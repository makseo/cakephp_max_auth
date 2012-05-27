<h2>Group List</h2>

<table>
<tr>
    <th><?php echo $this->Paginator->sort('name');?></th>
    <th><?php echo $this->Paginator->sort('created');?></th>
    <th><?php echo $this->Paginator->sort('modified');?></th>
    <th colspan="3" class="actions"><?php echo __('Actions');?></th>
</tr>

<?php foreach($groups as $group): ?>
<tr>
<td>
    <?= $group['Group']['name']; ?>
    <small>(<?= count($group['User']); ?> users)</small>
    <small><?php if ($group['Group']['id'] == $defaultGroupId) echo "Default Group"; ?></small>
</td>
<td><?= $group['Group']['created']; ?></td>
<td><?= $group['Group']['modified']; ?></td>
<td><?= $this->Html->link('Rename', '/maxauth/admin/group_edit/'.$group['Group']['id']); ?></td>
<td><?= $this->Html->link('Set Permissions', '/maxauth/admin/group_permissions/'.$group['Group']['id']); ?></td>
<td><?= $this->Html->link('Delete', '/maxauth/admin/group_delete/'.$group['Group']['id'], array(), 'Are you sure you want to delete this group?'); ?></td>
</tr>
<?php endforeach; ?>
</table>

<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>	</p>

<div class="paginator">
<?php
	echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
	echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
?>
</div>

<p>&nbsp;</p>

<div class="formBox">
<div class="formTitleBar"><span class="formTitle"><?= __('Add group'); ?></span></div>
<div class="formContent">
<?= $this->Form->create('Group', array('type' => 'post', 'inputDefaults' => array('label' => false, 'div' => false))); ?>
<?= $this->Form->input('name', array('placeholder' => __('name'))); ?>
</div>
<div class="formButtons">
<?= $this->Form->submit('Add Group', array('class' => 'button button-blue')); ?>
</div>
<?= $this->Form->end(); ?>
</div>