<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'slug'=>$this->slug,
            'price'=>$this->price,
            'category' => new CategoryResource($this->category),
            'features'=> ProductFeatureResource::collection($this->features),
        ];
    }

}
