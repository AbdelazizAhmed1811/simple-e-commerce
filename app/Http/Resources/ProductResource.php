<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'img' => $this->img,
            'product_type' => $this->product_type,
            'price' => $this->price,
            'in_stock' => $this->in_stock,
            'categories' => $this->categories,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];

        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'description' => $this->description,
        //     'comments' => CommentResource::collection($this->whenLoaded('comments')),
        // ];
    }
}
