<?php

/**
 * Copyright (c) 2012, M@kSEO (http://makseo.ru)
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author M@kSEO
 * @copyright Copyright (c) 2012, M@kSEO (http://makseo.ru)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */

class Group extends AppModel
{
    public $name = 'Group';

    public $hasMany = array('User' => array('className' => 'User', 'foreignKey' => 'group_id'));
    
    public $actsAs = array('Acl' => array('type' => 'requester'));
    
    public function parentNode()
    {
        return null;
    }
}