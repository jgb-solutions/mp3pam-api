<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MusicResource extends Resource
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
            'title'         => $this->title,
            'detail'        => $this->detail,
            'lyrics'        => $this->lyrics,
            'url'           => $this->url,
            'play_count'    => $this->play_count,
            'download_count'=> $this->download_count,
            'download_url'  => $this->download_url,
            'image_url'     => $this->url,
            'category'      => $this->category
        ];
    }
}
