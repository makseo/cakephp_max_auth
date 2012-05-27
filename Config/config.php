<?php

/**
 * Max Auth Config File
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

$config['MaxAuth'] = array
(
    // Default Group ID for new users
    'defaultGroupId' => 2,
    
    // Recaptcha Settings
    'Recaptcha' => array
    (
        'publicKey' => '',
        'privateKey' => '',
    ),
    
    // Facebook oAuth Settings
    'Facebook' => array
    (
        'app_id' => '',
        'app_secret' => '',
        'perms' => array('email'),
    ),
);