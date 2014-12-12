<?php
namespace Users\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;

use Users\Form\LoginForm;
use Users\Form\LoginFilter;

// References
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Zend\Session\Container; 


class LoginController extends AbstractActionController
{
	
	public $authservice;
		
	public function getAuthService(){
		
		if(! $this->authservice){
			return $this->getServiceLocator()->get('AuthService');
		}
		
	}
	
	public function indexAction()
	{
		$sessionUser = new Container('user');
		$sessionUser->getManager()->getStorage()->clear('user');	
			
		$form = $this->getServiceLocator()->get('LoginForm');
		$viewModel = new ViewModel(array(
			'form' => $form,
			'sessionUser' => $sessionUser,
		));
		return $viewModel;
	}
	
	public function logoutAction()
	{
		$sessionUser = new Container('user');
		$sessionUser->getManager()->getStorage()->clear('user');
		
		$form = $this->getServiceLocator()->get('LoginForm');
		$viewModel = new ViewModel(array('form' => $form));
		$viewModel->setTemplate('users/login/index');
		
		return $viewModel;
	}
	
	public function processAction()
	{
		if(!$this->request->isPost()){
			return $this->redirect()->toRoute(NULL, array(
				'controller' => 'index',
				'action' => 'login'
			));
		}	
		$post = $this->request->getPost();	
		$form = $this->getServiceLocator()->get('LoginForm');
		$form->setData($post);
		
		if(!$form->isValid()){
			$model = new ViewModel(array(
				'error' => true,
				'form' => $form,
			));
			$model->setTemplate('users/login/index');
			return $model;
		}
		
		
		$this->getAuthService()->getAdapter()->setIdentity($this->request->getPost('email'))->setCredential($this->request->getPost('password'));
		$result = $this->getAuthService()->authenticate();
		
		if ($result->isValid()) {
				
			$this->getAuthService()->getStorage()->write($this->request->getPost('email'));
			
			$user = $this->getServiceLocator()->get('UserTable')->getUserByEmail($this->request->getPost('email'));
			
			$userSession = new Container('user');
			$userSession->email = $user->email;
			$userSession->id = $user->id;
			
			
			$company = $this->getServiceLocator()->get('CompanyTable')->getCompany($user->company_id);
			$userSession->company_id = $user->company_id;
			$userSession->group_id = $user->group_id;
			$userSession->company_name = $company->name;
			
			$userSession->loggedIn = true;
			
			return $this->redirect()->toRoute(NULL , array(
				'controller' => 'login',
				'action' => 'confirm',
			));
		}else{
			$view = new ViewModel(array(
				'error' => true,
				'form' => $form,
			));
			$view->setTemplate('users/login/index');
			return $view;
		}	
		
	}

	
		
	public function confirmAction()
	{
		$user_email = $this->getAuthService()->getStorage()->read();
		$userSession = new Container('user');	
			
		$view = new ViewModel(array(
			'user_email' => $user_email,
			'userSession' => $userSession,
		));
		$view->setTemplate('users/login/confirm');
		return $view;
	}


}
