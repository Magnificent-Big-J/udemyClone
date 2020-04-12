<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /**
     * Fields for mass assignment
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = [];

    /**
     * A lesson belongs to a series
     *
     * @return void
     */
    public function series() {
        return $this->belongsTo(Series::class);
    }
}
