<?php
final class Registry
{
	private $data = array();

	public function get($key)
	{
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function has($key)
	{
		return isset($this->data[$key]);
	}

	/**
	 * CDN图片域名
	 *
	 * @param string $img  图片地址
	 * @param string $path 附加路径
	 * @return string 完整URL图片地址
	 */
	function cdn_img_url($img, $path = '/')
	{
		if (empty($img) || strpos($img, '://') !== false)
		{
			return $img;
		}
		if ($img[0] == '/')
		{
			$path = '';
		}

		/**
		 * 当使用本机时，就不用CDN处理了
		 */
		if (isset($_GET['local']))
		{
			return "{$path}{$img}";
		}
		if (USE_ISLOCAL_JS2CSS || USE_ISLOCAL_IMG)
		{
			$file_ext = strtolower(strrchr($img, '.'));
			if (USE_ISLOCAL_JS2CSS && ($file_ext == '.js' || $file_ext == '.css'))
			{
				return "{$path}{$img}";
			}

			if (USE_ISLOCAL_IMG && ($file_ext == '.jpg' || $file_ext == '.jpeg' || $file_ext == '.gif' || $file_ext == '.swf' || $file_ext == '.png'))
			{
				return "{$path}{$img}";
			}
		}

		/**
		 * 分服务器加载
		 */
		static $img_hosts = null;
		static $img_count = null;
		if (is_null($img_hosts))
		{
			$img_hosts = json_decode(IMG_URLS, true);
			$img_count = count($img_hosts);
		}

		$key = abs(crc32($img)) % $img_count;

		return "{$img_hosts[$key]}{$path}{$img}";
	}
}

?>