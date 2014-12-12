<?php
namespace Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Exception;

class UserTable{
		
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}
	
	public function saveUser(User $user){
		$data = array(
			'email' => $user->email,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'password' => $user->password,
			'company_id' => $user->company_id,
			'group_id' => $user->group_id,
		);
		
		$id = (int)$user->id; 
		if($id == 0){
			
			try {
				$this->tableGateway->insert($data);
				return true;
			}catch(\Exception $e){
				return false;
			}
			
				
				
		}else{
			if ($this->getUser($id)) {
				$this->tableGateway->update($data, array('id' => $id));
				return true;
			}else{
				throw new \Exception('User ID does not exist');
			}
		}
	}
	
	public function fetchAll(){
			
		
		$select = new \Zend\Db\Sql\Select ;
        $select->from('user');
        $select->join('company', "company.id = user.company_id");
         
        echo $select->getSqlString();
        $rowset = $this->tableGateway->selectWith($select);
			
			
		//$resultSet = $this->tableGateway->select();
		return $rowset;
	}
	
	public function getUser($id){
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function getUserByEmail($userEmail){
		$rowset = $this->tableGateway->select(array('email' => $userEmail));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception("Could not find row $userEmail");
		}
		return $row;
	}
	
	public function deleteUser($id){
		$id = (int) $id;
		$this->tableGateway->delete(array('id' => $id));
	}
	
}
