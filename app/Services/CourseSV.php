<?php

namespace App\Services;
use Exception;
use App\Models\Course;
use App\Services\BaseService;

class CourseSV extends BaseService
{
    public function getQuery()
    {
        return Course::query()->with([
            'teacher' => function($query) {
                $query->select('id', 'first_name', 'last_name');
            }
        ]);
    }

    public function getAllCourses($params)
    {
        $query = $this->getQuery();
        return $this->getAll($query, $params);
    }

    public function createCourse($data)
    {
        try {
            $query = $this->getQuery();

            $course = $query->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'teacher_id' => $data['teacher_id'],
                'status' => isset($data['status']) ? $data['status'] : 1,
            ]);

            return $course;
        } catch (Exception $e) {
            throw new Exception('Error creating course: ' . $e->getMessage());
        }
    }

    public function getCourseById($id){
        try {
            $query = $this->getQuery();
            $course = $query->where('id',$id)->get();
            if (!$course) {
                throw new Exception("Course with ID $id not found.");
            }
            return $course;
        } catch (Exception $e) {
            throw new Exception('Error getting course: ' . $e->getMessage());
        }
    }

    public function updateCourse($id, $data){
        try {
            $course = $this->update($data, $id);
            if (!$course) {
                throw new Exception("Course with ID $id not found.");
            }
            return $course;
        } catch (Exception $e) {
            throw new Exception('Error updating course: ' . $e->getMessage());
        }
    }

    public function deactivateCourse($id)
    {
         try {
            $course = $this->getQuery()->findOrFail($id);
            $newStatus = $course->status == 1 ? 0 : 1;
            $this->getQuery()->where('id', $id)->update(['status' => $newStatus]);
            $course->refresh();
            return $course;
        } catch (\Exception $e) {
            throw new \Exception('Error toggling course status: ' . $e->getMessage(), 500);
        }
    }
}                   
