<?php
class Core_View_Helper_FlashMessenger extends Zend_View_Helper_Abstract
{
    const INFO  = 'info';
    const SUCC  = 'success';
    const WARN  = 'warning';
    const ERR   = 'error';
    const DEBUG = 'debug';
    
    public function flashMessenger()
    {
        $session = new Zend_Session_Namespace('FlashMessenger');
        $xhtml = null;
        
        if($session->__isset(self::INFO))
        {
            $xhtml .= $this->_build($session->__get(self::INFO), self::INFO);
        }
        if($session->__isset(self::SUCC))
        {
            $xhtml .= $this->_build($session->__get(self::SUCC), self::SUCC);
        }
        if($session->__isset(self::WARN))
        {
            $xhtml .= $this->_build($session->__get(self::WARN), self::WARN);
        }
        if($session->__isset(self::ERR))
        {
            $xhtml .= $this->_build($session->__get(self::ERR), self::ERR);
        }
        if($session->__isset(self::DEBUG))
        {
            $xhtml .= $this->_build($session->__get(self::DEBUG), self::DEBUG);
        }
        
        return $xhtml;
    }
    
    protected function _build(array $messages, $class)
    {
        $xhtml = '<ul class="' . $class . '">';
        foreach($messages as $message){
            $xhtml .= '<li>' . $message . '</li>';
        }
        $xhtml .= '</ul>';
        
        return $xhtml;
    }
}