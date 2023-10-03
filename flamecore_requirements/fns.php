<?php
// Fake view function
use Cache\Views\Flame\FlameRender;

function flamephp_view__(string $file, array $data = [], ?int $code = 200){
     if(str_ends_with($file, '/') || str_ends_with($file, '\\')) $file .= 'index';
 
     $GLOBALS['views_data__info'][] = [
         'file' => $file,
         'data' => $data,
         'code' => $code,
     ];
     if(!empty($data)){
         foreach($data as $var => $vardata){
             if(is_string($var)){
                 ${$var} = $vardata;
                 $_bag[$var] = $vardata;
             } else {
                 $_bag[] = $vardata;
             }
         }
     }
     include FlameRender::include($file);
}

function flamephp_createPath__($path) {
    if(is_dir($path)) return true;
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
    $return = flamephp_createPath__($prev_path);
    return ($return && is_writable($prev_path)) ? mkdir($path) : false;
}

function cache($cache) {
    return FLAMEPHP_RENDER_ENGINE_ROOT . '/__fphp__cache__' . startStrSlash($cache);
}

function startStrSlash($str) {
    if(!str_starts_with($str, '/')) return '/' . $str;
    return $str;
}

//get string between
function string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}