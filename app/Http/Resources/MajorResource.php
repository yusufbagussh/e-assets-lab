<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MajorResource extends JsonResource
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
            'jurusan_id' => $this->jurusan_id,
            'jurusan_nama' => $this->jurusan_nama,
            'jurusan_fakultas' => $this->jurusan_fakultas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
