<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\VariableSet\VariableSetInterface $vars
 * @renderedByController
 * @hasParent('index', 'content')
 */
$view->addDefaultFilter('escape');
?>
<p>Hello, it's me, a sidebar!</p>

<p><?= $view->filter($vars->foo) ?></p>

<p>Data from action page::sidebar: <?= $vars->data ?></p>
