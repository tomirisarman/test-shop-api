<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'items' => OrderItemResource::collection($this->orderItems),
            'created_at' => date('d.m.Y H:m:s', strtotime($this->created_at)),
            'updated_at' => date('d.m.Y H:m:s', strtotime($this->updated_at)),
        ];
    }
}
