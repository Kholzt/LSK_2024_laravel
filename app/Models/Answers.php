<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    use HasFactory;
    protected $table = "answers";
    protected $fillable = ["date", "question_id", "response_id", "value"];
    public $timestamps = false;
}
