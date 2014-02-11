<?php 

/**
 * Bootstrap du module Core
 * 
 * @category    App1
 * @package     Core
 * @desc        Bootstrap du module Core
 * @author      jb <jb@ipformation-lyon.com>
 *  
 */
class Core_Bootstrap extends Zend_Application_Module_Bootstrap
{
    
    protected function _initPlugins()
    {
//         $fc = Zend_Controller_Front::getInstance();
//         $fc->registerPlugin(new Core_Plugin_Auth());
    }
    
    protected function _initAutoloading ()
    {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'  => SRC_PATH,
            'namespace' => '',
        ));
        $resourceLoader->addResourceType('asserter', 'application/Core/asserter', 'Core_Asserter');
    }
    
    protected function _initAcl(){
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Core_Plugin_Acl(), 90);
        
        $acl = new Zend_Acl();
        $acl->addRole(new Zend_Acl_Role(Core_Model_User::GUEST));
        
        $acl->addRole(new Zend_Acl_Role(Core_Model_User::AUTHOR));
        $acl->addRole(new Zend_Acl_Role(Core_Model_User::MODERATOR), array(Core_Model_User::AUTHOR));
        $acl->addRole(new Zend_Acl_Role(Core_Model_User::ROOT), array(Core_Model_User::MODERATOR));
        
        $acl->addResource('Core::auth::login');
        $acl->addResource('Core::auth::logout');
        $acl->addResource('Core::index::index');
        $acl->addResource('Core::article::index');
        
        $acl->addResource('article');
        
        $acl->allow(Core_Model_User::GUEST, 'Core::index::index');
        $acl->allow(Core_Model_User::GUEST, 'Core::auth::login');
        $acl->allow(Core_Model_User::AUTHOR, 'Core::index::index');
        $acl->allow(Core_Model_User::AUTHOR, 'Core::article::index');
        $acl->allow(Core_Model_User::AUTHOR, 'Core::auth::logout');
        
        $acl->allow(Core_Model_User::AUTHOR, 'article', 'read', new Core_Asserter_Owner());
        
        Zend_Registry::set('Zend_Acl', $acl);
        
    }
}