<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\View\VariableSet\VariableSetInterface $vars
 * @renderedByController()
 * @__reeenderedByController('a/b/::c, 'a')
 * @__haasParent('index', 'content')
 */
$view->addDefaultFilter('escape');
?>
<p>Hello, it's me, a sidebar!</p>

<p><?= $view->filter($vars->foo, 'escape') ?></p>
<p>Data from action page::sidebar: <?= $vars->data ?></p>
