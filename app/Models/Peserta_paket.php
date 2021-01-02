<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta_paket extends Model
{
    use HasFactory;
    protected $table= "peserta_paket";

    public function peserta_paket(){
        return $this->belongsTo('\App\Models\Paket_soal');
    }
}
