<?php

/**
 * Cakephp Auth Helper
 * 
 * Copyright (c) 2012, M@kSEO (http://makseo.ru)
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author M@kSEO
 * @copyright Copyright (c) 2012, M@kSEO (http://makseo.ru)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */

class AuthHelper extends AppHelper
{
    /**
     * Include Session Helper
     * 
     * @var array
     */
    public $helpers = array('Session');
    
    /**
     * Check whether the user is authorized
     * 
     * @return boolean - true if user authorized and false otherwise
     */
    public function loggedIn()
    {
        return (bool) $this->getUserId();
    }
    
    /**
     * Return User Id
     * 
     * @return int
     */
    public function getUserId()
    {
        return $this->Session->read('Auth.User.id');
    }
    
    /**
     * Return Group Id
     * 
     * @return int
     */
    public function getGroupId()
    {
        return $this->Session->read('Auth.User.group_id');
    }
    
    /**
     * Return user information
     * 
     * @return array
     */
    public function user()
    {
        return $this->Session->read('Auth.User');
    }  
}