<?php
class User extends modules_mem
{
	private $user_id;

	private $username;

	private $permission = array();

	public function __construct($registry)
	{
		parent::__construct();
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id']))
		{
			$this->permission = $this->_get_permission($this->session->data['user_group_id']);
			if (empty($this->permission))
			{
				$this->logout();
			}

			$this->user_id  = $this->session->data['user_id'];
			$this->username = $this->session->data['username'];
		}
	}

	public function login($username, $password)
	{
		$user_query = $this->sdb()->query_res("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->mdb()->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->mdb()->escape($password) . "'))))) OR password = '" . $this->mdb()->escape(md5($password)) . "') AND status = '1'");
		if ($user_query->num_rows)
		{
			$this->user_id                        = $user_query->row['user_id'];
			$this->username                       = $user_query->row['username'];
			$this->session->data['user_id']       = $user_query->row['user_id'];
			$this->session->data['username']      = $user_query->row['username'];
			$this->session->data['user_group_id'] = $user_query->row['user_group_id'];

			$this->permission = $this->_get_permission($this->session->data['user_group_id']);
			$this->mdb()->query_res("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->mdb()->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '{$this->session->data['user_id']}'");

			return true;
		}

		return false;
	}

	private function _get_permission($user_group_id)
	{
		$mkey             = "USER-GROUP{$user_group_id}-PERMISSION";
		$group_permission = $this->mem_get($mkey);
		if (empty($group_permission))
		{
			$user_group_query = $this->sdb()->query_res("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '{$user_group_id}'");
			$permissions      = unserialize($user_group_query->row['permission']);
			if (is_array($permissions))
			{
				foreach ($permissions as $key => $value)
				{
					$group_permission[$key] = array_flip($value);
				}
			}
			$this->mem_set($mkey, $group_permission);
		}

		return $group_permission;
	}

	public function logout()
	{
		$this->user_id  = '';
		$this->username = '';
		unset($this->session->data['user_id']);
		session_destroy();
	}

	public function hasPermission($key, $value)
	{
		return isset($this->permission[$key][$value]) ? true : false;
	}

	public function isLogged()
	{
		return $this->user_id;
	}

	public function getId()
	{
		return $this->user_id;
	}

	public function getUserName()
	{
		return $this->username;
	}
}

?>