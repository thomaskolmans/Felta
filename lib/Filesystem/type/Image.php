<?php
namespace lib\Filesystem\type;

use lib\Filesystem\File;

class Image extends File{

    public $image;

    private $file;

    public function __construct($file){
        $this->file = new File($file);
    }
    public function load($image){
        $this->image = $image;
        $file = new File($this->image);
        if(!$file->is("image")){
            return false;
        }
    }
    public static function resize($path,$x1,$y1,$w,$h,$x2,$y2){
        $x = getimagesize($path);            
        switch ($x['mime']) {
            case "image/gif":
                $img = imagecreatefromgif($path);
            break;
            case "image/jpeg":
                $img = imagecreatefromjpeg($path);
            break;
            case "image/png":
                $img = imagecreatefrompng($path);
            break;
       }
        list($width, $height) = getimagesize($path);
        $wc = ($width / $w) * $w;
        $hc = ($height /$h) * $h;

        $xc = ($width / $w) * $x1;
        $yc = ($height /$h) * $y1;

        $img_base = imagecreatetruecolor($w,$h);


        imagecopyresampled($img_base, $img,0,0,$x1,$y1, $wc,$hc, $width, $height);

        $path_info = pathinfo($path);    
        switch ($path_info['extension']) {
            case "gif":
                imagegif($img_base, $path);  
            break;
            case "jpeg":
                imagejpeg($img_base, $path);  
                break;
            case "png":
                imagepng($img_base, $path);  
                break;
            case "jpg":
                imagejpeg($img_base,$path);
                break;
        }
       imagedestroy($img_base);
    }

    public static function saveBlob($blob){
        $uid = \lib\Helpers\UUID::generate(20);
        $file = new File($uid.".png");
        file_put_contents($file->getDefaultDir().$uid.".png", $blob);
        return $uid;
    }
}

?>
