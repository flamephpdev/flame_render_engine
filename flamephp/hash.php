<?php

namespace Cache\Views\Flame;

use Core\Base\Base;

class FlameFileHash extends Base {

     private string $hash;
     private static array $hashList = [];

     public static function boot(): void {
          flamephp_createPath__(flamephp_cache('/flame-engine'));
          if(FLAMEPHP_MODE_DEV) {
               if(file_exists($file = flamephp_cache('/flame-engine/hash.php'))) {
                    self::$hashList = require $file;
               }
          }
     }

     public function __construct($hash) {
          $this->hash = $hash;
     }

     public function isValid() {
          return in_array($this->hash, array_keys(self::$hashList));
     }

     public function getFile() {
          return self::$hashList[$this->hash];
     }

     public static function addFile($hash, $cache_path) {
          self::$hashList[$hash] = $cache_path;
          file_put_contents(flamephp_cache('/flame-engine/hash.php'), "<?php\nreturn " . var_export(self::$hashList, true) . ";");
     }

}