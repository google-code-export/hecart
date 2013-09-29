<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/fso.php
 * 简述: 专门用于提供各种操作文件系统的函数
 * 作者: woods·zhang  ->  hoojar@163.com
 * 版本: $Id: fso.php 1 2012-11-20 05:55:12Z Administrator $
 * 版权: Copyright 2006-2013 慧佳工作室拥有此系统所有版权等知识产权
 *
 */
class wcore_fso
{
	/**
	 * 加载小模板文件
	 * @param array  $data 要处理的数组值
	 * @param string $file 要加载的小模板文件
	 * @return string
	 */
	public static function file_tpl($data, $file) //加载文件小模板
	{
		if (!is_array($data) || !$file)
		{
			return '';
		}
		if (is_array($data))
		{
			extract($data, EXTR_PREFIX_SAME, 'woods'); //以数组键名为变量名
		}

		//获取文件的内容
		ob_start();
		$result = @include($file);
		if (!$result)
		{
			exit("include: {$file} without exist, Error Line: " . __LINE__);
		}
		$content = ob_get_contents();
		ob_end_clean();

		//分析并组合内容
		$content = addslashes($content);
		@eval("\$content = \"{$content}\";");

		return stripcslashes($content);
	}

	/**
	 * 获取加载文件的内容
	 *
	 * @param string $filename 文件名
	 * @return string 内容
	 */
	function &get_include_contents($filename)
	{
		$content = '';
		if (!is_file($filename))
		{
			return $content;
		}
		ob_start();
		require($filename);
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * 自动创建目录,可递归创建
	 *
	 * @param    string $path 要创建的目录地址
	 * @return    boolean    创建成功返回true失败为false
	 */
	public static function make_dir($path)
	{
		if (empty($path))
		{
			return false;
		}
		if (!file_exists($path))
		{
			wcore_fso::make_dir(dirname($path));
			@mkdir($path, 0777);
		}

		return true;
	}

	/**
	 * 递归删除文件夹
	 *
	 * @param    string $path 要删除的文件夹路径
	 * @return    boolean    删除成功为true失败为false
	 */
	public static function rm_dir($path)
	{
		if (empty($path))
		{
			return false;
		}
		if ($objs = glob("{$path}/*"))
		{
			foreach ($objs as $obj)
			{
				is_dir($obj) ? wcore_fso::rm_dir($obj) : unlink($obj);
			}
		}

		return rmdir($path);
	}

	/**
	 * 取二进制文件头快速判断文件类型
	 *
	 * @param string $file 文件路径
	 * @return string 文件类型
	 */
	public static function get_file_type($file)
	{
		$fp  = fopen($file, 'rb');
		$bin = fread($fp, 2); //只读2字节
		fclose($fp);
		$str_info  = @unpack('C2chars', $bin);
		$type_code = intval($str_info['chars1'] . $str_info['chars2']);
		switch ($type_code)
		{
			case 7790:
				$file_type = 'exe';
				break;
			case 7784:
				$file_type = 'midi';
				break;
			case 8075:
				$file_type = 'zip';
				break;
			case 8297:
				$file_type = 'rar';
				break;
			case 255216:
				$file_type = 'jpg';
				break;
			case 7173:
				$file_type = 'gif';
				break;
			case 6677:
				$file_type = 'bmp';
				break;
			case 13780:
				$file_type = 'png';
				break;
			default:
				$file_type = 'unknown';
				break;
		}

		return $file_type;
	}
}
?>