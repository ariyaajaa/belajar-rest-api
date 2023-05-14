<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredients extends Model
{
    use HasFactory;

    protected $table = 'bahan';
    
    protected $fillable = ['nama','satuan','banyak','keterangan','resep_idresep'];
}
