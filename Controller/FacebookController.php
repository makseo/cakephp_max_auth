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

class FacebookController extends MaxAuthAppController
{
    public $components = array('Auth');

    public function beforeFilter()
    {
        parent::beforeFilter();

        $app_id = Configure::read('MaxAuth.Facebook.app_id');
        $app_secret = Configure::read('MaxAuth.Facebook.app_secret');
        $this->Auth->authenticate['MaxAuth.Facebook']['application'] = array('id' => $app_id, 'secret' => $app_secret);
        $this->Auth->allowedActions = array_merge($this->Auth->allowedActions, array('login'));
    }


    public function login()
    {
        if (!$this->Auth->login())
        {
            $app_id = Configure::read('MaxAuth.Facebook.app_id');
            $app_secret = Configure::read('MaxAuth.Facebook.app_secret');
            $perms = implode(',', Configure::read('MaxAuth.Facebook.perms'));
            $csrfToken   = CakeSession::read('FacebookAuthCSRF');
            $redirect = Router::url(false, true);

            $url = "https://www.facebook.com/dialog/oauth?client_id={$app_id}&redirect_uri={$redirect}&scope={$perms}&state={$csrfToken}";

            $this->redirect($url);
            
            exit(0);
        }
        
        $this->layout = 'close_window';
        $this->render(false);
    }
}