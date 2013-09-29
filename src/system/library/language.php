<?php
class Language
{
	private $default = 'english';

	public $directory;

	private $data = array();

	public function __construct($directory)
	{
		$this->directory = $directory;
	}

	public function get($key)
	{
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}

	public function load($filename)
	{
		$file = DIR_SITE . "/language/{$this->directory}/{$filename}.php";
		if (file_exists($file))
		{
			$_ = array();
			require($file);
			$this->data = array_merge($this->data, $_);

			return $this->data;
		}

		$file = DIR_SITE . "/language/{$this->default}/{$filename}.php";
		if (file_exists($file))
		{
			$_ = array();
			require($file);
			$this->data = array_merge($this->data, $_);

			return $this->data;
		}
	}
}

?>