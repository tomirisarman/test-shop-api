<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'user' => $this->user!==null ? new UserResource($this->user) : null,
            'created_at' => date('d.m.Y H:m:s', strtotime($this->created_at)),
            'updated_at' => date('d.m.Y H:m:s', strtotime($this->updated_at)),
        ];
    }
}
