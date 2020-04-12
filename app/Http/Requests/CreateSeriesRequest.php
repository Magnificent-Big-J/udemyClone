<?php

namespace App\Http\Requests;

use App\Series;
use Illuminate\Support\Str;

class CreateSeriesRequest extends SeriesRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ];
    }

    public function storeSeries()
    {
       $series = Series::create([
            'title'=> $this->title,
            'description' => $this->description,
            'image_url' => 'series/' . $this->fileName,
            'slug' => Str::slug($this->title)
        ]);
        session()->flash('success', 'Series created successfully.');
        return redirect()->route('series.show', $series->slug);
    }
}
