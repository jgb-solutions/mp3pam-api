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
            'admin'     => (boolean) $this->admin,
            'facebook'  => $this->facebook_link,
            'musics'    => $this->musics_url,
            'telephone' => $this->telephone,
            'type'      => $this->type
        ];
    }
}
