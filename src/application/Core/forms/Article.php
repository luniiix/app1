<?php
class Core_Form_Article extends Zend_Form
{
    public function init(){
        
        $this->setDisableLoadDefaultDecorators(TRUE);
        
        $this->addDecorator('FormElements');
        $this->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'zend_form'));
        $this->addDecorator('Form');
        
        $articleTitle = new Zend_Form_Element_Text('article_title');
        $articleTitle->setDecorators(array(
        	'ViewHelper',
            new Core_Decorator_Errors()
        ));
        $articleTitle->setLabel('Titre de l\'article');
        $articleTitle->setRequired(TRUE);
        $articleTitle->setValidators(array(
        	'int' => new Zend_Validate_Alpha(),
        ));
        
        $this->addElement($articleTitle);
        
        $userAuth = Zend_Auth::getInstance()->getIdentity();
        
        $mapperUser = new Core_Model_Mapper_User();
        $authors = $mapperUser->fetchAllAuthor($userAuth->getUserId());
        $authorElement = new Zend_Form_Element_Select('author_id');
        $arrayAuthors = array();
        foreach($authors as $author){
            $arrayAuthors[$author->getAuthorId()] = $author->getAuthorName();
        }
        $authorElement->setMultiOptions($arrayAuthors);
        
        $this->addElement($authorElement);
        
        $this->addElement('textarea', 'article_content', 
                array('required' => TRUE,
                      'label' => 'Contenu de l\'article',
                      'rows' => 2,
                      'cols' => 30
        ));
        
        $submit = new Zend_Form_Element_Submit('create');
        $this->addElement($submit);
    }
}