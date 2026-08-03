<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicineCategoryCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'categories' => $this->collection->map(function ($category) {

                return [
                    'id' => $category->id,

                    'category_name' =>
                        $category->category_name,

                    'slug' =>
                        $category->slug,

                    'image' =>
                        $category->image,

                    'description' =>
                        $category->description,

                    'sort_order' =>
                        $category->sort_order,

                    'status' =>
                        $category->status,
                ];
            }),
        ];
    }
}