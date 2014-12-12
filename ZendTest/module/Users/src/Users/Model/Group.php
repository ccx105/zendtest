<?php

namespace Users\Model;

class Group
{
	public $id;
	public $name;
	
	
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }
	
	function exchangeArray($data)
	{
		$this->id = (isset($data['id'])) ? $data['id'] : null;	
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		
	}
}
