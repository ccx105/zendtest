<?php

namespace Users\Form;
use Zend\Form\Form;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserEditForm extends Form implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;
	
	public function __construct($name = null, $sm)
	{
		parent::__construct('UserEdit');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype', 'multipart/form-data');
		$this->setServiceLocator($sm);
		
		$companyTable = $this->getServiceLocator()->get('CompanyTable');
		$companies = $companyTable->fetchAll();
		
		$options = array();
		foreach ($companies as $company) {
			$options[$company->id] = $company->name;
		}
		
		
		$groupTable = $this->getServiceLocator()->get('GroupTable');
		$groups = $groupTable->fetchAll();
		
		$options_group = array();
		foreach ($groups as $group) {
			$options_group[$group->id] = $group->name;
		}
		
		
		
		$this->add(array( 
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
		
		
		$this->add(
			array(
				'name' => 'first_name',
				'attributes' => array(
					'type' => 'text',
				),
				'options' => array(
					'label' => 'First Name'
				),
			)
		);
		
		
		$this->add(
			array(
				'name' => 'last_name',
				'attributes' => array(
					'type' => 'text',
				),
				'options' => array(
					'label' => 'Last Name'
				),
			)
		);
		
		
		
		$this->add(
			array(
				'name' => 'email',
				'attributes' => array(
					'type' => 'email',
					'required' => 'required',
				),
				'options' => array(
					'label' => 'Email'
				),
				'filters' => array(
					array(
						'name' => 'StringTrim'
					),
				),
				
			)
		);
		
		$this->add(
			array(
				'name' => 'company_id',
				'type' => 'Zend\Form\Element\Select',				
				'options' => array(
					'label' => 'Company:',
					'empty_option' => 'Please choose a company',
					'value_options' => $options,
				),
			)
		);
		
		
		$this->add(
			array(
				'name' => 'group_id',
				'type' => 'Zend\Form\Element\Select',				
				'options' => array(
					'label' => 'Group:',
					'empty_option' => 'Please choose a group',
					'value_options' => $options_group,
				),
			)
		);
		
		
		$this->add(
			array(
				'name' => 'password',
				'attributes' => array(
					'type' => 'password',
					'required' => 'required',
				),
				'options' => array(
					'label' => 'Password'
				),			
			)
		);
		
		$this->add(
			array(
				'name' => 'confirm_password',
				'attributes' => array(
					'type' => 'password',
					'required' => 'required',
				),
				'options' => array(
					'label' => 'Confirm Password'
				),
				'validators' => array(
			        array(
			            'name'    => 'Identical',
			            'options' => array(
			                'token' => 'password',
			            ),
			        ),
			    ),
					
			)
		);
		
		
		$this->add(
			array(
				'name' => 'submit',
				'attributes' => array(
					'type' => 'submit',
					'value' => 'Edit',
				),
				'options' => array(
					'label' => 'Edit'
				),				
			)
		);
		
		
		
	}
	
	
	public function init()
    {
        
    }
	public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }
	public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}

