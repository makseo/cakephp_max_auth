<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $title_for_layout?></title>
<?= $this->Html->css(array('/max_auth/css/layout', '/max_auth/css/forms', '/max_auth/css/buttons', '/max_auth/css/admin')); ?>
<?= $this->Html->script(array('/max_auth/js/jquery-1.7.2.min', '/max_auth/js/jquery.validate.min', '/max_auth/js/jquery.qtip.min')); ?>
</head>
<body>
<div id="container">
<div id="header"><h1>MaxAuth - User Authorization Manager</h1></div>
<div id="menu">
    <ul>
        <li><?php echo $this->Html->link('Home', '/'); ?></li>
        <li><?php echo $this->Html->link('Admin', array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link('Users', array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'users')); ?></li>
        <li><?php echo $this->Html->link('Groups', array('plugin' => 'MaxAuth', 'controller' => 'admin', 'action' => 'groups')); ?></li>
        <li><?php echo $this->Html->link('Logout', array('plugin' => 'MaxAuth', 'controller' => 'users', 'action' => 'logout')); ?></li>
    </ul>
</div>

<div id="content">
<?php
    echo $this->Session->flash();
    echo $content_for_layout;
?>
</div>

<div id="footer">MaxAuth &copy; 2012</div>
</div>
</body>
</html>