<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\VariableSet\VariableSetInterface $vars
 * @hasParent('shift1:blogDemo:index', 'content')
 * @renderedByController
 */
$view->addDefaultFilter('escape');
//$view->getVariableSet()->add('foo', 'bar');
?>
<h2><?= $view->filter($vars->post->title, 'toLower') ?></h2>
<?= $view->renderTemplate('shift1:blogDemo:post/post-meta') ?>
<p><?= $vars->post->body ?></p>

