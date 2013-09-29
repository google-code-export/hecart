<?php
class DB
{
	private $driver;

	public function __construct($driver, $hostname, $username, $password, $database)
	{
		if (file_exists(DIR_ROOT . "/system/database/{$driver}.php"))
		{
			require(DIR_ROOT . "/system/database/{$driver}.php");
		}
		else
		{
			exit('Error: Could not load database file ' . $driver . '!');
		}

		$this->driver = new $driver($hostname, $username, $password, $database);
	}

	public function query($sql)
	{
		return $this->driver->query($sql);
	}

	public function escape($value)
	{
		return $this->driver->escape($value);
	}

	public function countAffected()
	{
		return $this->driver->countAffected();
	}

	public function insert_id()
	{
		return $this->driver->insert_id();
	}
}

?>