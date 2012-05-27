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

App::uses('MaxAuthAppController', 'MaxAuth.Controller');
App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('AclComponent', 'Controller/Component');
App::uses('DbAcl', 'Model');

class AdminController extends MaxAuthAppController
{
    public $uses = array('MaxAuth.User', 'MaxAuth.Group', 'Aco', 'Aro');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        $this->layout = 'admin';
    }
    
    
    public function index()
    {
        
    }
    
    
    public function users()
    {
        $this->paginate = array('limit' => 15, 'order' => 'User.id');
        $this->set('users', $this->paginate('User'));
        $this->set('groups', $this->Group->find('list'));
    }
    
    
    public function user_edit($id)
    {
        $this->User->id = $id;
        
        if (!$this->User->exists())
        {
            throw new NotFoundException(__('Invalid user'));
        }
        
        if ($this->request->is('post'))
        {
            if (empty($this->request->data['User']['password']))
            {
                unset($this->request->data['User']['password']);
            }
            
            if ($this->User->save($this->request->data))
            {
                $this->fixAroAlias('User');
                
                $this->fixAroParent();
                
                $this->Session->setFlash(__('User has been successfully saved!'), 'default', array('class' => 'success'));
                
                $this->redirect(array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'users'));
            }
            else
            {
                $this->Session->setFlash(__('User could not be saved!'), 'admin', array('class' => 'failure'));
            }
        }
        else
        {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
        
        // Set Groups to view
        $this->set('groups', $this->Group->find('list'));
    }
    
    
    public function user_delete($id)
    {
        $this->User->delete($id);
        
        if (($aro = $this->Acl->Aro->findByForeignKey($id)))
        {
            $this->Acl->Aro->delete($aro['Aro']['id']);
        }
        
        $this->redirect(array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'users'));
    }
    
    
    public function user_permissions($id)
    {
        if (!empty($this->request->query))
        {
            $req = $this->request->query;

            if ($req['perm'] == 'allow')
            {
                $this->Acl->allow($req['aro'], $req['aco']);
                $this->Session->setFlash('Aro has been allowed');
            }
            elseif ($req['perm'] == 'deny')
            {
                $this->Acl->deny($req['aro'], $req['aco']);
                $this->Session->setFlash('Aro has been denied');
            }
        }

        $this->User->id = $id;
        $user = $this->User->read();
        
        $acos = $this->Acl->Aco->find('threaded');

        $this->set('user', $user);
        $this->set('acos', $acos);

        $perms = array();

        $i = 0;

        foreach($acos as $aco)
        {
            $perms[$i]['Aco']['alias'] = $aco['Aco']['alias'];
            
            $perms[$i]['Aco']['layer'] = 1;
            
            if ($this->Acl->check($user['User']['email'], $aco['Aco']['alias']) == '1')
            {
                $perms[$i]['Aco']['perm'] = 'allow';
            }
            else
            {
                $perms[$i]['Aco']['perm'] = 'deny';
            }
            
            $i++;

            //Second Layer
            foreach($aco['children'] as $aco2)
            {
                $perms[$i]['Aco']['alias'] = $aco2['Aco']['alias'];
                
                $perms[$i]['Aco']['layer'] = 2;
                
                if ($this->Acl->check($user['User']['email'], $aco2['Aco']['alias']) == '1')
                {
                    $perms[$i]['Aco']['perm'] = 'allow';
                }
                else
                {
                    $perms[$i]['Aco']['perm'] = 'deny';
                }
                
                $i++;

                //Third Layer
                foreach($aco2['children'] as $aco3)
                {
                    $perms[$i]['Aco']['alias'] = $aco2['Aco']['alias']."/".$aco3['Aco']['alias'];
                    
                    $perms[$i]['Aco']['layer'] = 3;
                    
                    if ($this->Acl->check($user['User']['email'], $aco2['Aco']['alias']."/".$aco3['Aco']['alias']) == '1')
                    {
                        $perms[$i]['Aco']['perm'] = 'allow';
                    }
                    else
                    {
                        $perms[$i]['Aco']['perm'] = 'deny';
                    }
                    
                    $i++;

                    //Fourth Layer
                    foreach($aco3['children'] as $aco4)
                    {
                        $perms[$i]['Aco']['alias'] = $aco2['Aco']['alias']."/".$aco3['Aco']['alias']."/".$aco4['Aco']['alias'];
                        
                        $perms[$i]['Aco']['layer'] = 4;
                        
                        if ($this->Acl->check($user['User']['email'], $aco2['Aco']['alias']."/".$aco3['Aco']['alias']."/".$aco4['Aco']['alias']) == '1')
                        {
                            $perms[$i]['Aco']['perm'] = 'allow';
                        }
                        else
                        {
                            $perms[$i]['Aco']['perm'] = 'deny';
                        }
                        
                        $i++;
                    }
                }
            }
        }
				
        $this->set('perms', $perms);
    }
    
    
    public function groups()
    {
        if ($this->request->is('post'))
        {
            $this->Group->create();
            
            if ($this->Group->save($this->request->data))
            {
                $this->fixAroAlias('Group');
                $group = $this->Group->read();
                $this->Acl->deny($group['Group']['name'], 'controllers');
                
                $this->Session->setFlash(__("New group has been added."), 'default', array('class' => 'success'));
            }
            else
            {
                $this->Session->setFlash(__("Unable to create your a group."), 'default', array('class' => 'failure'));
            }
        }

        $this->paginate = array('limit' => 15);
        $this->set('groups', $this->paginate('Group'));
        
        $defaultGroupId = Configure::read('MaxAuth.defaultGroupId');
        $this->set('defaultGroupId', $defaultGroupId);
    }
    
    
    public function group_edit($id)
    {
        $this->Group->id = $id;
        
        if (!$this->Group->exists())
        {
            throw new NotFoundException(__('Invalid group'));
        }
        
        if ($this->request->is('post'))
        {
            if ($this->Group->save($this->request->data))
            {
                $this->fixAroAlias('Group');
                
                $this->Session->setFlash(__('Group has been successfully saved'), 'default', array('class' => 'success'));
                
                $this->redirect(array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'groups'));
            }
            else
            {
                $this->Session->setFlash(__('Group could not be saved.'), 'default', array('failure' => 'failure'));
            }
        }
        else
        {
            $this->request->data = $this->Group->read(null, $id);
        }
    }
    
    
    public function group_permissions($id)
    {
        if (!empty($this->request->query))
        {
            $req = $this->request->query;
            
            if ($req['perm'] == 'allow')
            {
                $this->Acl->allow($req['aro'], $req['aco']);
                
                $this->Session->setFlash(__('Aro has been allowed'), 'default', array('class' => 'success'));
            }
            elseif ($req['perm'] == 'deny')
            {
                $this->Acl->deny($req['aro'], $req['aco']);
                
                $this->Session->setFlash(__('Aro has been denied'), 'default', array('class' => 'success'));
            }
        }

        $this->Group->recursive = -1;
        $this->Group->id = $id;
        $group = $this->Group->read();
	 $this->set('group', $group);
     
        $acos = $this->Acl->Aco->find('threaded');    
        $this->set('acos', $acos);
		
        $perms = array();

        $i = 0;

        // First Layer
        foreach($acos as $aco)
        {
            $perms[$i]['Aco']['alias'] = $aco['Aco']['alias'];
            
            $perms[$i]['Aco']['layer'] = 1;
            
            if ($this->Acl->check($group['Group']['name'], $aco['Aco']['alias']) == '1')
            {
                $perms[$i]['Aco']['perm'] = 'allow';
            }
            else
            {
                $perms[$i]['Aco']['perm'] = 'deny';
            }
            
            $i++;
			
            // Second Layer
            foreach($aco['children'] as $aco2)
            {
                $perms[$i]['Aco']['alias'] = $aco2['Aco']['alias'];
                
                $perms[$i]['Aco']['layer'] = 2;
                
                if ($this->Acl->check($group['Group']['name'], $aco2['Aco']['alias']) == '1')
                {
                    $perms[$i]['Aco']['perm'] = 'allow';
                }
                else
                {
                    $perms[$i]['Aco']['perm'] = 'deny';
                }
                
                $i++;

                //Third Layer
                foreach($aco2['children'] as $aco3)
                {
                    $perms[$i]['Aco']['alias'] = $aco2['Aco']['alias']."/".$aco3['Aco']['alias'];

                    $perms[$i]['Aco']['layer'] = 3;

                    if ($this->Acl->check($group['Group']['name'], $aco2['Aco']['alias']."/".$aco3['Aco']['alias']) == '1')
                    {
                        $perms[$i]['Aco']['perm'] = 'allow';
                    }
                    else
                    {
                        $perms[$i]['Aco']['perm'] = 'deny';
                    }

                    $i++;

                    // Fourth Layer
                    foreach($aco3['children'] as $aco4)
                    {
                        $perms[$i]['Aco']['alias'] = $aco2['Aco']['alias']."/".$aco3['Aco']['alias']."/".$aco4['Aco']['alias'];

                        $perms[$i]['Aco']['layer'] = 4;

                        if ($this->Acl->check($group['Group']['name'], $aco2['Aco']['alias']."/".$aco3['Aco']['alias']."/".$aco4['Aco']['alias']) == '1')
                        {
                            $perms[$i]['Aco']['perm'] = 'allow';
                        }
                        else
                        {
                            $perms[$i]['Aco']['perm'] = 'deny';
                        }

                        $i++;
                    }
                }
            }
        }
        
        $this->set('perms', $perms);
    }
    
    
    public function group_delete($id)
    {
        $this->Group->delete($id);
        
        $this->redirect(array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'groups'));
    }
    
    
    private function fixAroAlias($model)
    {
        switch($model)
        {
            case 'Group':
                $insertId = $this->Group->id;
                $group = $this->Group->read('name');
                $alias = $group['Group']['name'];
                break;

            case 'User':
                $insertId = $this->User->id;
                $user = $this->User->read('email');
                $alias = $user['User']['email'];
                break;
        }

        $aroRecord = $this->Aro->find('first', array('conditions' => array('foreign_key' => $insertId, 'model' => $model)));

        if ($aroRecord['Aro']['alias'] != $alias)
        {
            $aroRecord['Aro']['alias'] = $alias;
            $this->Aro->save($aroRecord);
        }
    }
    
    
    private function fixAroParent()
    {
        $insertId = $this->User->id;
        $user = $this->User->read('group_id');
        $group = $user['User']['group_id'];
        $aroRecord = $this->Aro->find('first', array('conditions' => array('foreign_key' => $insertId, 'model' => 'User')));
        
        if ($aroRecord['Aro']['parent_id'] != $group)
        {
            $aroRecord['Aro']['parent_id'] = $group;
            $this->Aro->save($aroRecord);
        }
    }
}