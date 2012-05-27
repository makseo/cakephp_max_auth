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

App::uses('CakeEmail', 'Network/Email');

class UsersController extends MaxAuthAppController
{  
    public $uses = array('MaxAuth.User', 'MaxAuth.Group', 'Aro');
    
    public $components = array('MaxAuth.Recaptcha' => array('actions' => array('signup', 'forgot')));
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        $this->Auth->authenticate = array
        (
            'Form' => array
            (
                'userModel' => 'User',
                'fields' => array('username' => 'email'),
                'recursive' => 0,
            ),
        );
        
        $this->Auth->allow('*');
    }
    
    
    public function login()
    {
        // If login form has transferred
        if ($this->request->is('post') && !empty($this->request->data))
        {
            if ($this->Auth->login())
            {
                $this->redirect('/');
                exit(0);
            }
            else
            {
                $this->request->data['User']['password'] = '';
                $this->Session->setFlash(__('Email or password are incorrect!'), 'default', array('class' => 'failure'));
            }
        }
    }
    
    
    public function signup()
    {
        // If signup form has transferred
        if ($this->request->is('post') && !empty($this->request->data))
        {
            // Set Default User Group ID
            $this->User->create();
            $this->request->data['User']['group_id'] = Configure::read('MaxAuth.defaultGroupId');

            // Try to save a new user
            if ($this->User->save($this->request->data))
            {
                $this->fixAlias();
                
                // Auth with new data
                if ($this->Auth->login())
                {
                    $this->redirect('/');
                    exit(0);
                }
            }

            // Clean passwords
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['password_confirm'] = '';
        }
    }
    
    
    public function profile()
    {
        
    }
    
    
    public function forgot()
    {
        if ($this->request->is('post'))
        {
            // If captcha is success
            if ($this->Recaptcha->recaptcha)
            {
                // Get E-mail
                $send_to = $this->request->data['User']['email'];

                // Find User per email
                $this->User->recursive = -1;
                $user = $this->User->find('first', array('conditions' => array('User.email' => $send_to)));

                // If user exists, send him a letter with reset code
                if ($user)
                {
                    $reset_code = sha1(rand(0,10).rand(0,10).rand(0,10));
                    
                    $vars = array('reset_code' => $reset_code);
                    
                    $data = array('User' => $vars);
                    
                    $this->User->id = $user['User']['id'];
                    
                    if ($this->User->save($data))
                    {
                        $this->sendEmail('forgot', $send_to, 'gmail', $vars);
                        $this->Session->setFlash(__('On your e-mail has been sent a letter with instructions to reset your password.'), 'default', array('class' => 'success'));
                    }
                }
                
                
            }
            else
            {
                $this->Session->setFlash($this->Recaptcha->error, 'default', array('class' => 'failure'));
            }
        }
    }
    
    
    public function reset($code = NULL)
    {
        if ($this->request->is('post') && !empty($this->request->data))
        {
            $code = isset($this->request->data['User']['code']) ? $this->request->data['User']['code'] : '';
            
            $this->User->recursive = -1;
            $user = $this->User->find('first', array('conditions' => array('User.reset_code' => $code)));

            if ($user)
            {
                $this->request->data['User']['reset_code'] = '';
                
                $this->User->id = $user['User']['id'];
                
                if ($this->User->save($this->request->data))
                {
                    $this->Session->setFlash(__('New password has been successfully installed!'), 'default', array('class' => 'success'));
                    $this->redirect('/login');
                }
                
                $this->request->data['User']['password'] = '';
                $this->request->data['User']['password_confirm'] = '';
            }
            
            $this->set(compact('code'));
        }
        else
        {
            if (empty($code)) $this->redirect('/');

            $this->User->recursive = -1;
            $user = $this->User->find('first', array('conditions' => array('User.reset_code' => $code)));

            if ($user)
            {
                $this->set(compact('code'));
            }
            else
            {
                $this->Session->setFlash(__('An error occured! Please verify your data!'), 'default', array('class' => 'failure'));
                $this->redirect('/forgot');
            }
        }
    }
    
    
    public function logout()
    {
          $this->Auth->logout();
          $this->redirect('/');
          exit(0);
    }
    
    
    private function fixAlias()
    {
        $user = $this->User->read();
        $this->Aro->findByForeignKey($user['User']['id']);
        $this->Aro->save(array('alias' => $user['User']['email']));
    }
    
    
    private function sendEmail($type, $to, $config = 'default', $vars = array())
    {
        $email = new CakeEmail($config);
        
        $email->to($to)
                ->emailFormat('html')
                ->viewVars($vars)
                ->template('MaxAuth.'.$type, 'max_auth')
                ->subject(__('Password Reset'))
                ->send();
    }
}