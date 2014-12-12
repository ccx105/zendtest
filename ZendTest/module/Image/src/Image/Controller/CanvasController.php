<?php
namespace Image\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Zend\Session\Container; 

class CanvasController extends AbstractActionController
{
		
	protected $userSession;	
		
	public function onDispatch(\Zend\Mvc\MvcEvent $e)
	{
	    $userSession = new Container('user');
	    
		$this->layout()->setVariable('userSession', $userSession);
	    
	    if(!$userSession->loggedIn){
	       return $this->redirect()->toUrl('/users/login');
	    }
	        return parent::onDispatch($e);
	}
	
	
	public function indexAction()
	{
		$viewModel = new ViewModel();
		
		return $viewModel;
	}
	
	
	


}
