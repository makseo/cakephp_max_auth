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

class MaxAuthSchema extends CakeSchema
{
    public $name = 'MaxAuth';

    public function before($event = array())
    {
        return true;
    }

    public function after($event = array())
    {

    }

    public $users = array
    (
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'nickname' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
        'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
        'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
        'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
        'created' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
        'reset_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
        'facebook_user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20),
        'facebook_access_token' => array('type' => 'string', 'null' => false, 'length' => 255),
    );

    public $groups = array
    (
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL)
    );
}