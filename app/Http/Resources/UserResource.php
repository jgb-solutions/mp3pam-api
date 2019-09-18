<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
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
            'name'      => $this->name,
            'email'     => $this->email,
            'avatar'    => $this->avatar_url,
            'active'    => (boolean) $this->active,
            'facebook'  => $this->facebook_link,
            'tracks'    => $this->when($this->type == 'artist', $this->tracks_url),
            'telephone' => $this->telephone,
            'type'      => $this->type
        ];
    }
}
