<?php

function my_autoloader($classname) {
    include 'classes/'.$classname.'.class.php';
}

spl_autoload_register('my_autoloader');

function debug_print($d) {
    echo "<pre>";
    print_r($d);
    echo "</pre>";
}

?>