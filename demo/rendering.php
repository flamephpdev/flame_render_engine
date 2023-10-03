<?php

use Cache\Views\Flame\FlameRender;

require __DIR__ . '/../app.php';

echo FlameRender::textParser('
     <h1>Thanks for using {{ $myTool }}
', [
     'myTool' => 'Flame Engine'
], true);

echo "\n";
if(php_sapi_name() !== 'cli') echo "<br/>";

echo FlameRender::textParser('
     <h1>This is how a non-rendered code looks like {{ $test }}
', [
     'test' => 'Something...'
]);