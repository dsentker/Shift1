<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\View\VariableSet\VariableSetInterface $vars
 * @hasParent('index', 'content')
 */
$view->setDefaultFilter('escape');
?>
<h2><?= $view->filter($vars->post->title, 'toLower') ?></h2>
<p><?= $vars->post->body ?></p>