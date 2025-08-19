<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'category',
        'difficulty',
        'points'
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function getCorrectAnswerTextAttribute()
    {
        $options = [
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
        ];
        
        return $options[$this->correct_answer] ?? 'غير محدد';
    }

    public function getDifficultyTextAttribute()
    {
        return match($this->difficulty) {
            'easy' => 'سهل',
            'medium' => 'متوسط',
            'hard' => 'صعب',
            default => 'غير محدد'
        };
    }

    public function getCategoryTextAttribute()
    {
        return match($this->category) {
            'general' => 'عام',
            'programming' => 'برمجة',
            'database' => 'قواعد البيانات',
            'networking' => 'شبكات',
            'security' => 'أمن المعلومات',
            default => 'غير محدد'
        };
    }
}
