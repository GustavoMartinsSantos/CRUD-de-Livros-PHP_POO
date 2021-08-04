<?php
    spl_autoload_register('autoloadClasses');

    function autoloadClasses ($class) {
        $path = "../Classes/";
        $extension = ".class.php";
        $fullPath = $path . $class . $extension;

        if(!file_exists($fullPath)) {
            $path = "Classes/";
            $fullPath = $path . $class . $extension;

            if(!file_exists($fullPath)) {
                return false;
            }
        }

        require_once $fullPath;
    }
?>