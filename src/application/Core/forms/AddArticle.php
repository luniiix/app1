<?php 


class Core_Form_AddArticle extends Zend_Form
{
    public function init()
    {
        $newArticle = new Zend_Form_Element_Text('newArticle');
        $newArticle->setLabel('Titre de l\'article');
        $newArticle->setRequired(true);
        $this->addElement($newArticle);
        
        $this->addElement('textarea', 'article_content',
			array('required' => TRUE,
					'label' => 'Contenu de l\'article'
        	)			
		);
        
        $submit = new Zend_Form_Element_Submit('send');
        $this->addElement($submit);
    }
}