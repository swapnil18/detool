<?php

namespace common\components; 
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
 
class CustomComponent extends Component
{
 public function emptyDir($dir) {
    if (is_dir($dir)) {
        $scn = scandir($dir);
        foreach ($scn as $files) {
            if ($files !== '.') {
                if ($files !== '..') {
                    if (!is_dir($dir . '/' . $files)) {
                        @unlink($dir . '/' . $files);
                    } else {
                        emptyDir($dir . '/' . $files);
                        rmdir($dir . '/' . $files);
                    }
                }
            }
        }
    }
  }
 
}

?>
