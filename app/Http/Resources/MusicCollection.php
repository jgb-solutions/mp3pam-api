<?php

namespace App\Http\Resources;

use App\Http\Resources\MusicResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MusicCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function($music) {
                return new MusicResource($music);
            })
        ];
    }
}
