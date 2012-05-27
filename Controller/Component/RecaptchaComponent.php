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

App::uses('HttpSocket', 'Network/Http');

class RecaptchaComponent extends Component
{
    /**
     * Name
     *
     * @var string
     */
    public $Controller = null;

    /**
     * Recaptcha API Url
     *
     * @var string
     */
    public $apiUrl = 'http://www.google.com/recaptcha/api/verify';

    /**
     * Private API Key
     *
     * @var string
     */
    public $privateKey = '';

    /**
     * Error coming back from Recaptcha
     *
     * @var string
     */
    public $error = null;

    /**
     * Actions that should automatically checked for a recaptcha input
     *
     * @var array
     */
    public $actions = array();

    /**
     * Settings
     *
     * @var array
     */
    public $settings = array();

    /**
     * Callback
     *
     * @param object Controller object
     * @param Array $settings 
     */
    public function initialize(Controller $controller, $settings = array())
    {
        if ($controller->name == 'CakeError')
        {
            return;
        }
        
        Configure::load('MaxAuth.config');
        $this->privateKey = Configure::read('MaxAuth.Recaptcha.privateKey');
        
        $this->Controller = $controller;

        if (empty($this->privateKey))
        {
            throw new Exception(__d('recaptcha', "You must set your private recaptcha key using Configure::write('MaxAuth.Recaptcha.privateKey', 'your-key');!", true));
        }

        $defaults = array
        (
            'modelClass' => $this->Controller->modelClass,
            'errorField' => 'recaptcha',
            'actions' => array()
        );

        $this->settings = array_merge($defaults, $settings);
        $this->actions = array_merge($this->actions, $this->settings['actions']);
        unset($this->settings['actions']);
    }

    /**
     * Callback
     *
     * @param object Controller object
     */
    public function startup(Controller $controller)
    {
        extract($this->settings);
        
        if ($this->Controller->Components->enabled('Recaptcha'))
        {
            // Automatic mode .. using actions
            if (in_array($this->Controller->action, $this->actions)) {
                $this->Controller->helpers[] = 'MaxAuth.Recaptcha';

                $this->recaptcha = true;
                
                $this->Controller->{$modelClass}->Behaviors->load('MaxAuth.Recaptcha', array('errorField' => $errorField));
                
                $helper_options['useActions'] = true;
                
                if (!$this->verify())
                {
                    $this->recaptcha = false;
                    
                    $helper_options['error'] = $this->error;
                }
            }
            else
            {
                $helper_options['useActions'] = false;   
            }
            
            $this->Controller->helpers['MaxAuth.Recaptcha'] = $helper_options;
        }
    }

    /**
     * Verifies the recaptcha input
     *
     * Please note that you still have to pass the result to the model and do
     * the validation there to make sure the data is not saved!
     *
     * @return boolean True if the response was correct
     */
    public function verify() {
        if (isset($this->Controller->request->data['recaptcha_challenge_field']) &&
                isset($this->Controller->request->data['recaptcha_response_field'])) {

            $response = $this->_getApiResponse();
            $response = explode("\n", $response);

            if (empty($response[0])) {
                $this->error = __d('recaptcha', 'Invalid API response, please contact the site admin.', true);
                return false;
            }

            if ($response[0] == 'true') {
                return true;
            }

            if ($response[1] == 'incorrect-captcha-sol') {
                $this->error = __d('recaptcha', 'Incorrect captcha', true);
            } else {
                $this->error = $response[1];
            }

            return false;
        }
    }

    /**
     * Queries the Recaptcha API and and returns the raw response
     *
     * @return string
     */
    protected function _getApiResponse() {
        $Socket = new HttpSocket();
        return $Socket->post($this->apiUrl, array(
                    'privatekey' => $this->privateKey,
                    'remoteip' => env('REMOTE_ADDR'),
                    'challenge' => $this->Controller->request->data['recaptcha_challenge_field'],
                    'response' => $this->Controller->request->data['recaptcha_response_field']));
    }

}
