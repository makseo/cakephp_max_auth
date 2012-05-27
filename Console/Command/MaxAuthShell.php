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

App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('AclComponent', 'Controller/Component');
App::uses('DbAcl', 'Model');
App::import('Component', 'Auth');

class MaxAuthShell extends AppShell
{
    public $uses = array('MaxAuth.User', 'MaxAuth.Group', 'Aro');
    
    public $Acl;
    
    public function startup()
    {
        parent::startup();
        $collection = new ComponentCollection();
        $this->Acl = new AclComponent($collection);
    }
    
    public function update()
    {
        $this->dispatchShell("max_auth.acl_extras aco_update");
    }
    
    public function init()
    {  
        $this->dispatchShell('schema create DbAcl');
        $this->dispatchShell("schema create --plugin MaxAuth --file max_auth");
        $this->dispatchShell("acl create aco root controllers");
        $this->dispatchShell("max_auth.acl_extras aco_sync");

        $this->Group->create();
        $this->Group->save(array('name' => 'superadmin'));
        $groupId = $this->Group->Id;
        $this->Aro->findByForeignKey($groupId);
        $this->Aro->save(array('alias' => 'superadmin'));

        $this->Group->create();
        $this->Group->save(array('name' => 'member'));
        $groupId = $this->Group->Id;
        $this->Aro->findByForeignKey($groupId);
        $this->Aro->save(array('alias' => 'member'));

        $this->User->create();
        $this->User->save(array('nickname' => 'Admin', 'email' => 'admin@example.com', 'password' => 'pass1234', 'group_id' => 1));
        $this->Aro->findByForeignKey($this->User->Id);
        $this->Aro->save(array('alias' => 'admin@example.com'));
        $this->Acl->allow('superadmin', 'controllers');
        $this->Acl->deny('member', 'controllers');
        $this->out('MaxAuth is now setup. Your username: admin password: pass1234');
    }
}