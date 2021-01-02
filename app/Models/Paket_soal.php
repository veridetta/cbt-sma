<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket_soal extends Model
{
    use HasFactory;
    protected $table= "paket_soal";

    public function peserta_paket(){
        return $this->hasMany('\App\Models\Peserta_paket');
    }
}
