<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; //трейт, который добавляет поддержку фабрик моделей.
use Illuminate\Database\Eloquent\Model; //базовый класс для моделей Eloquent

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'title',
        'description',
        'what_they_will_learn',
        'price',
        'duration',
        'category',
        'image',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function statistics()
    {
        return $this->hasMany(Statistic::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
