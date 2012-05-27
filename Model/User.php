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

class User extends AppModel
{
    public $name = 'User';
    
    public $belongsTo = array('Group' => array('foreignKey' => 'group_id'));
    
    public $actsAs = array('Acl' => array('type' => 'requester'));
    
    public $validate = array
    (
        'email' => array
        (
            'required' => array
            (
                'rule' => array('notEmpty'),
                'message' => 'E-mail can not be empty',
            ),
            'emailCheck' => array
            (
                'rule' => array('email'),
                'message' => 'E-mail is invalid',
            ),
            'EmailUnique' => array
            (
                'rule' => array('isUnique'),
                'message' => 'User with this E-mail already exists',
            ),
        ),
        'password' => array
        (
            'required' => array
            (
                'rule' => array('notEmpty'),
                'message' => 'Password can not be empty',
            ),
            'length' => array
            (
                'rule' => array('between', 3, 100),
                'message' => 'Password must be between 3 and 100 characters',
            ),
        ),
        'password_confirm' => array
        (
            'required' => array
            (
                'rule' => array('notEmpty'),
                'message' => 'Password confirm can not be empty',
            ),
            'length' => array
            (
                'rule' => array('between', 3, 100),
                'message' => 'Password comfirm must be between 3 and 100 characters',
            ),
            'identical' => array
            (
                'rule' => array('identicalFieldValues', 'password'),
                'message' => 'Passwords are not equal',
            ),
        ),
    );
    
    
    public function parentNode()
    {
        if (!$this->id && empty($this->data))
        {
            return null;
        }
        
        $data = $this->data;
        
        if (empty($this->data))
        {
            $data = $this->read();
        }
        
        if (!$data['User']['group_id'])
        {
            return null;
        }
        else
        {
            return array('Group' => array('id' => $data['User']['group_id']));
        }
    }
    
    
    public function beforeSave()
    {
        if (isset($this->data[$this->alias]['password']))
        {
            $this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
        }

        return true;
    }
    
    
    protected function identicalFieldValues($field = array(), $compare_field = null)
    {
        if (!empty($this->data['User']))
        {
            $user = $this->data['User'];
            foreach($field as $key => $value)
            {
                $v1 = $value;
                $v2 = $user[$compare_field];
                
                if ($v1 !== $v2)
                {
                    return FALSE;
                }
                else
                {
                    continue;
                }
            }
        }

        return TRUE;
    }
}