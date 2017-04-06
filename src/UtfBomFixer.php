<?php

namespace Hongliang\UtfBomFixer;

class UtfBomFixer
{
    private $dir;
    private $fileExtension;
    private $bom = null;

    public function __construct($dir, $fileExtension = null)
    {
        $this->dir = rtrim($dir, '/');
        $this->fileExtension = $fileExtension;
    }

    public function fix()
    {
        $files = $this->scanFiles($this->dir);
        foreach ($files as $file) {
            $this->fixFile($file);
        }
    }

    public function fixFile($file)
    {
        $res = array('success' => -1, 'message' => '');
        if (file_exists($file)) {
            $content = file_get_contents($file);
            if (0 === strncmp($content, $this->getBom(), 3)) {
                file_put_contents($file, substr($content, 3));
                $res['success'] = 1;
                $res['message'] = 'BOM detected and fixed -----> '.$file;
            } else {
                $res['success'] = 0;
                $res['message'] = 'BOM not detected, nothing to fix -----> '.$file;
            }
        } else {
            $res['message'] = 'File not exist: '.$file;
        }

        return $res;
    }

    public function scanFiles()
    {
        $files = array();
        if (is_file($this->dir)) {
            return [$this->dir];
        }
        $this->getAllFiles($this->dir, $files);

        return $files;
    }

    private function getBom()
    {
        if (null === $this->bom) {
            $this->bom = pack('CCC', 0xef, 0xbb, 0xbf);
        }

        return $this->bom;
    }

    private function getAllFiles($path, &$files)
    {
        if (is_dir($path)) {
            $dp = dir($path);
            while ($file = $dp->read()) {
                if ($file != '.' && $file != '..') {
                    $this->getAllFiles($path.'/'.$file, $files);
                }
            }
            $dp->close();
        } elseif (is_file($path)) {
            if (!$this->fileExtension || pathinfo($path, PATHINFO_EXTENSION) == $this->fileExtension) {
                $files[] = $path;
            }
        }
    }
}
