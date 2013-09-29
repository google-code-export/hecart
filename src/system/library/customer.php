<?php
class Customer extends modules_mem
{
	private $customer_id = 0;

	private $firstname = '';

	private $lastname = '';

	private $email = '';

	private $telephone = '';

	private $fax = '';

	private $newsletter = '';

	private $customer_group_id;

	private $address_id = 0;

	public function __construct($registry)
	{
		parent::__construct();
		$this->config  = $registry->get('config');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['customer_id']))
		{
			$ures = $this->mem_sql("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '{$this->session->data['customer_id']}' AND status = '1'");
			if (!empty($ures))
			{
				$this->customer_id       = $ures['customer_id'];
				$this->firstname         = $ures['firstname'];
				$this->lastname          = $ures['lastname'];
				$this->email             = $ures['email'];
				$this->telephone         = $ures['telephone'];
				$this->fax               = $ures['fax'];
				$this->newsletter        = $ures['newsletter'];
				$this->customer_group_id = $ures['customer_group_id'];
				$this->address_id        = $ures['address_id'];
			}
			else
			{
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false)
	{
		$email = $this->sdb()->escape(strtolower($email));
		if ($override)
		{
			$query = $this->sdb()->query_res("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '{$email}' AND status = '1'");
		}
		else
		{
			$query = $this->sdb()->query_res("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '{$email}' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->sdb()->escape($password) . "'))))) OR password = '" . $this->sdb()->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		}

		if (empty($query->num_rows))
		{
			return false;
		}

		/**
		 * login success
		 */
		$this->session->data['customer_id'] = $query->row['customer_id'];
		if ($query->row['cart'] && is_string($query->row['cart']))
		{
			if (!isset($this->session->data['cart']))
			{
				$this->session->data['cart'] = array();
			}

			$cart = unserialize($query->row['cart']);
			foreach ($cart as $key => $value)
			{
				if (!array_key_exists($key, $this->session->data['cart']))
				{
					$this->session->data['cart'][$key] = $value;
				}
				else
				{
					$this->session->data['cart'][$key] += $value;
				}
			}
		}

		if ($query->row['wishlist'] && is_string($query->row['wishlist']))
		{
			if (!isset($this->session->data['wishlist']))
			{
				$this->session->data['wishlist'] = array();
			}

			$wishlist = unserialize($query->row['wishlist']);
			foreach ($wishlist as $product_id)
			{
				if (!in_array($product_id, $this->session->data['wishlist']))
				{
					$this->session->data['wishlist'][] = $product_id;
				}
			}
		}

		$this->customer_id       = $query->row['customer_id'];
		$this->firstname         = $query->row['firstname'];
		$this->lastname          = $query->row['lastname'];
		$this->email             = $query->row['email'];
		$this->telephone         = $query->row['telephone'];
		$this->fax               = $query->row['fax'];
		$this->newsletter        = $query->row['newsletter'];
		$this->customer_group_id = $query->row['customer_group_id'];
		$this->address_id        = $query->row['address_id'];

		wcore_utils::set_cookie('cust_email', $this->email);
		wcore_utils::set_cookie('cust_id', $this->customer_id);

		/**
		 * 记录客户的IP地址
		 */
		$ip = wcore_utils::get_ip();
		$this->mdb()->query_res("UPDATE " . DB_PREFIX . "customer SET ip = '{$ip}' WHERE customer_id = '{$this->customer_id}'");
		$uip_res = $this->mem_sql("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '{$this->session->data['customer_id']}' AND ip = '{$ip}'");
		if (!empty($uip_res))
		{
			$this->mdb()->query_res("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '{$this->session->data['customer_id']}', ip = '{$ip}', date_added = NOW()");
		}

		return true;
	}

	public function logout()
	{
		/**
		 * 保存客户购物车中的所购买的产品与收藏产品
		 */
		$ip       = wcore_utils::get_ip();
		$cartlist = $this->mdb()->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '');
		$wishlist = $this->mdb()->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '');
		$this->mdb()->query_res("UPDATE " . DB_PREFIX . "customer SET cart = '{$cartlist}', wishlist = '{$wishlist}', ip = '{$ip}' WHERE customer_id = '{$this->customer_id}'");

		/**
		 * 清除登录信息
		 */
		$this->customer_id       = '';
		$this->firstname         = '';
		$this->lastname          = '';
		$this->email             = '';
		$this->telephone         = '';
		$this->fax               = '';
		$this->newsletter        = '';
		$this->customer_group_id = '';
		$this->address_id        = '';

		unset($this->session->data['customer_id']);
		wcore_utils::set_cookie('cust_id', null, -1);
		wcore_utils::set_cookie('cust_email', null, -1);
	}

	public function isLogged()
	{
		return $this->customer_id;
	}

	public function getId()
	{
		return $this->customer_id;
	}

	public function getFirstName()
	{
		return $this->firstname;
	}

	public function getLastName()
	{
		return $this->lastname;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getTelephone()
	{
		return $this->telephone;
	}

	public function getFax()
	{
		return $this->fax;
	}

	public function getNewsletter()
	{
		return $this->newsletter;
	}

	public function getCustomerGroupId()
	{
		return $this->customer_group_id;
	}

	public function getAddressId()
	{
		return $this->address_id;
	}

	public function getBalance()
	{
		$query = $this->sdb()->query_res("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '{$this->customer_id}'");

		return $query->row['total'];
	}

	public function getRewardPoints()
	{
		$query = $this->sdb()->query_res("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '{$this->customer_id}'");

		return $query->row['total'];
	}
}

?>