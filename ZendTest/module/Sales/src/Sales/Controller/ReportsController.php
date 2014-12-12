<?php
namespace Sales\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Zend\Session\Container; 

class ReportsController extends AbstractActionController
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
		$databaseSearch = $this->getServiceLocator()->get('DatabaseSearch');
		
		$databaseSearch->select->join('products', "products.id = invoice_details.product_id", array(
			'name' => 'name',
			'unit_price' => 'unit_price',
		));
		
		$databaseSearch->select->join('sales', "sales.id = invoice_details.sale_id", array(
			'timestamp' => 'timestamp',
		));
		
		$databaseSearch->select->from('invoice_details');
        $databaseSearch->select->columns(array(
			'sale_id' => 'sale_id',
			'quantity' => 'quantity',
		));
		
		$databaseSearch->select->order('sale_id');
		
		$results = $databaseSearch->returnResults();
		
		
		
		$viewModel = new ViewModel(array('sales' => $results));
		
		return $viewModel;
	}
	
	
	


}
