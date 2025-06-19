<?php

namespace App\Http\Controllers\Api\v1\Admin;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\v1\BaseAPI;
use App\Services\ClassroomSV;
class ClassroomController extends BaseAPI
{
    protected $classroomSV;
    public function __construct()
    {
        $this->classroomSV = new ClassroomSV();
    }

    public function getAllClassrooms()
    {
        $classromms = $this->classroomSV->getQuery()->get();

        return $this->successResponse($classromms, 'classroom retrieved successfully');
    }

    public function store(StoreClassroomRequest $request){
        try{
            $params = $request->validated();

            DB::beginTransaction();
            $classroom = $this->classroomSV->createClassroom($params);
            DB::commit();
            return $this->successResponse($classroom, 'classroom created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getClassrooms($id)
    {
        try {
            $classroom = $this->classroomSV->getClassroomById($id);
            return $this->successResponse($classroom, 'classroom retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode(), 404);
        }
    }

    public function updateClassroom(UpdateClassroomRequest $request,$id){
        try {
        
            $params = $request->only([
                'class_name',
            ]);


            DB::beginTransaction();
            $updateClassroom = $this->classroomSV->updateClassroom($id, $params);
            DB::commit();

            return $this->successResponse($updateClassroom, 'classroom updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


}
