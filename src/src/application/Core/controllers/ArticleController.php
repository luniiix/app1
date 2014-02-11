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
        $form = new Core_Form_Article();
        $form->setDecorators(array(
            array('ViewScript', array('viewScript' => '/article/forms/article.phtml',
                                      'title' => 'Création d\'un article')),
            'Form'
        ));
        if($this->_request->isPost()){
            $post = $this->_request->getPost();
            if($form->isValid($post)){
                echo 'Je suis ok !!';
            }else{
                echo 'Pas ok !!';
            }
        }
        
        $this->view->form = $form;
    }
    
    public function readAction(){
        $articleId = $this->_getParam('id');
        
        $mapperArticle = new Core_Model_Mapper_Article();
        $article = $mapperArticle->find($articleId);
        
        $this->view->article = $article;
    }
        
    public function updateAction(){
        $articleId = $this->_getParam('id');
        
        $mapperArticle = new Core_Model_Mapper_Article();
        $article = $mapperArticle->find($articleId);
        
        $form = new Core_Form_Article();
        $form->setDecorators(array(
            array('ViewScript', array('viewScript' => '/article/forms/article.phtml',
                'title' => 'Modification d\'un article')),
            'Form'
        ));
        
        $form->populate(array('article_title' => $article->getArticleTitle(),
                              'article_content' => $article->getArticleContent()));
        
        $this->view->form = $form;
    }
    
    public function deleteAction(){
        $this->_helper->viewRenderer->setNoRender();
        $userAuth = Zend_Auth::getInstance()->getIdentity();
        
        $articleId = $this->_getParam('id');
        
        $mapperArticle = new Core_Model_Mapper_Article();
        $article = $mapperArticle->find($articleId);
        
        $session = new Zend_Session_Namespace('message');
        $acl = Zend_Registry::get('Zend_Acl');
        if($acl->isAllowed($userAuth, $article, 'delete')){
            $mapperArticle->delete($article);
            $session->__set('value', 'Supression de l\'article réussie');
        } else {
            $session->__set('value', 'Echec de la supression de l\'article');
        }
        
        $this->_redirect('/accueil.html');
    }
}