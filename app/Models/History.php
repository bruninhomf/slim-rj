<?php

/*
 * File        : History.php
 * Description : Model da tabela de histórico
*/

namespace App\Models;

class History extends Model {

    protected $table      = 'history';
    protected $fillable   = [
        'user_id',
        'action',
        'ip',
    ];

    /*
    |--------------------------------------------------------------------------
    | Retorna o usuário que fez a ação
    |--------------------------------------------------------------------------
    */
    public function user(){
        return $this->belongsTo(User::class);
    }

}