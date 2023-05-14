<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeView extends Model
{
    use HasFactory;
    protected $table='resep_view';
    public $timestamps=false;
    protected $fillable=['email','date','resep_idresep'];
}
