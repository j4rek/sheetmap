<?php
namespace j4rek\sheetmap;
/** Include PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/../../../phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
use \Box\Spout\Reader\ReaderFactory;
use \Box\Spout\Common\Type;
use PHPExcel_IOFactory;
use \j4rek\sheetmap\helpers\html;
use \j4rek\sheetmap\helpers\helpers;

class sheetmap{
    use helpers;

    static $excel;

    /**
     * 
     */
    static function loadFile($xlsFile){
        libxml_disable_entity_loader(false);
        try{
            self::$excel = ReaderFactory::create(Type::XLSX);
            self::$excel->open($xlsFile);
            throw new \Exception();
        }catch(\Exception $ex){
            /** rebuild excelfile */
            $w = PHPExcel_IOFactory::createWriter(PHPExcel_IOFactory::load($xlsFile), "Excel2007");
            $info = pathinfo($xlsFile);
            $newFileName = str_replace($info['extension'], 'xlsx', $xlsFile);
            $w->save($newFileName);
            
            self::$excel = ReaderFactory::create(Type::XLSX);
            self::$excel->open($newFileName);
        }
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
                    $body .= html::div($header[0] . html::select($header[0] . (isset($header[1]) && $header[1] != 'filtro'? '|' . $header[1]: '') , $row));
                    break;
                }
            }
        }
        echo html::form('sd', './', $body);
    }

    /**
     * 
     */
    static function extractData($data, $xlsFile){
        self::loadFile($xlsFile);
        $fields = [];
        $records = [];
        foreach(self::$excel->getSheetIterator() as $sheet){
            foreach($sheet->getRowIterator() as $k => $row){
                if($k > 1){
                    foreach ($data as $obj) {
                        if(is_numeric($obj->col) && array_key_exists($obj->col, $row)){
                            ## image list
                            if($obj->field == 'imagesBuffer_'){
                                $fields['imagesBuffer_'] = '';
                                for($loop = $obj->col; $loop <= ($obj->col + 49); $loop++){
                                    $fields[$obj->field] .= $row[$loop]? trim($row[$loop]) .'|':'';
                                }
                            }else{
                                ## fields and filters
                                if(isset($obj->filter))
                                    $fields[$obj->field] = call_user_func_array(array(self::class, $obj->filter), array(trim($row[$obj->col])));
                                else
                                    $fields[$obj->field] = trim($row[$obj->col]);
                            }

                        }
                    }
                    array_push($records, $fields);
                }
            }
        }
        return $records;
    }

    /**
     * 
     */
    static function generateJson($data){
        $array = [];
        foreach($data as $record => $value){
            $field = explode('|', $record);
            if($value != 'none'){
                array_push($array, ['field'=> $field[0], 'col' => ($value + 1), 'filter' => (isset($field[1])?$field[1]:null) ] );
            //echo "<div>" . $field[0] . " = " . (isset($field[1])? $field[1] . "(\$reader->sheets[0]['cells'][\$i][" . ($value + 1) . "])" : "\$reader->sheets[0]['cells'][\$i][". ($value + 1) . "]") . ";</div>";
            
            }
        }
        echo json_encode($array);
        echo "<div><a href='./proceso.php'>procesar</a></div>";
        exit();
    }
}