<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;   
    }    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 'success',
        ]+$this->data;
    }
}
