<?php
/**
 * @var $vars ...
 * @setParent()
 *
 */
$vars->setDefaultFilter('escape');
?>

<p style="background-color: green; font-weight: bold;">*** IT WORKS ***</p>

    <?= $vars->filter('escape upper')->foo ?>