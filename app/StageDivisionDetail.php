<?php

namespace App;

use App\Models\Webinar;
use Illuminate\Database\Eloquent\Model;

class StageDivisionDetail extends Model
{
    protected $guarded = ['id'];
    // protected $hidden = ['stage_divisions_id', 'webinar_id'];
    protected $with = ['webinar'];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }
}
