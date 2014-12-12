<?php

namespace Users\Model;
use Zend\Form\Form;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class DatabaseSearch 
{
	public $adapter;
	public $select;
	
	public function __construct(Adapter $adapter)
	{
		
		$this->adapter = $adapter;
		$this->select = new \Zend\Db\Sql\Select;
		
	}	
	
	public function returnResults(){
				
		$sql = new Sql($this->adapter);
		
		$statement = $sql->prepareStatementForSqlObject($this->select);
		$results = $statement->execute();
		
		
	    $resultSet = new ResultSet;
	    $resultSet->initialize($results);
		
		return $resultSet;
	}
}
