<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlists extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id', 'internship_offer_id'];
}
