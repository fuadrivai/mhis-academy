<?php

namespace App\Models;

use App\Models\Traits\SequenceContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Quiz extends Model implements TranslatableContract
{
    use Translatable;
    use SequenceContent;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    public $timestamps = false;
    protected $table = 'quizzes';
    protected $guarded = ['id'];
    protected $with = ['quizResults'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }


    public function quizQuestions()
    {
        return $this->hasMany('App\Models\QuizzesQuestion', 'quiz_id', 'id');
    }

    public function quizResults()
    {
        return $this->hasMany('App\Models\QuizzesResult', 'quiz_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function certificates()
    {
        return $this->hasMany('App\Models\Certificate', 'quiz_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\WebinarChapter', 'chapter_id', 'id');
    }


    public function increaseTotalMark($grade)
    {
        $total_mark = $this->total_mark + $grade;
        return $this->update(['total_mark' => $total_mark]);
    }

    public function decreaseTotalMark($grade)
    {
        $total_mark = $this->total_mark - $grade;
        return $this->update(['total_mark' => $total_mark]);
    }

    public function getUserCertificate($user, $quiz_result)
    {
        if (!empty($user) and !empty($quiz_result)) {
            return Certificate::where('quiz_id', $this->id)
                ->where('student_id', $user->id)
                ->where('quiz_result_id', $quiz_result->id)
                ->first();
        }

        return null;
    }
}
