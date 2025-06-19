<?php

namespace App\Services;
use App\Models\Attendance;
use Exception;
use App\Services\BaseService;
class AttendanceSV extends BaseService
{
    public function getQuery()
    {
        return Attendance::query()->with(['user', 'classroom', 'course']);
    }

    public function getAllAttendances($params)
    {
        $query = $this->getQuery();
        return $this->getAll($query, $params);
    }

    public function createAttendance($data)
    {
        try {
            $query = $this->getQuery();
            $attendance = $query->create([
                'student_id' => $data['student_id'],
                'classroom_id' => $data['classroom_id'],
                'course_id' => $data['course_id'],
                'attendance_date' => $data['attendance_date'],
                'status' => $data['status'] ?? 'not_mark', // e.g., 'present', 'absent', 'late'
            ]);
            if (!$attendance) {
                throw new Exception('Failed to create attendance record.');
            }
            return $attendance;
        } catch (Exception $e) {
            throw new Exception('Error creating attendance: ' . $e->getMessage());
        }
    }

    public function getAttendanceById($id)
    {
        try {
            $attendance = $this->getById($id);
            if (!$attendance) {
                throw new Exception("Attendance with ID $id not found.");
            }
            return $attendance;
        } catch (Exception $e) {
            throw new Exception('Attendance not found: ' . $e->getMessage());
        }
    }

    public function updateAttendance($id, $data)
    {
        try {
            $attendance = $this->update($data, $id);
            return $attendance;
        } catch (Exception $e) {
            throw new Exception('Error updating attendance: ' . $e->getMessage());
        }
    }

    public function verifyAttendance($id, $data)
    {
        try {
            $attendance = $this->getQuery()->findOrFail($id);
            $this->getQuery()->where('id',$id)->update(['status'=>$data]);
            return $data;
        } catch (Exception $e) {
            throw new Exception('Error verifying attendance: ' . $e->getMessage());
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