<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    use HasFactory;
    protected $table = "forms";
    protected $fillable = ["name", "slug", "description", "limit_one_response", "creator_id"];
    public $timestamps = false;

    public function domains()
    {
        return $this->hasMany(AllowedDomains::class, "form_id");
    }
    public function questions()
    {
        return $this->hasMany(Questions::class, "form_id");
    }
}
