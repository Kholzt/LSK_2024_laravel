<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedDomains extends Model
{
    use HasFactory;
    protected $table = "allowed_domains";
    protected $fillable = ["domain", "form_id"];
    public $timestamps = false;
}
