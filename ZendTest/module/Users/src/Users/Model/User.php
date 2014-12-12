<?php

namespace Users\Model;

class User
{
	public $id;
	public $company_id;
	public $group_id;
	public $first_name;
	public $last_name;
	public $email;
	public $password;
	
	
	
	public function setPassword($clearPassword){
		$this->password = sha1($clearPassword);
	}
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }
	
	function exchangeArray($data)
	{
		$this->id = (isset($data['id'])) ? $data['id'] : null;	
		$this->company_id = (isset($data['company_id'])) ? $data['company_id'] : null;
		$this->group_id = (isset($data['group_id'])) ? $data['group_id'] : null;
		$this->first_name = (isset($data['first_name'])) ? $data['first_name'] : null;
		$this->last_name = (isset($data['last_name'])) ? $data['last_name'] : null;
		$this->email = (isset($data['email'])) ? $data['email'] : null;
		if (isset($data["password"]))
		{
			$this->setPassword($data["password"]);
		}
	}
}
