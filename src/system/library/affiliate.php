<?php
class Affiliate extends modules_mem
{
	private $affiliate_id;

	private $firstname;

	private $lastname;

	private $email;

	private $telephone;

	private $fax;

	private $code;

	public function __construct($registry)
	{
		$this->config  = $registry->get('config');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['affiliate_id']))
		{
			$affiliate_query = $this->sdb()->query_res("SELECT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '{$this->session->data['affiliate_id']}' AND status = '1'");

			if ($affiliate_query->num_rows)
			{
				$this->affiliate_id = $affiliate_query->row['affiliate_id'];
				$this->firstname    = $affiliate_query->row['firstname'];
				$this->lastname     = $affiliate_query->row['lastname'];
				$this->email        = $affiliate_query->row['email'];
				$this->telephone    = $affiliate_query->row['telephone'];
				$this->fax          = $affiliate_query->row['fax'];
				$this->code         = $affiliate_query->row['code'];
				$this->mdb()->query_res("UPDATE " . DB_PREFIX . "affiliate SET ip = '" . $this->mdb()->escape($this->request->server['REMOTE_ADDR']) . "' WHERE affiliate_id = '{$this->session->data['affiliate_id']}'");
			}
			else
			{
				$this->logout();
			}
		}
	}

	public function login($email, $password)
	{
		$affiliate_query = $this->sdb()->query_res("SELECT * FROM " . DB_PREFIX . "affiliate WHERE email = '" . $this->sdb()->escape($email) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->sdb()->escape($password) . "'))))) OR password = '" . $this->sdb()->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		if ($affiliate_query->num_rows)
		{
			$this->session->data['affiliate_id'] = $affiliate_query->row['affiliate_id'];

			$this->affiliate_id = $affiliate_query->row['affiliate_id'];
			$this->firstname    = $affiliate_query->row['firstname'];
			$this->lastname     = $affiliate_query->row['lastname'];
			$this->email        = $affiliate_query->row['email'];
			$this->telephone    = $affiliate_query->row['telephone'];
			$this->fax          = $affiliate_query->row['fax'];
			$this->code         = $affiliate_query->row['code'];

			return true;
		}

		return false;
	}

	public function logout()
	{
		unset($this->session->data['affiliate_id']);
		$this->affiliate_id = '';
		$this->firstname    = '';
		$this->lastname     = '';
		$this->email        = '';
		$this->telephone    = '';
		$this->fax          = '';
	}

	public function isLogged()
	{
		return $this->affiliate_id;
	}

	public function getId()
	{
		return $this->affiliate_id;
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

	public function getCode()
	{
		return $this->code;
	}
}

?>