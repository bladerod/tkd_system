<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student_code' => $this->student_code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'photo_url' => $this->photo_url,
            'current_belt' => $this->current_belt,
            'join_date' => $this->join_date,
            'status' => $this->status,
            // 'branch' => new BranchResource($this->whenLoaded('branch')),
            // 'parents' => ParentResource::collection($this->whenLoaded('parents')),
            'attendance_count' => $this->whenCounted('attendanceLogs'),
            'created_at' => $this->created_at,
        ];
    }
}