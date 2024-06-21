<?php
// var_dump(__DIR__);
function autoload($class) {
    $classfile = $class.'.class.php';
    $dir = __DIR__ . '/Classes/';
    if(file_exists($dir . $classfile)) {
    // var_dump($dir . $classfile)
    require_once $dir . $classfile;   
    } 
}
spl_autoload_register('autoload');
