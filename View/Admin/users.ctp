<h2>User List</h2>

<table>
<tr>
    <th><?= $this->Paginator->sort('id');?></th>
    <th><?= $this->Paginator->sort('nickname');?></th>
    <th><?= $this->Paginator->sort('email');?></th>
    <th><?= $this->Paginator->sort('group_id');?></th>
    <th><?= $this->Paginator->sort('created');?></th>
    <th class="actions" colspan="3"><?= __('Actions');?></th>
</tr>
<?php foreach($users as $user): ?>
<tr>
    <td><?= $user['User']['id']; ?></td>
    <td><?= $user['User']['nickname']; ?></td>
    <td><?= $user['User']['email']; ?></td>
    <td><?= $groups[$user['User']['group_id']]; ?></td>
    <td><?= $user['User']['created']; ?></td>
    <td><?= $this->Html->link('Edit', '/maxauth/admin/user_edit/'.$user['User']['id']); ?></td>
    <td><?= $this->Html->link('Permissions', '/maxauth/admin/user_permissions/'.$user['User']['id']); ?></td>
    <td><?= $this->Html->link('Delete', '/maxauth/admin/user_delete/'.$user['User']['id'], array(), __('Are you sure you want to delete this user?')); ?></td>
</tr>
<?php endforeach; ?>
</table>

<p>
<?php
echo $this->Paginator->counter
(
    array
    (
        'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    )
);
?></p>

<div class="paginator">
<?php
	echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
	echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
?>
</div>