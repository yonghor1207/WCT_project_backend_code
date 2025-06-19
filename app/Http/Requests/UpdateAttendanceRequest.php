<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'sometimes|required|exists:users,id',
            'classroom_id' => 'sometimes|required|exists:classrooms,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'attendance_date' => 'sometimes|required|datetime',
            'status' => 'sometimes|required|in:present,absent,late', 
        ];
    }
}
