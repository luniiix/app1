<?php 

/**
 * 
 * Controller par défaut de l'application
 * 
 * @category    App1
 * @package     Core
 */

class IndexController extends Zend_Controller_Action
{
    
    public function indexAction()
    {   
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $this->forward('index', 'article');
        }else{
            $this->forward('login', 'auth');
        }
    }
}