<?php
namespace Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Exception;

class CompanyTable{
		
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}
	
	
	public function fetchAll(){
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getCompany($id){
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	
}
