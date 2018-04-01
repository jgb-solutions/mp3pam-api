<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArtistResource extends Resource
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
            'name'          => $this->name,
            'stageName'     => $this->stage_name,
            'hash'          => $this->hash,
            'avatar'        => $this->avatar_url,
            'verified'      => (boolean) $this->verified
        ];
    }
}
