<?php

namespace Users\Form;
use Zend\InputFilter\InputFilter;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterFilter extends InputFilter implements ServiceLocatorAwareInterface{
	
	protected $serviceLocator;
	
	public function __construct($name = null, $sm){
		
		
		$this->setServiceLocator($sm);
			
		$this->add(array(
			'name' => 'email',
			'required' => true,
			'validators' => array(
				array(
					'name' => 'EmailAddress',
					'options' => array(
						'domain' => true,
					),
				),
			),
		));
		
		
		$this->add(array(
			'name' => 'first_name',
			'required' => true,
			'filters' => array(
				array(
					'name' => 'StripTags'
				),
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min' => 1,
						'max' => 255,
					),
				),
			),
			
		));
		
		$this->add(array(
			'name' => 'last_name',
			'required' => true,
			'filters' => array(
				array(
					'name' => 'StripTags'
				),
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min' => 1,
						'max' => 255,
					),
				),
			),
			
		));
		
		
		$this->add(array(
			'name' => 'company_id',
			'required' => true,
			'filters' => array(
				array(
					'name' => 'Int'
				),
			),
			'validators' => array(
				array(
					'name' => 'DB\RecordExists',
					'options' => array(
						'table' => 'company',
						'field' => 'id',
						'adapter' => $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'),
					),
				),
			),
			
		));
		
		
		$this->add(array(
			'name' => 'group_id',
			'required' => true,
			'filters' => array(
				array(
					'name' => 'Int'
				),
			),
			'validators' => array(
				array(
					'name' => 'DB\RecordExists',
					'options' => array(
						'table' => 'group',
						'field' => 'id',
						'adapter' => $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'),
					),
				),
			),
			
		));
		
		
		$this->add(array(
			'name' => 'password',
			'required' => true,			
			'options' => array(
						'min' => 5,
						'max' => 20,
					),
		));
		
		$this->add(array(
			'name' => 'confirm_password',
			'required' => true,	
			'validators' => array(
				array(
					'name' => 'Identical',
					'options' => array(
						'token' => 'password',
						'messages' => array(
							\Zend\Validator\Identical::NOT_SAME => 'The passwords you have entered don\'t match.',
						),
					),
				),
			),					
		));
		
		
		
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
