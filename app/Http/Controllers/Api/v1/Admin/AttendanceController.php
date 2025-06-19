<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Services\AttendanceSV;
use App\Http\Controllers\Api\v1\BaseAPI;
use Illuminate\Http\Request;

class AttendanceController extends BaseAPI
{
    protected $attendanceService;
    public function __construct()
    {
        $this->attendanceService = new AttendanceSV();
    }

    public function getAllAttendances()
    {
        $attendances = $this->attendanceService->getQuery()->get();
        return $this->successResponse($attendances, 'Attendances retrieved successfully');
    }

    public function store(StoreAttendanceRequest $request)
    {
        try {
            $params = $request->validated();
            $attendance = $this->attendanceService->createAttendance($params);
            return $this->successResponse($attendance, 'Attendance created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getAttendance($id)
    {
        try {
            $attendance = $this->attendanceService->getAttendanceById($id);
            return $this->successResponse($attendance, 'Attendance retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode(), 404);
        }
    }

    public function updateAttendance(UpdateAttendanceRequest $request, $id)
    {
        try {
            $params = $request->validated();
            $updatedAttendance = $this->attendanceService->updateAttendance($id, $params);
            return $this->successResponse($updatedAttendance, 'Attendance updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function verifyAttendance(Request $request,$id)
    {
        $status = $request->input('status');
        try {
            $attendance = $this->attendanceService->verifyAttendance($id,$status);
            return $this->successResponse($attendance, 'Attendance verified successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }




}