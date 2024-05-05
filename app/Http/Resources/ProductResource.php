<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the URL of the uploaded image
        // $fileUrl = storage_path('app') . "/" . $this->img;

        // $imageName = uniqid() . '.' . $validated['img']->extension();

        // $validated['img']->move(public_path('uploads'), $imageName);

        $fileUrl = url('uploads/' . $this->img);




        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $fileUrl,
            'product_type' => $this->product_type,
            'price' => $this->price,
            'in_stock' => $this->in_stock,
            'category' => $this->category,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
