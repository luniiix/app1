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
        $userAuth = Zend_Auth::getInstance()->getIdentity();
        if($this->_request->isPost()){
            $post = $this->_request->getPost();
            if($form->isValid($post)){
                $data = $post;
                unset($data['create']);
                $mapperArticle = new Core_Model_Mapper_Article();
                $article = $mapperArticle->insert($data);
            }else{
                $this->_helper->flashMessenger('Formulaire incorrect', 'error');
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
        $this->view->action = $this->_request->getActionName();
        $this->view->article = $article;
    }
    
    public function deleteAction(){
        $this->_helper->viewRenderer->setNoRender();
        $userAuth = Zend_Auth::getInstance()->getIdentity();
        
        $articleId = $this->_getParam('id');
        
        $mapperArticle = new Core_Model_Mapper_Article();
        $article = $mapperArticle->find($articleId);
        
        $acl = Zend_Registry::get('Zend_Acl');
        if($acl->isAllowed($userAuth, $article, 'delete')){
            $mapperArticle->delete($article);
            $this->_helper->flashMessenger('Supression de l\'article réussie', 'success');
        } else {
            $this->_helper->flashMessenger('Echec de la supression de l\'article', 'error');
        }
        $this->_redirect($this->_helper->url('index','article'));
    }
}