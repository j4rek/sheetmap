<?php
namespace j4rek\sheetmap\helpers;

use \j4rek\database\database;

trait helpers{
    static function setIdComuna($value){
        $cells = database::executeQuery('select * from ubicComunas where comuna= "' . $value . '";');
        return ($cells)?$cells[0]['idComuna'] : null;
    }

    static function setIdOperacion($value){
        return strtolower($value) == 'venta' ? 1 : 2;
    }

    static function setBool($value){
        return $value === 'SI'?1:0;
    }

    static function setIdDivisa($value){
        return $value;
    }
}