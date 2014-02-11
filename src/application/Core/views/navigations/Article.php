<?php

class Core_Navigation_Article extends Zend_Navigation_Page_Mvc {

    /**
    * Params with ":" are read from request
    * 
    * @param array $params
    * @return Zend_Navigation_Page_Mvc
    */
    public function setParams(array $params = null) {
        $requestParams = Zend_Controller_Front::getInstance()
                        ->getRequest()->getParams();

        //searching for dynamic params (begining with :)
        foreach ($params as $paramKey => $param) {
            if (substr($param, 0, 1) == ':' &&
                    array_key_exists(substr($param, 1), $requestParams)) {
                $params[$paramKey] = $requestParams[substr($param, 1)];
            }
        }
        
        return parent::setParams($params);
    }

    /**
    * If label begining with : manipulate (for example with Zend_Tanslate)
    */
    public function setLabel($label) {
        if (substr($label, 0, 1) == ':') {
        	$requestParams = Zend_Controller_Front::getInstance()
            				->getRequest()->getParams();
            $labelParamKey = substr($label, 1);
            
            if (array_key_exists($labelParamKey, $requestParams)) {

            	$view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
				$label = null;
				
				$mapperArticle = new Core_Model_Mapper_Article();
		        $article = $mapperArticle->find($requestParams);
		        
		        $label = $article->getArticleTitle();
            }
        }

        return parent::setLabel($label);
    }

}