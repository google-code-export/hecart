<?php
class Log
{
	private $filename;

	public function __construct($filename)
	{
		$this->filename = $filename;
	}

	public function write($message)
	{
		$file   = DIR_ROOT . "/system/logs/{$this->filename}";
		$handle = fopen($file, 'a+');
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
		fclose($handle);
	}

	public function payment($message)
	{
		$file   = DIR_ROOT . "/system/logs/payment.txt";
		$handle = fopen($file, 'a+');
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
		fclose($handle);
	}
}

?>