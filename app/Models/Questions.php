<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;
    protected $table = "questions";
    protected $fillable = ["name", "form_id", "choice_type", "choices", "is_required"];
    public $timestamps = false;
}
