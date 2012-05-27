# MaxAuth Cakephp 2.0 ACL Management Plugin #

- supports creation of user groups;
- access rights for groups and for each user individually
- recaptcha support
- javascript form validation with qtip hints
- facebook oauth
- admin panel

-------------------------------

### Installation ###

Сlone repository into app/Plugin directory

```
git clone http://github.com/makseo/MaxAuth.git
```

Add following line into your app/Config/bootstrap.php file

```php
CakePlugin::load('MaxAuth', array('routes' => true));
```

Change config.php settings for Recaptcha and Facebook oAuth

Add following code into your app/Controller/AppController.php filer

```php
public $components = array('Acl', 'Session', 'Auth');

public $helpers = array('Html', 'Form', 'Session', 'MaxAuth.Auth');

public function beforeFilter()
{
    parent::beforeFilter();

    $this->Auth->loginAction = array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'login');
    $this->Auth->authorize = array(AuthComponent::ALL => array('actionPath' => 'controllers'), 'Actions');
}
```

Then from the command line call

```
Console/cake max_auth.max_auth init
```

NOTICE! After each change in controller or action you have to call

```
Console/cake max_auth.max_auth update
```