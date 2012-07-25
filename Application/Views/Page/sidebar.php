<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\View\VariableSet\VariableSetInterface $vars
 * @renderedByController()
 * @__reeenderedByController('a/b/::c, 'a')
 * @__haasParent('index', 'content')
 */
?>
<p>Hello, it's me, a sidebar!</p>

<p>Data from action page::sidebar:  <?= $vars->data ?></p>

    <pre><?php print_r($vars->getAll()); ?>