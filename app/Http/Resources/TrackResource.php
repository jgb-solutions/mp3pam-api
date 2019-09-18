<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TrackResource extends Resource
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
            'title'         	=> $this->title,
            'detail'        	=> $this->detail,
            'lyrics'        	=> $this->lyrics,
            'url'           	=> $this->url,
            'publicUrl'         => $this->public_url,
            'hash'				=> $this->hash,
            'play_count'    	=> $this->play_count,
            'play_url'    		=> $this->play_url,
            'download_count'	=> $this->download_count,
            'download_url'  	=> $this->download_url,
            'image'				=> $this->image_url,
            'category'      	=> new CategoryResource($this->category),
            'artist' 			=> new ArtistResource($this->artist)
        ];
    }
}
