# How to add to a project?

Just add it to your projects root directory, and
require the app.php like: 
```php
require_once __DIR__ . '/flame_renderer_engine/app.php';
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

FlameRender::include(
     'filename' // without the .flame.php extension!
);
```
This doesn't comes with props... This will be fixed in the future,
but just use the whole [FlameCore](https://github.com/flamephpdev/flamecore)
to use every feature of this plugin.

## Credits

[Martin Binder](https://mrtn.vip) FullStack Web Developer, with 4 years of experience with PHP, 3 with Laravel.
Currently learning GoLang ;)
