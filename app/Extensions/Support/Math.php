<?php

/*
 * File        : Math.php
 * Description : Extensão do manipulador de floats
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

class Math {

    /*
    |--------------------------------------------------------------------------
    | Retorna uma porcentagem
    |--------------------------------------------------------------------------
    */
    public static function percent($percent, $total) {
        return ( $percent / 100 ) * $total;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna uma porcentagem de um número em relação a outro
    |--------------------------------------------------------------------------
    */
    public static function relativePercent($partial, $total){
        return number_format(((($partial - $total) / $total) * 100), 1, ',');
    }

    /*
    |--------------------------------------------------------------------------
    | Formata o número passado para decimal afim de armazenamento no database
    |--------------------------------------------------------------------------
    */
    public static function parse($money) {
        return str_replace(['.', ','], ['', '.'], $money);
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a string é um valor decimal
    |--------------------------------------------------------------------------
    */
    public static function equalsFormat($money, $format = "/^[0-9.,]+$/"){
        return preg_match($format, $money);
    }

    /*
    |--------------------------------------------------------------------------
    | Formata o número passado para o padrão de moeda brasileiro
    |--------------------------------------------------------------------------
    */
    public static function toMonetary($money, $ignore = false) {
        if(!$ignore){
            return number_format($money, 2, ',', '.');
        }else{
            return ($money > 0) ? number_format($money, 2, ',', '.') : '-';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Formata o número por extenso
    |--------------------------------------------------------------------------
    */
    public static function toWords($valor = 0){

        $singular = ["centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão"];
        $plural = ["centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões"];
        $u = ["", "um", "dois", "três", "quatro", "cinco", "seis",  "sete", "oito", "nove"];

        $c = ["", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];
        $d = ["", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
        $d10 = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"];

        $z = 0;
        $rt = "";

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for($i=0;$i<count($inteiro);$i++)
            for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
                $inteiro[$i] = "0".$inteiro[$i];

        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
        for ($i=0;$i<count($inteiro);$i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
                    $ru) ? " e " : "").$ru;
            $t = count($inteiro)-1-$i;
            $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")$z++; elseif ($z > 0) $z--;
            if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        return $rt ? $rt : "zero";

    }


}