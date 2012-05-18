<?php
/** @var \Shift1\Core\View\View $view  */

$view->setParent($view->newInstance('index')->assign('foo', 'This is the text for the "foo" placeholder'));
?>

<div style="border: 1px solid #dc143c; padding-left: 10px; background-color: #dcdcdc; ">
    <h3>This is a sub-heading from /index/view.php</h3>
    <p>Testing a paragraph...</p>

    <p>Testing ::has('sub') &raquo; <?php \var_export($view->has('sub')); ?></p>
    <?php if($view->has('sub')) echo $view->get('subs'); ?>
</div>


<div style="5px dashed grey">
    <p>Böser Content vom User: <input type="text" value="<?= $view->escape('inVal') ?>" /></p>
</div>