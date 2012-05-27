<?php

/**
 * Max Auth Routes
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

Router::connect('/login', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'logout'));
Router::connect('/signup', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'signup'));
Router::connect('/profile', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'profile'));
Router::connect('/forgot', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'forgot'));
Router::connect('/reset', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'reset'));
Router::connect('/reset/*', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'reset'));
Router::connect('/maxauth/admin', array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'index'));
Router::connect('/maxauth/admin/:action/*', array('plugin' => 'MaxAuth', 'controller' => 'admin'));
Router::connect('/facebook_login', array('plugin' => 'MaxAuth', 'controller' => 'facebook', 'action' => 'login'));