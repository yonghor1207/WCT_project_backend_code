<?php

namespace App\Http\Controllers\Api\v1\Admin;
use App\Http\Controllers\Api\v1\BaseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Services\PaymentSV;
use Illuminate\Support\Facades\DB;

class PaymentController extends BaseAPI
{
   protected $paymentService;
   public function __construct()
   {
       $this->paymentService = new PaymentSV();
   }

   public function getAllPayments(){
         $payments = $this->paymentService->getQuery()->get();
         return $this->successResponse($payments, 'Payments retrieved successfully');
   }

    public function store(StorePaymentRequest $request){
        try {
            DB::beginTransaction();
            $params = $request->validated();
            $payment = $this->paymentService->createPayment($params);
            DB::commit();
            return $this->successResponse($payment, 'Payment created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getPayment($id){
        try {
            $payment = $this->paymentService->getPaymentById($id);
            return $this->successResponse($payment, 'Payment retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode(), 404);
        }
    }

    public function updatePayment(UpdatePaymentRequest $request, $id){
        try {
            $params = $request->validated();
            DB::beginTransaction();
            $updatedPayment = $this->paymentService->updatePayment($id, $params);
            DB::commit();
            return $this->successResponse($updatedPayment, 'Payment updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function verifyPayment($id)
    {
        try {
            DB::beginTransaction();
            $payment = $this->paymentService->verifyPayment($id);
            DB::commit();

            return $this->successResponse($payment, 'Payment verified successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
   
       
}
