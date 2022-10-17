<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'created_at' => date('d.m.Y H:m:s', strtotime($this->created_at)),
            'updated_at' => date('d.m.Y H:m:s', strtotime($this->updated_at)),
        ];
    }
}
