<?php
namespace j4rek\sheetmap\helpers;

class chrono{
    static $timezone;

    static function setTimezone($timezone){
        self::$timezone = new \DateTimeZone($timezone);
    }

    static function now($format){
        $now = new \DateTime();
        $now->setTimezone(self::$timezone);
        return $now->format($format);
    }

    static function moveDays($days, $action='+'){
        $now = new \DateTime();
        $now->setTimezone(self::$timezone);
        
        if($action === '+')
            return $now->add(new \DateInterval('P' . $days . 'D'));
        else
            return $now->sub(new \DateInterval('P' . $days . 'D'));
    }
}