<?php
namespace j4rek\sheetmap\helpers;

class html{
    /**
     * 
     */
    static function select($name, $options){
        $html= "<div>
            <select name=\"$name\" id=\"$name\"><option value=\"none\" selected=\"selected\">No aplica</option>";
                foreach($options as $k => $option){
                    $html .= "<option value=\"" . $k . "\">" . $option . "</option>";
                }
        return $html .="</select></div>";
    }

    /**
     * 
     */
    static function div($content){
        return $html = "<div>$content</div>";
    }

    /**
     * 
     */
    static function form($name,$action,$content, $method = 'post'){
        return $html = "<form name='$name' id='$name' action='$action' method='$method'>$content
        <div><p></p><button type='submit'>Enviar</button></div></form>";
    }
}