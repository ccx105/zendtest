<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;


use Users\Model\User;
use Users\Model\DatabaseSearch;
use Users\Model\UserTable;
use Users\Model\Company;
use Users\Model\CompanyTable;
use Users\Model\Group;
use Users\Model\GroupTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\SessionManager;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
	
	public function getServiceConfig(){
		return array(
			'abstract_factories' => array(),
			'aliases' => array(),
			'factories' => array(
			
				
				'SessionManager' => function($sm){
					$sessionManager = new SessionManager;
					$sessionManager->setStorage(new SessionArrayStorage());
					return $sessionManager;
				},
				'UserTable' => function($sm){
					$tableGateway = $sm->get('UserTableGateway');
					$table = new UserTable($tableGateway);
					return $table;
				},
				'CompanyTable' => function($sm){
					$tableGateway = $sm->get('CompanyTableGateway');
					$table = new CompanyTable($tableGateway);
					return $table;
				},
				'GroupTable' => function($sm){
					$tableGateway = $sm->get('GroupTableGateway');
					$table = new GroupTable($tableGateway);
					return $table;
				},
				'UserTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new User());
					return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
				},
				'CompanyTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Company());
					return new TableGateway('company', $dbAdapter, null, $resultSetPrototype);
				},
				'GroupTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Group());
					return new TableGateway('group', $dbAdapter, null, $resultSetPrototype);
				},
				'LoginForm' => function($sm){
					$form = new \Users\Form\LoginForm();
					$form->setInputFilter($sm->get('LoginFilter'));
					return $form;
				},
				'RegisterForm' => function($sm){
					$form = new \Users\Form\RegisterForm(null, $sm);
					$form->setInputFilter($sm->get('RegisterFilter'));
					return $form;
				},
				'UserEditForm' => function($sm){
					$form = new \Users\Form\UserEditForm(null, $sm);
					$form->setInputFilter($sm->get('UserEditFilter'));
					return $form;
				},
				'LoginFilter' => function($sm){
					return new \Users\Form\LoginFilter();
				},
				'RegisterFilter' => function($sm){
					return new \Users\Form\RegisterFilter(null, $sm);
				},
				'UserEditFilter' => function($sm){
					return new \Users\Form\UserEditFilter(null, $sm);
				},
				'AuthService' => function($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user', 'email', 'password', 'SHA1(?)');
					$authService = new AuthenticationService();
					$authService->setAdapter($dbTableAuthAdapter);
					return $authService;
				},
				'DatabaseSearch' => function($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					
					$databaseSearch = new DatabaseSearch($dbAdapter);
					return $databaseSearch;
				},

			
			),
			
			'invokables' => array(),
			'services' => array(),
			'shared' => array(),
		);
	}
	
}
