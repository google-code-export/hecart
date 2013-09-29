<?php
class Captcha
{
	protected $code;

	protected $width = 28;

	protected $height = 90;

	private $_vif = null;

	function __construct()
	{
		$this->_vif = new wcore_verify();

		$this->code = $this->_vif->generate_words();
	}

	function getCode()
	{
		return $this->code;
	}

	function showImage()
	{
		$this->_vif->font_size = 20;
		$this->_vif->bgcolor   = '#FFFFFF';
		$this->_vif->draw(90, 33);
		exit;

		$image  = imagecreatetruecolor($this->height, $this->width);
		$width  = imagesx($image);
		$height = imagesy($image);

		$black = imagecolorallocate($image, 0, 0, 0);
		$white = imagecolorallocate($image, 255, 255, 255);
		$red   = imagecolorallocatealpha($image, 255, 0, 0, 75);
		$green = imagecolorallocatealpha($image, 0, 255, 0, 75);
		$blue  = imagecolorallocatealpha($image, 0, 0, 255, 75);

		imagefilledrectangle($image, 0, 0, $width, $height, $white);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $red);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $green);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $blue);

		imagefilledrectangle($image, 0, 0, $width, 0, $black);
		imagefilledrectangle($image, $width - 1, 0, $width - 1, $height - 1, $black);
		imagefilledrectangle($image, 0, 0, 0, $height - 1, $black);
		imagefilledrectangle($image, 0, $height - 1, $width, $height - 1, $black);
		imagestring($image, 10, intval(($width - (strlen($this->code) * 9)) / 2), intval(($height - 15) / 2), $this->code, $black);

		header('Content-type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
	}
}

?>