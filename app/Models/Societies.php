<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societies extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'activity_field', 'address', 'postal_code', 'city', 'cesi_interns', 'evaluation'];
}
