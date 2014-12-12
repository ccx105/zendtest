<?php
namespace Users\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Zend\Session\Container; 

class UserManagerController extends AbstractActionController
{
		
	protected $userSession;	
		
	public function onDispatch(\Zend\Mvc\MvcEvent $e)
	{
	    $userSession = new Container('user');
		
		$this->layout()->setVariable('userSession', $userSession);
		
	    if(!$userSession->loggedIn || $userSession->group_id != 2){
	       return $this->redirect()->toUrl('/users/login');
	    }
	        return parent::onDispatch($e);
	}
	
	
	public function indexAction()
	{
		$databaseSearch = $this->getServiceLocator()->get('DatabaseSearch');
		
		$databaseSearch->select->join('company', "company.id = user.company_id", array(
			'name' => 'name',
		));
		
		$databaseSearch->select->join('group', "group.id = user.group_id", array(
			'group_name' => 'name',
		));
		
		$databaseSearch->select->from('user');
        $databaseSearch->select->columns(array(
			'first_name' => 'first_name',
			'last_name' => 'last_name',
			'email' => 'email',
			'id' => 'id',
		));
		
		$databaseSearch->select->order('id');
		
		$results = $databaseSearch->returnResults();
		
		
		
		$viewModel = new ViewModel(array('users' => $results));
		
		return $viewModel;
	}
	
	public function editAction(){
		
		$userTable = $this->getServiceLocator()->get('UserTable');
		
		
		$user = $userTable->getUser($this->params()->fromRoute('id'));
		
		$form = $this->getServiceLocator()->get('UserEditForm');
		$user->password = '';
		$form->bind($user);
		
		$viewModel = new ViewModel(array(
			'form' => $form,
			'user_id' => $this->params()->fromRoute('id'),			
		));		
		
		return $viewModel;
	}
	
	public function processAction(){
			
		//Get user_id from post	
		$post = $this->request->getPost();
		
		$userTable = $this->getServiceLocator()->get('UserTable');
		
		//Load user entity
		$user = $userTable->getUser($post->id);
		
		//Bind User entity to form
		$form = $this->getServiceLocator()->get('UserEditForm');
		$form->bind($user);
		$form->setData($post);
		
		if(!$form->isValid()){
			$model = new ViewModel(
			
				array(
					'error' => true,
					'form' => $form,
				)
			
			);
			$model->setTemplate('users/user-manager/index');
			return $model;
		}
		
		//Save user
		$this->getServiceLocator()->get('UserTable')->saveUser($form->getData());
		
		$viewModel = new ViewModel(array(
			'email' => $user->email,			
		));		
		return $viewModel;
	}
	
	public function deleteAction(){
		$this->getServiceLocator()->get('UserTable')->deleteUser($this->params()->fromRoute('id'));
		
		$viewModel = new ViewModel(array(
			'id' => $this->params()->fromRoute('id'),			
		));		
		return $viewModel;
		
	}
	
	


}
