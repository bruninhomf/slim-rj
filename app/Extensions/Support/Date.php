<?php

/*
 * File        : Date.php
 * Description : Extensão do manipulador de datas
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

use Illuminate\Support\Carbon;

class Date extends Carbon {

    /*
    |--------------------------------------------------------------------------
    | Formata a string passada para data em padrão americano
    |--------------------------------------------------------------------------
    */
    public static function toYMD($date) {
        return date("Y-m-d", strtotime(str_replace('/', '-', $date)));
    }

    /*
    |--------------------------------------------------------------------------
    | Formata a string passada para um unix-timestamp
    |--------------------------------------------------------------------------
    */
    public static function toYMDHIS($date){
        return date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $date)));
    }

    /*
    |--------------------------------------------------------------------------
    | Formata a string passada para data em padrão brasileiro
    |--------------------------------------------------------------------------
    */
    public static function toDMY($date, $format = "d/m/Y") {
        return date($format, strtotime(str_replace('/', '-', $date)));
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna a data em uma string legivel
    |--------------------------------------------------------------------------
    */
    public static function toEXT($date, $hours = false){
        if(!empty($date)){
            $d = date('d', strtotime(str_replace('/', '-', $date)));
            $m = date('m', strtotime(str_replace('/', '-', $date)));
            $y = date('Y', strtotime(str_replace('/', '-', $date)));
            $h = date('H:i', strtotime(str_replace('/', '-', $date)));
            $f =  $d.' de '. self::mtn($m).' de '.$y;
            return (!$hours) ? $f : $f. ' às '.$h;
        }else{
            return '-';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a string é uma data
    |--------------------------------------------------------------------------
    */
    public static function isYMD($date){
        try{
            return self::createFromFormat("Y-m-d", $date, APP_TIMEZONE)->format("Y-m-d") == $date;
        }catch (\InvalidArgumentException $e){
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a string é uma data e hora
    |--------------------------------------------------------------------------
    */
    public static function isYMDHIS($date){
        try{
            return self::createFromFormat("Y-m-d H:i:s", $date, APP_TIMEZONE)->format("Y-m-d H:i:s") == $date;
        }catch (\InvalidArgumentException $e){
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a string é uma data válida
    |--------------------------------------------------------------------------
    */
    public static function equalsFormat($date, $format){
        try{
            return self::createFromFormat($format, $date, APP_TIMEZONE)->format($format) === $date;
        }catch (\InvalidArgumentException $e){
            return !1;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica uma data está dentro de um itervalo
    |--------------------------------------------------------------------------
    */
    public static function in($in, $start, $end){

        //passa as datas para o padrão timestamp
        $start_ts = strtotime($start);
        $end_ts   = strtotime($end);
        $user_ts  = strtotime($in);

        //verifica se está dentro do range
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se uma data é maior que a outra
    |--------------------------------------------------------------------------
    */
    public static function greather($major, $minor){
        return self::createFromFormat('Y-m-d', $major, APP_TIMEZONE)->gte(
            self::createFromFormat('Y-m-d', $minor, APP_TIMEZONE)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formata o número do mês se ele for menor que dez
    |--------------------------------------------------------------------------
    */
    public static function fm($month){
        $month = (int) $month;
        return ($month < 10) ? (($month > 0) ? "0{$month}" : 0) : $month;
    }

    /*
    |--------------------------------------------------------------------------
    |  Retorna o nome do mês
    |--------------------------------------------------------------------------
    */
    public static function mtn($month) {
        return [
            "01" => 'Janeiro',
            "02" => 'Fevereiro',
            "03" => 'Março',
            "04" => 'Abril',
            "05" => 'Maio',
            "06" => 'Junho',
            "07" => 'Julho',
            "08" => 'Agosto',
            "09" => 'Setembro',
            "10" => 'Outubro',
            "11" => 'Novembro',
            "12" => 'Dezembro'
        ][self::fm($month)];
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o nome do dia
    |--------------------------------------------------------------------------
    */
    public static function dyn($day) {
        return [
            "01" => 'Segunda-Feira',
            "02" => 'Terça-Feira',
            "03" => 'Quarta-Feira',
            "04" => 'Quinta-Feira',
            "05" => 'Sexta-Feira',
            "06" => 'Sábado',
            "00" => 'Domingo'
        ][self::fm($day)];
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o nome do dia pela abreviação
    |--------------------------------------------------------------------------
    */
    public static function abreviation($day) {
        return [
            1 => 'Segunda-Feira',
            2 => 'Terça-Feira',
            3 => 'Quarta-Feira',
            4 => 'Quinta-Feira',
            5 => 'Sexta-Feira',
            6 => 'Sábado',
            0 => 'Domingo'
        ][$day];
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o número do dia da semana
    |--------------------------------------------------------------------------
    */
    public static function weekNumber($day) {
        return [
            'mo' => 1,
            'tu' => 2,
            'we' => 3,
            'th' => 4,
            'fr' => 5,
            'sa' => 6,
            'su' => 0
        ][$day];
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o número do dia da semana
    |--------------------------------------------------------------------------
    */
    public static function weekDayName($day) {
        return [
            'mo' => 'Segunda-Feira',
            'tu' => 'Terça-Feira',
            'we' => 'Quarta-Feira',
            'th' => 'Quinta-Feira',
            'fr' => 'Sexta-Feira',
            'sa' => 'Sábado',
            'su' => 'Domingo'
        ][$day];
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o número do dia da semana
    |--------------------------------------------------------------------------
    */
    public static function weekDayAbreviation($day) {
        return [
            1 => 'mo',
            2 => 'tu',
            3 => 'we',
            4 => 'th',
            5 => 'fr',
            6 => 'sa',
            0 => 'su'
        ][$day];
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna a descrição do filtro de data aplicado
    |--------------------------------------------------------------------------
    */
    public static function description($start, $end){

        //inicializa o carbon
        $now       = self::createFromFormat('Y-m-d', CURRENT_DATE);

        //pega o dia atual
        $today     = $now->copy()->format('Y-m-d');

        //pega o dia anterior
        $yesterday = $now->copy()->subDays(1)->format('Y-m-d');

        //pega o primeiro range do mês atual
        $currentMonth  = [
            'start' => $now->copy()->startOfMonth()->format('Y-m-d'),
            'end'   => $now->copy()->endOfMonth()->format('Y-m-d')
        ];

        //pega o range do mês anterior
        $lastMonth  = [
            'start' => $now->copy()->subMonth(1)->startOfMonth()->format('Y-m-d'),
            'end'   => $now->copy()->subMonth(1)->endOfMonth()->format('Y-m-d')
        ];

        //hoje
        if($start == $today and $end == $today){
            return "Hoje";
        }

        //ontem
        if($start == $yesterday and $end == $yesterday){
            return "Ontem";
        }

        //7 dias
        if($start == $now->copy()->subDays(7)->format('Y-m-d') and $end == $now->copy()->format('Y-m-d')){
            return "Últimos 15 dias";
        }

        //15 dias
        if($start == $now->copy()->subDays(15)->format('Y-m-d') and $end == $now->copy()->format('Y-m-d')){
            return "Últimos 15 dias";
        }

        //este mês
        if($start == $currentMonth['start'] and $end == $currentMonth['end']){
            return "Este mês";
        }

        //mês passado
        if($start == $lastMonth['start'] and $end == $lastMonth['end']){
            return "Mês passado";
        }

        //outro
        return self::toDMY($start) . ' à ' . self::toDMY($end);

    }

    /*
    |--------------------------------------------------------------------------
    | Retorna a data por extenso
    |--------------------------------------------------------------------------
    */
    public static function toWords($date){
        if(!empty($date)){
            $dateD = date('d', strtotime(str_replace('/', '-', $date)));
            $dateM = date('m', strtotime(str_replace('/', '-', $date)));
            $dateY = date('Y', strtotime(str_replace('/', '-', $date)));
            $dateH = date('H:i', strtotime(str_replace('/', '-', $date)));
            return $dateD.' de '.self::mtn($dateM).' de '.$dateY. ' às '.$dateH;
        }else{
            return '-';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o próximo dia útil a partir da data
    |--------------------------------------------------------------------------
    */
    public function nextUsefulDay(){

        while ($this->isWeekend()) {
            $this->addDay(1);
        }

        return $this;

    }

    //overrides

    public static function now($tz = null){
        return parent::now(APP_TIMEZONE);
    }

    public static function createFromFormat($format, $time, $tz = null){
        return parent::createFromFormat($format, $time, APP_TIMEZONE);
    }

    public static function createFromYMD($time){
        return parent::createFromFormat('Y-m-d', $time, APP_TIMEZONE);
    }

    public static function createFromYMDHIS($time){
        return parent::createFromFormat('Y-m-d H:i:s', $time, APP_TIMEZONE);
    }

    public static function createFromHI($time){
        return parent::createFromFormat('H:i', $time, APP_TIMEZONE);
    }

    public static function createFromHIS($time){
        return parent::createFromFormat('H:i:s', $time, APP_TIMEZONE);
    }

}