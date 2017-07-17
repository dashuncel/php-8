<?php
class Gd 
{
	private $width;
	private $height;
	private $text;
	private $color;

	public function __construct($width, $height, $text) {
		$this->width  = $width;
		$this->height = $height;
		$this->text   = $text;
	}

	public function generate() {
        // изменение размеров картинки под заданные размеры в объекте: 
        $destImg   = imagecreatetruecolor($this->width, $this->height);
        $srcImg    = imageCreateFromJpeg(__DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."ramka.jpg");
        $srcWidth  = ImageSX($srcImg);
        $srcHeight = ImageSY($srcImg);
        $res=ImageCopyResampled($destImg, $srcImg, 0, 0, 0, 0, $this->width, $this->height, $srcWidth, $srcHeight);

        $font=__DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."albionic.ttf";
        $textColor=imagecolorallocate($destImg, 123, 104, 238);

        // вычисляем размер щрифта:
        // позиционирование текста в рамке:  
        $txt=explode("\n", $this->text);
        $px = ($this->width - 8*strlen($txt[0])) / strlen($txt[0]); // размер шрифта
        $pos_y = ($this->height - ($px + 10) * count($txt)) / 2;      // позиция по вертикали

        foreach ($txt as $piece) {
          $pos_x = ($this->width - ($px * mb_strlen($piece))) / 2 + 2*$px; // позиция по горизонтали
          imagettftext($destImg, $px, 0, $pos_x, $pos_y, $textColor, $font, $piece);
          $pos_y += $px + 10;
        } 
/*
         //--гербовая печать:
        $stamp = imagecreatetruecolor($this->width / 10 , $this->width / 10);
        $stampSrc = imageCreateFromPng(__DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."stamp.png");
        $res=ImageCopyResampled($stamp, $stampSrc, 0, 0, 0, 0, $this->width / 10 , $this->width / 10, ImageSX($stampSrc), ImageSX($stampSrc));

        $pos_x = $this->width - 2 * imageSX($stamp); // позиция по горизонтали
        $pos_y = $this->height - 2 * imageSY($stamp); // позиция по горизонтали

        imagecopy($destImg, $stamp, $pos_x, $pos_y, 0,0, imagesx($stamp), ImageSY($stamp));
*/
        header('Content-type: image/png');
        imagePng($destImg);
        imagedestroy($destImg);
	}
}
?>