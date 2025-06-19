<?php

namespace App\Http\Controllers\Api\v1\Admin;
use App\Http\Controllers\Api\v1\BaseAPI;
use App\Services\CourseSV;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class CourseController extends BaseAPI
{
   protected $courseService;
   public function __construct()
   {
       $this->courseService = new CourseSV();
   }

    public function getAllCourses(Request $request){
        $filters = [];

        $params = [
            'filterBy' => $filters,
            'perPage' => $request->query('perPage', 10), // default to 10
        ];
        $courses = $this->courseService->getAllCourses($params);
        return $this->successResponse($courses, 'Courses retrieved successfully');
    }

    public function store(StoreCourseRequest $request){
        try {
            $params = $request->validated();
            DB::beginTransaction();
            $course = $this->courseService->createCourse($params);
            DB::commit();
            return $this->successResponse($course, 'Course created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getCourse($id){
        try {
            $course = $this->courseService->getCourseById($id);
            return $this->successResponse($course, 'Course retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode(), 404);
        }
    }

    public function updateCourse(UpdateCourseRequest $request, $id){
        try {
            $params = $request->validated();
            DB::beginTransaction();
            $updatedCourse = $this->courseService->updateCourse($id, $params);
            DB::commit();
            return $this->successResponse($updatedCourse, 'Course updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function deactivateCourse($id){
        try {
            DB::beginTransaction();
            $course = $this->courseService->deactivateCourse($id);
            DB::commit();
            if (!$course) {
                return $this->errorResponse('Course not found', 404);
            }
            return $this->successResponse($course, 'Course deactivated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
