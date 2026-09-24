<?php

namespace Models;

use DateTimeImmutable;
use Exception;
use PDO;

class User extends Database
{
	private $id;
	private $last_name;
	private $first_name;
	private $email;
	private $registration_date;
	private $password;

	
	public function getId()
	{
		return $this->id;
	}



	public function getLast_name()
	{
		return $this->last_name;
	}
	public function setLast_name($value)
	{
		if (empty($value)) throw new Exception('Last name is required');
		$this->last_name = $value;
	}
	public function getFirst_name()
	{
		return $this->first_name;
	}

	public function setFirst_name($value)
	{
		if (empty($value)) throw new Exception('First name is required');
		$this->first_name = $value;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($value)
	{
		if (empty($value)) throw new Exception('Email is required');

		$this->email = $value;
	}

	public function setPassword($value)
	{
		if (empty($value)) throw new Exception('Password is required');


		$this->password = password_hash($value, PASSWORD_DEFAULT);
	}

	public function getPassword()
	{
		return $this->password;
	}
	public function getRegistration_date()
	{
		return $this->registration_date;
	}

	public function setRegistration_date()
	{
		$this->registration_date = new DateTimeImmutable();
	}

	public function register()
	{
		$queryExecute = $this->db->prepare("INSERT INTO `users`(`first_name`,`last_name`, `email`, `password`, `registration_date`) 
			VALUES (:first_name,:last_name,:registration_date, :email, :password)");

		$queryExecute->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
		$queryExecute->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
		$queryExecute->bindValue(':email', $this->email, PDO::PARAM_STR);
		$queryExecute->bindValue(':registration_date', $this->registration_date, PDO::PARAM_STR);
		$queryExecute->bindValue(':password', $this->password, PDO::PARAM_STR);

		return $queryExecute->execute();
	}
}
