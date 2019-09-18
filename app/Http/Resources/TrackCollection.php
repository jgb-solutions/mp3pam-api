<?php

namespace App\Http\Resources;

use App\Http\Resources\TrackResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TrackCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function($track) {
                return new TrackResource($track);
            })
        ];
    }
}
