<?php
function QueryBuilder_autoloader($class){
    $class_name = str_replace('\\', '/', str_replace('DevCoder\\','',$class));
    require $class_name.'.php';
}

spl_autoload_register('QueryBuilder_autoloader');

?>