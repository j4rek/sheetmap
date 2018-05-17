<?php
namespace j4rek\sheetmap\helpers;

use \j4rek\database\database;

trait helpers{
    /**
     * 
     */
    static function setIdComuna($value){
        $cells = database::executeQuery('select * from ubicComunas where comuna= "' . $value . '";');
        return ($cells)?$cells[0]['idComuna'] : null;
    }

    /**
     * 
     */
    static function setIdOperacion($value){
        return strtolower($value) == 'venta' ? 1 : 2;
    }

    /**
     * 
     */
    static function setBool($value){
        return $value === 'SI'?1:0;
    }

    /**
     * 
     */
    static function setIdDivisa($value){
        switch(strtolower($value)){
            case "uf": return 2; break;
            case "$":
            case "pesos": return 1; break;
            case "usd": return 3; break;
            default: return 2;
        }
    }

    /**
     * 
     */
    static function clean($value){
        return addslashes(str_replace('$', '$ ', preg_replace_callback('/_x([0-9a-fA-F]{4})_/', function ($matches) {
            return '<br>';
        }, $value)));
    }

    /**
     * 
     */
    static function checkZero($value){
        return $value === 0 ? '' : $value;
    }

    /**
     * 
     */
    static function setTipo($value){
        if(strtolower($value) == "local") $value = "local comercial";
        if(strtolower($value) == "parcela") $value = "Parcela de agrado";
        $q = 'select idPropTipo from proptipos where propTipo = "' . $value . '";';
        $a = database::executeQuery($q);
        return $a[0]['idPropTipo'];
    }
}