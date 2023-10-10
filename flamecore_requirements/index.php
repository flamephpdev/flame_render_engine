<?php

require_once __DIR__ . '/base.php';

// Load the package
foreach([
    'app',
    'extend',
    'filedata',
    //'flame_file',
    'flame_view',
    'hash',
    'ignore',
    'operations',
    'parser'
] as $file) require_once __DIR__ . '/../flamephp/' . $file . '.php';

// require the fake functions
require_once __DIR__ . '/fns.php';

foreach(get_declared_classes() as $class){
    // create a reflection to that class
    $reflected = new \ReflectionClass( $class );
    // and check if it's a framework base class
    if( $reflected->isSubclassOf( 'Core\Base\Base' ) && !$reflected->isAbstract() ){
        // create a class instance without constructor
        $instance = $reflected->newInstanceWithoutConstructor();
        if(method_exists($instance,'classBooter')) {
            // call it without arguments and boot it
            call_user_func(array($instance, 'classBooter'),$instance);
        }
    }
}

