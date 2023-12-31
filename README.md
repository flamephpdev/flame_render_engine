# How to add to a project?

Just add it to your projects root directory, and
require the app.php like: 
```php
require_once __DIR__ . '/flame_render_engine/app.php';
```

And configure the `config.php` file

```php
return array(
     'plugin_root' => dirname(__DIR__), // this is where to plugin will put all the cached files and etc
     'views_path' => __DIR__ . '/views', // this is where you have to put your view files
     'developer_mode' => true // developer mode 🤷🏼‍♂️ just turn off in production
);
```

# How to use it?

You can use it with `*.flame.php` files with a directory named views in your root folder, or the `textParser` option that doesn't requires any file. (Except the cache files that auto generated by this tool).

## Code examples

### With the textParser option

```php
use Cache\Views\Flame\FlameRender;

FlameRender::textParser('
     <h1>Hello {{ $world }}</h1>
', ['world' => 'Developer'], 
     true // this means that the output is evald, so not a non-executed php will be returned
);
```

### With the file option

```php
use Cache\Views\Flame\FlameRender;

FlameRender::view(
     'filename', // without the .flame.php extension!
     ['name' => 'John'] // add props to it
);
```

Actually you can get the path of the file's cache with the `include` method, like this:
```php
use Cache\Views\Flame\FlameRender;

$parsed_file = FlameRender::include(
     'filename', // without the .flame.php extension!
     // No props here
);

echo $parsed_file; // output: C:\...\your_project\flame_render_engine\__fphp__cache__\views\filename.flame.php
```

### Demo

Yes, check the `/demo` folder and run the files with php to see the output of each.

```
php ./demo/file.php
```

## Credits

[Martin Binder](https://mrtn.vip), FullStack Web Developer, 4 years of experience with PHP, 3 with Laravel.
Currently I am learning GoLang ;)