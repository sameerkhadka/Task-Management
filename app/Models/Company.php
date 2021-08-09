<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $fillable = ['name'];
    use HasFactory;

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
