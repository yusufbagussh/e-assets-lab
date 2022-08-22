<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'lokasi_id' => $this->lokasi_id,
            'lokasi_nama' => $this->lokasi_nama,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
