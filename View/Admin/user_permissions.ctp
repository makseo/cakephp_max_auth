<h2>Edit permissions for <?= $user['User']['email']; ?></h2>

<table>
<thead>
    <tr>
        <th>ACO</th>
        <th>Permission (click to change)</th>
        <th>Action</th>
    </tr>
</thead>

<?php foreach($perms as $perm): ?>
<tr>
    <td><?= $perm['Aco']['alias']; ?></td>
    <td><?= $perm['Aco']['perm']; ?></td>
    <?php if ($perm['Aco']['perm'] == 'allow'): ?>

    <td>
    <?php
    echo $this->Html->link(
    $this->Html->image('/max_auth/img/allow.png', array('alt' => 'Set to Deny', 'width' => '30')),
    array(
    'plugin' => 'max_auth',
    'controller' => 'admin',
    'action' => 'user_permissions',
    $user['User']['id'],
    '?' => array(
    'perm' => 'deny',
    'aro' => $user['User']['email'],
    'aco' => $perm['Aco']['alias']
    )
    ),
    array('escape' => false)
    );
    ?>

    </td>

    <?php else: ?>

        <td>

        <?php echo $this->Html->link
        (
            $this->Html->image('/max_auth/img/deny.png', array('alt' => 'Set to Allow', 'width' => '30')),
            array
            (
                'plugin' => 'max_auth',
                'controller' => 'admin',
                'action' => 'user_permissions',
                $user['User']['id'],
                '?' => array('perm' => 'allow', 'aro' => $user['User']['email'], 'aco' => $perm['Aco']['alias'])
            ),
            array('escape' => false)
        );
        ?>

        </td>

    <?php endif; ?>
</tr>
<?php endforeach; ?>
</table>