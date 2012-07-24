<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\View\VariableSet\FilteredVariableSet $vars
 * @hasParent('index', 'content')
 */
$vars->setDefaultFilter('escape');
?>
<h2><?= $vars->post->title ?></h2>
<p><?= $vars->post->body ?></p>
<p><?= $vars->filter('escape')->foo ?></p>