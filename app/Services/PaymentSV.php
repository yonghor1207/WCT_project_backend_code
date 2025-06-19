<?php

namespace App\Services;
use Exception;
use App\Models\Payment;
use App\Services\BaseService;

class PaymentSV extends BaseService
{
    public function getQuery()
    {
        return Payment::query()->with([
            'student' => function($query){
                $query->select('id','first_name','last_name');
            } 
        ]);
    }

    public function getAllPayments($params)
    {
        $query = $this->getQuery();

        return $this->getAll($query, $params);
    }

    protected function generateTransactionId()
    {
        return 'TXN' . date('YmdHis') . rand(1000, 9999); // e.g., "TXN202505251230451234"
    }

    public function createPayment($data)
    {
        try {
            $query = $this->getQuery();

            // Generate a unique transaction ID if not provided
            if (!isset($data['transaction_id'])) {
                $data['transaction_id'] = $this->generateTransactionId();
            }

            $payment = $query->create([
                'amount' => $data['amount'],
                'status' => $data['status'] ?? 'pending',
                'user_id' => $data['user_id'],
                'payment_method' => $data['payment_method'],
                'payment_type' => $data['payment_type'],
                'transaction_id' => $data['transaction_id'], 
            ]);

            return $payment;
        } catch (Exception $e) {
            throw new Exception('Error creating payment: ' . $e->getMessage());
        }
    }


    public function getPaymentById($id)
    {
        try {
            $query = $this->getQuery();
            $payment = $query->where('id',$id)->get();
            if (!$payment) {
                throw new Exception("Payment with ID $id not found.");
            }
            return $payment;
        } catch (Exception $e) {
            throw new Exception('Error getting payment: ' . $e->getMessage());
        }
    }

    public function updatePayment($id, $data)
    {
        try {
            $payment = $this->update($data, $id);
            return $payment;
        } catch (Exception $e) {
            throw new Exception('Error updating payment: ' . $e->getMessage());
        }
    }

    public function verifyPayment($id)
    {
        try {
            $payment = $this->getQuery()->findOrFail($id);
            $newPayment = $payment->status == 'pending' ? 'paid' : 'pending';
            $this->getQuery()->where('id', $id)->update(['status' => $newPayment]);
            return $newPayment;
        } catch (Exception $e) {
            throw new Exception('Error verifying payment: ' . $e->getMessage());
        }
    }
}           