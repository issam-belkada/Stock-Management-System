<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Liste des champs autorisés pour le "mass assignment"
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
}
