<?php
final class Action
{
	protected $file;

	protected $class;

	protected $method;

	protected $args = array();

	public function __construct($route, $args = array())
	{
		$parts       = explode('/', $route);
		$this->file  = DIR_SITE . "/controller/{$parts[0]}/{$parts[1]}.php";
		$this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', "{$parts[0]}{$parts[1]}");

		if (!empty($args))
		{
			$this->args = $args;
		}
		$this->method = isset($parts[2]) ? $parts[2] : 'index';
	}

	public function getFile()
	{
		return $this->file;
	}

	public function getClass()
	{
		return $this->class;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getArgs()
	{
		return $this->args;
	}
}

?>