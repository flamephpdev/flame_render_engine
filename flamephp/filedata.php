<?php

namespace Cache\Views\Flame;

class FileData {

    public static function get($file, $views_dir, $view__autorender_file, $store_dir) {
        $renderFile = false;
        $v_p = $views_dir;
        $atp = '';
        
        if(str_starts_with($file,'.src/:')) {
            $file = str_replace('.src/:', '', $file);
            $v_p = FLAMEPHP_RENDER_ENGINE_ROOT . ('/template/views');
            $data = require FLAMEPHP_RENDER_ENGINE_ROOT . ('/applock.token.php');
            $atp = flamephp_startStrSlash($data['framework_builtin_views_directory']);
        }
        $file_ext = 'php';
        if(str_contains($file, '.')) {
            $ext = explode('.', $file);
            $file_ext = $ext[array_key_last($ext)];
            unset($ext[array_key_last($ext)]);
            $file = implode('.', $ext);
        }
        $varf = str_replace('{ext}', $file_ext, $view__autorender_file);
        if(file_exists($v_p . flamephp_startStrSlash($file) . $varf)) {
            $view_file = $v_p . flamephp_startStrSlash($file) . $varf;
            $cached_file = $store_dir . $atp . flamephp_startStrSlash($file) . $varf;
            $renderFile = true;
            
        } else {
            $view_file = $v_p . flamephp_startStrSlash($file) . '.' . $file_ext;
            $cached_file = $store_dir . $atp . flamephp_startStrSlash($file) . '.' . $file_ext;
        }
        if($file_ext !== 'php' && $renderFile) {
            $cached_file .= '.php';
        }
        return [
            'renderFile' => $renderFile,
            'view_file' => $view_file,
            'cached_file' => $cached_file,
            'file_ext' => $file_ext,
        ];
    }

}