<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\View\VariableSet\VariableSetInterface $vars
 * @hasParent('index', 'content')
 * @renderedByController
 */
$view->addDefaultFilter('escape');
$view->getVariableSet()->add('foo', 'bar');
?>
<h2><?= $view->filter($vars->post->title, 'toLower') ?></h2>
<?= $view->renderTemplate('post/post-meta') ?>
<p><?= $vars->post->body ?></p>

