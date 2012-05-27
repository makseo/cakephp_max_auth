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

class MaxAuthAppController extends AppController
{  
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        Configure::load('MaxAuth.config');
        
        $this->Auth->loginAction = '/login';
    }
    
    public function beforeRender()
    {
        parent::beforeRender();
    }
}