<?php
class Core_Asserter_Owner implements Zend_Acl_Assert_Interface
{
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        $userId = $role->getUserId();
        $userIdAuthor = $resource->getAuthor()->getUserId();
        
        if($userId === $userIdAuthor){
            return true;
        }
    }
}