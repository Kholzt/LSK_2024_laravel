<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responses extends Model
{
    use HasFactory;
    protected $table = "responses";
    protected $fillable = ["form_id", "user_id", "date"];
    public $timestamps = false;

    public function answer()
    {
        return $this->hasMany(Answers::class, "response_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
