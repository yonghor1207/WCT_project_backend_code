<?php

namespace App\Services;
use Exception;
use App\Models\Classroom;
use App\Services\BaseService;
class ClassroomSV extends BaseService
{
    public function getQuery()
    {
        return Classroom::query();
    }

    public function getAllClassrooms($params)
    {
        $query = $this->getQuery();

        return $this->getAll($query, $params);
    }

    public function createClassroom($data){
       try {
            $query = $this->getQuery();
            
            // $active = isset($data['active']) ? $data['active'] : 1;

            $classroom = $query->create([
                'class_name'     => $data['class_name'],
            ]);
            
            return $classroom;
        } catch (Exception $e) {
            throw new Exception('Error creating classroom: ' . $e->getMessage());
        }
    }

 
    public function getClassroomById($id)
    {
        try {
            $query = $this->getQuery();
            $classroom = $query->where('id', $id)->get();
            return $classroom;
        } catch (Exception $e) {
            throw new Exception('Error get classroom: ' . $e->getMessage());
        }
    }


    public function updateClassroom($id,$data){
       try {
            $classroom = $this->update($data, $id);
           
            return $classroom;
        } catch (\Exception $e) {
            throw new \Exception('Error updating classroom: ' . $e->getMessage(), 500);
        }
    }
 


}
