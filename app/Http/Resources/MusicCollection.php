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
                return [
                    'title'         => $music->title,
                    'detail'        => $music->detail,
                    'lyrics'        => $music->lyrics,
                    'url'           => $music->url,
                    'play_count'    => $music->play_count,
                    'play_url'      => $music->play_url,
                    'download_count'=> $music->download_count,
                    'download_url'  => $music->download_url,
                    'image_url'     => $music->image_url,
                    'category'      => $music->category,
                    'artist'        => $music->artist
                ];
            })
        ];
    }
}
