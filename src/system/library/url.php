<?php
class Url
{
	private $url;

	private $ssl;

	private $rewrite;

	public function __construct($url, $ssl = '', $rewrite = true)
	{
		$this->url     = $url;
		$this->ssl     = $ssl;
		$this->rewrite = $rewrite;
	}

	public function link($route, $args = '', $connection = 'NONSSL')
	{
		$durl = ($connection == 'NONSSL') ? $this->url : $this->ssl;

		if (!$this->rewrite)
		{
			$url = "{$durl}index.php?route={$route}";
			if (!empty($args))
			{
				$url .= "&{$args}";
			}
		}
		else
		{
			$url = "{$durl}{$route}";
			if (!empty($args))
			{
				$url .= "?{$args}";
			}
		}

		return $url;
	}
}

?>