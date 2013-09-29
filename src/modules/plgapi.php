<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: modules/plgapi.php
 * 简述: 插件订单处理接口
 * 作者: woods·zhang  ->  hoojar@163.com
 * 版本: $Id: plgapi.php 1 2012-11-20 05:55:12Z Administrator $
 * 版权: Copyright 2006-2013 慧佳工作室拥有此系统所有版权等知识产权
 *
 */
class modules_plgapi extends modules_mem
{
	/**
	 * 接口构造函数
	 *
	 * @param string $plg_api_name  接口名称
	 * @param array  $plg_api_data  接口数据
	 * @param array  $order_info    订单数据
	 */
	public function __construct($plg_api_name, $plg_api_data, $order_info)
	{
		$calFunc      = "_{$plg_api_name}";
		$plg_api_data = json_decode(stripslashes($plg_api_data), true);
		if (method_exists($this, $calFunc))
		{
			$this->$calFunc($plg_api_data, $order_info);
		}
	}

	/**
	 * 返还网订单数据存储
	 *
	 * @param array $plg_api_data  接口数据
	 * @param array $order_info    订单数据
	 */
	private function _fanhuan($plg_api_data, $order_info)
	{
		$data = array(
			'OrderCode'    => $order_info['OrderCode'],
			'ChannelID'    => $plg_api_data['ChannelID'],
			'UserID'       => $plg_api_data['UserID'],
			'UserName'     => $plg_api_data['UserName'],
			'TrackingCode' => $plg_api_data['tkc'],
			'CreateDate'   => $order_info['CreateDate'],
		);
		wcore_object::mdb()->insert('web_fanhuan_order', $data);
	}
}

?>