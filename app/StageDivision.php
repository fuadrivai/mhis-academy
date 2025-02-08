<?php

namespace App;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class StageDivision extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['location_id', 'level_id', 'category_id', 'created_by_id'];
    protected $with = ['location', 'category', 'level', 'created_by', 'details'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function created_by()
    {
        return $this->belongsTo(User::class);
    }
    public function details()
    {
        return $this->hasMany(StageDivisionDetail::class, 'stage_divisions_id', 'id');
    }
}
