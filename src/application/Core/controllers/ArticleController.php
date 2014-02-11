<?php 

/**
 * @category    App1
 * @package     Core
 */

class ArticleController extends Zend_Controller_Action
{
    
    public function indexAction()
    {
        $auth = Zend_Auth::getInstance()->getIdentity();
        $articleMapper = new Core_Model_Mapper_Article();
        $articles = $articleMapper->fetchLast(5);
        
        $acl = Zend_Registry::get('Zend_Acl');
        $allowArticles = array();
        
        foreach($articles as $article){
            if($acl->isAllowed($auth, $article, 'read')){
                $allowArticles[] = $article;
            }
        }
        
        $this->view->articles = $allowArticles;
        
        $userMapper = new Core_Model_Mapper_User();
        $this->view->authors = $userMapper->fetchAllAuthor($auth->getUserId());
    }
    
    public function createAction(){
        
    }
    
    public function readAction(){
        
    }
        
    public function updateAction(){
        
    }
    
    public function deleteAction(){
        
    }
}