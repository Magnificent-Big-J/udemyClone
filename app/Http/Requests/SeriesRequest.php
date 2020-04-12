<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SeriesRequest extends FormRequest
{
     public function uploadSeriesImage()
    {
        $uploadImage = $this->image;

        $this->fileName = Str::slug($this->title) .".". $uploadImage->getClientOriginalExtension();
        $uploadImage->storePubliclyAs('public/series', $this->fileName);

        return $this;
    }
}
