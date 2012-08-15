<?php
/**
 * @var \Shift1\Core\View\ViewInterface $view
 * @var \Shift1\Core\VariableSet\VariableSetInterface $vars
 *
 *
 */
$view->addDefaultFilter('escape');
?>
<div class="post-meta" style="float: left; width: 80px; font-size: 10px; margin: 0 5px 0 0; background: #eee none">
    Autor: <?= $vars->post->author; ?> (<?= $view->filter($vars->foo) ?>)
</div>