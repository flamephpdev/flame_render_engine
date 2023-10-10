<?php

use Cache\Views\Flame\FlameRender;

require __DIR__ . '/../app.php';

// including the file from /views/demo.flame.php
FlameRender::view('demo', [
     'name' => 'FlameCore Developer',
     'needHelp' => true,
]);

// oh, you just want the path of the generated file? 
// no problem
$file_path = FlameRender::include('demo');