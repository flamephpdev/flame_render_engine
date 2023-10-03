<?php

namespace Cache\Views\Flame;

use Core\Base\Base;
use Exception;

class FlameRender extends Base {

     public static $store_dir = FLAMEPHP_RENDER_ENGINE_ROOT . '/__fphp__cache__/views';
     private static $ez_tags = [ '{{', '}}', '*', '!', '--' ];
     public static $views_dir = FLAMEPHP_RENDER_ENGINE_ROOT . '/views';
     private static $view__autorender_file = '.view.{ext}';
     private static array $custom_replace = [];

     public static function boot():void {
          $config = array(
               // the folder where the dev views stored
               'view-folder' => FLAMEPHP_RENDER_ENGINE_ROOT . '/views',
           
               // ez tags for easier writing, usage: {{ asset('css/main.css') }} // returns the main css url
               'ez-tags' => array(
                   0 => '{{',
                   1 => '}}',
                   2 => '*', // use this to disable echo in php
                   3 => '!', // use this for auto htmlspecialchars function
                   4 => '--', // use this for comments
               ),
           
               'view-render-file-ext' => '.flame.{ext}', // file extension to render
           
               'replace-tags-to' => array(
                   '@else:' => 'else:',
                   '@CSRF' => 'echo \Core\App\Security\Csrf::tokenInput()',
                   '@dev' => 'if(MODE_DEV):',
                   '@enddev' => 'endif',
                   '@debugger' => 'view(".src/:helpers/debugger")',
                   '@svglogo' => 'echo "<svg version=\"1.1\" viewBox=\"0 0 32 32\" xml:space=\"preserve\" xmlns=\"http://www.w3.org/2000/svg\" enable-background=\"new 0 0 32 32\"><path d=\"M27 4H5C3.3 4 2 5.3 2 7v18c0 1.7 1.3 3 3 3h2.8c-.5-1-.8-2-.8-3.1-.1-2.7.7-5.3 2.5-7.5l.3-.4c.8-1 1.6-2 2-3 .2-.6.7-1.1 1.2-1.5 1.2-.8 2.9-.6 3.9.4 1.5 1.6 2.4 3.3 2.7 5 0 .2.1.4.1.6.5-.3 1.1-.5 1.7-.5 1.1 0 2.1.7 2.6 1.7.9 1.9 1.2 4.3.8 6.4-.1.6-.4 1.3-.6 1.8H27c1.7 0 3-1.3 3-3V7c0-1.7-1.3-3-3-3zM7.9 8.4c0 .1-.1.2-.2.3-.2.2-.4.3-.7.3s-.5-.1-.7-.3C6.1 8.5 6 8.3 6 8c0-.3.1-.5.3-.7l.1-.1c.1 0 .1-.1.2-.1.1-.1.1-.1.2-.1h.4c.1 0 .1 0 .2.1.1 0 .1.1.2.1l.1.1c.1.1.2.2.2.3.1.1.1.3.1.4 0 .1 0 .3-.1.4zm2.8.3c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.2-.2-.3-.4-.3-.7 0-.1 0-.3.1-.4.1-.1.1-.2.2-.3.1-.1.2-.2.3-.2.4-.2.8-.1 1.1.2.1.1.2.2.2.3.1.1.1.3.1.4 0 .3-.1.5-.3.7zm3.2-.3c-.1.1-.1.2-.2.3-.2.2-.4.3-.7.3-.1 0-.3 0-.4-.1-.1-.1-.2-.1-.3-.2-.1-.1-.2-.2-.2-.3-.1-.1-.1-.3-.1-.4 0-.1 0-.3.1-.4.1-.1.1-.2.2-.3.4-.4 1-.4 1.4 0 .1.1.2.2.2.3.1.1.1.3.1.4 0 .1 0 .3-.1.4z\" fill=\"#ff595E\" class=\"fill-000000\"></path><path d=\"M22.2 20.7c-.2-.3-.5-.5-.9-.6-.4 0-.7.2-.9.5l-.1.2c0 .1-.1.2-.1.2-.5.7-1.1 1.1-2.1 1.2H18v-.7c-.1-1.1-.1-2.1-.3-3.2-.3-1.4-1-2.7-2.2-4-.3-.3-.9-.4-1.3-.1-.2.1-.4.3-.5.5-.5 1.3-1.3 2.3-2.3 3.5l-.3.4c-1.5 1.9-2.2 4-2.1 6.2.1 3.3 3.3 6.2 6.9 6.2 3.3 0 6.2-2.2 6.9-5.3.4-1.6.2-3.5-.6-5z\" fill=\"#ffca3a\" class=\"fill-000000\"></path></svg>"',
               ),
           
          );
          if(isset($config['ez-tags'])) self::$ez_tags = $config['ez-tags'];
          if(isset($config['view-folder'])) self::$views_dir = $config['view-folder'];
          if(isset($config['replace-tags-to'])) self::$custom_replace = $config['replace-tags-to'];
          if(isset($config['view-render-file-ext'])) self::$view__autorender_file = $config['view-render-file-ext'];
     }

     public static function include(string $file){
          // Render start time
          $genTime = microtime(true);

          // Get the file extension and check if parser is enabled
          $filedata = FileData::get($file, self::$views_dir, self::$view__autorender_file, self::$store_dir);
          // is parser enabled
          $renderFile = $filedata['renderFile'];
          // the file path
          $view_file = $filedata['view_file'];
          // the cached file path
          $cached_file = $filedata['cached_file'];
          // the file extension
          $file_ext = $filedata['file_ext'];

          // is the parser is required to reParse the file or just parse if not exits curretly
          if(!file_exists($cached_file) || (MODE_DEV)) {
               // check if the view file is not exists
               if(!file_exists($view_file)){
                    $ex = new Exception();
                    $trace = $ex->getTrace();
                    $final_call = $trace[1];
                    throw new Exception('Trying to import a non-existing file (' . $file . ')');
               }

               // get the content of the file
               $view_data = file_get_contents($view_file);
               
               // create the cached file's storage path
               flamephp_createPath__(dirname($cached_file));

               $hash = NULL;

               $is_static = false;

               // is required to render the file
               if($renderFile) {
                    $st = '@static';
                    if(str_starts_with($view_data, $st)){
                         $is_static = true;
                         $view_data = substr($view_data, strlen($st));
                         $nl = "\n";
                         if(str_starts_with($view_data, $nl)) $view_data = substr($view_data, strlen($nl));
                    }
                    // while the file has an extend tag
                    while(str_starts_with($view_data, '@extends(')) {
                         $view_data = FlameExtend::extended($view_data, self::$views_dir, self::$view__autorender_file, self::$store_dir);
                    }

                    $hash = hash('md5', $view_data);
                    $checkHash = new FlameFileHash($hash);
                    if($checkHash->isValid()) return $checkHash->getFile();

                    $view_data_real = $view_data;

                    $ignore = new FlameIgnores(
                         '#flame-engine.ignore:start',
                         '#flame-engine.ignore:end',
                         '#flame-engine.ignore:next-line'
                    );
                    $view_data = $ignore->createAndIgnoreHTML($view_data);

                    $view_data = FlameParser::auto_tags($view_data, self::$custom_replace);

                    $view_data = FlameParser::inline_operators($view_data, self::$ez_tags);
               
                    // create a new flame operation parser
                    $fo = new FlameOperations;
                    // add the full source
                    $fo->addFullSource($view_data);
                    // configure
                    $fo->configureParser('@','(',')');
                    // parse
                    $fo->parseFile();
                    // get the parsed content string
                    $view_data = $fo->getParsed();

                    // now parse back the ignored content
                    $view_data = $ignore->getRealContent($view_data);

                    $view_data = file_get_contents(__DIR__ . '/view_header.template.php') . $view_data;

                    // add some information about the parsed file
                    $view_data .= "<?php\n/*\nGenerated at: " . date('Y-m-d H:i:s') .  "\nMD5 File Hash: " . md5($view_data_real) . "\nRender Time: " . microtime(true) - $genTime . "s\nFlame Engine ALPHA v0.1\n*/\n?>";
               }

               if($is_static) {
                    ob_start();
                    eval('?>' . $view_data);
                    $view_data = ob_get_contents();
                    ob_end_clean();
               }
               // save the file data
               file_put_contents($cached_file, $view_data);

               if($hash) FlameFileHash::addFile($hash, $cached_file);
          }

          return $cached_file;
     }

     public static function textParser(string $text, array $variable_pack = array(), bool $eval = false, bool $cache_evald_data = false) {
          $textHash = md5($text);
          flamephp_createPath__(cache('/intimeParser/'));
          $cfile = cache('/intimeParser' . startStrSlash($textHash . '.text-content.php'));
          $varPack = '';
          if(!empty($variable_pack)) {
               $varPack = "<?php\n";
               foreach($variable_pack as $var => $val) {
                    $varPack .= '$' . $var . '=' . var_export($val,true) . ';';
               }
               $varPack .= "\n?>";
          }

          if(file_exists($cfile)) {
               $data = require $cfile;
               $data = $varPack . $data;
               if($eval) $data = self::eval($varPack . $data, $cache_evald_data);
               return $data;
          }

          $ignore = new FlameIgnores(
               '#flame-engine.ignore:start',
               '#flame-engine.ignore:end',
               '#flame-engine.ignore:next-line'
          );
          $text = $ignore->createAndIgnoreHTML($text);

          $text = FlameParser::auto_tags($text, self::$custom_replace);

          $text = FlameParser::inline_operators($text, self::$ez_tags);
     
          // create a new flame operation parser
          $fo = new FlameOperations;
          // add the full source
          $fo->addFullSource($text);
          // configure
          $fo->configureParser('@','(',')');
          // parse
          $fo->parseFile();
          // get the parsed content string
          $text = $fo->getParsed();

          // now parse back the ignored content
          $text = $ignore->getRealContent($text);

          file_put_contents($cfile, "<?php\nreturn " . var_export($text, true) . ";");

          $data = $varPack . $text;
          if($eval) $data = self::eval($varPack . $data, $cache_evald_data);
          return $data;
     }

     public static function eval(string $data, bool $cache_evald_data = false) {
          if($cache_evald_data) {
               $cfile = cache('/intimeParser' . startStrSlash(md5($data) . '.text-data-eval.php'));
               if(file_exists($cfile)) return require $cfile;
          }
          ob_start();
          eval('?>' . $data);
          $content = ob_get_contents();
          ob_end_clean();
          if($cache_evald_data) file_put_contents($cfile, "<?php\nreturn " . var_export($content, true) . ";");
          return $content;
     }

}