<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipOffers extends Model
{
    use HasFactory;
    protected $fillable = ['content', '	offer_start', 'offer_end', 'end_date', 'society_id'];
}
