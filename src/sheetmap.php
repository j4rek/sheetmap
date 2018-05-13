<?php
namespace j4rek\sheetmap;

use \Box\Spout\Reader\ReaderFactory;
use \Box\Spout\Common\Type;
use \j4rek\sheetmap\helpers\html;
use \j4rek\sheetmap\helpers\helpers;

class sheetmap{
    use helpers;

    static $excel;

    /**
     * 
     */
    static function loadFile($xlsFile){
        self::$excel = ReaderFactory::create(Type::XLSX);
        self::$excel->open($xlsFile);
    }

    /**
     * 
     */
    static function generateForm($xlsFile, $headers){
        self::loadFile($xlsFile);
        $body = "";
        foreach($headers as $header){
            foreach(self::$excel->getSheetIterator() as $sheet){
                $options = [];
                foreach($sheet->getRowIterator() as $row){
                    $body .= html::div($header[0] . html::select($header[0] . '|' . (isset($header[1]) && $header[1] != 'filtro'?$header[1]: '') , $row));
                    break;
                }
            }
        }
        echo html::form('sd', './', $body);
    }

    /**
     * 
     */
    static function generateJson($data, $xlsFile){
        self::loadFile($xlsFile);
        $fields = [];
        $records = [];
        foreach(self::$excel->getSheetIterator() as $sheet){
            foreach($sheet->getRowIterator() as $k => $row){
                if($k > 1){
                    foreach ($data as $key => $value) {
                        if(is_numeric($value) && array_key_exists($value, $row)){
                            $field_filter = explode('|', $key);
                            $field = $field_filter[0];
                            
                            ## image list
                            if($field == 'imagesBuffer_'){
                                $fields['imagesBuffer_'] = '';
                                for($loop = $value; $loop <= ($value + 49); $loop++){
                                    $fields[$field] .= $row[$loop]? $row[$loop] .'|':'';
                                }
                            }else{
                                ## fields and filters
                                if(isset($field_filter[1]) && $field_filter[1] != '')
                                    $fields[$field] = call_user_func_array(array(self::class, $field_filter[1]), array($row[$value]));
                                else
                                    $fields[$field] = $row[$value];
                            }

                        }
                    }
                    array_push($records, $fields);
                }
            }
        }
        return json_encode($records);
    }
}