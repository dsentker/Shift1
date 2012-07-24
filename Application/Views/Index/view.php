<?php
/** @var \Shift1\Core\View\View $view  */

#$view->setParent($view->newInstance('index')->assign('foo', 'This is the text for the "foo" placeholder'));
$view->setParent('index')->foo = 'Ein Test';
#$view->getParent()->foo = 'Ein Test';
?>

<div style="padding: 20px; background: beige">
    <p><strong>Inhalt von Action "View"</strong></p>
    <div style="border: 1px solid #dc143c; padding: 0 10px 10px 10px; background-color: #dcdcdc; ">
        <h3>This is a sub-heading from /index/view.php</h3>
        <p>Testing a paragraph...</p>

        <p>Testing ::has('sub') &raquo; <?php \var_export($view->has('sub')); ?></p>
        <?php if($view->has('sub')) echo $view->get('sub'); ?>
    </div>


    <div style="border: 1px dashed red">
        <p>Böser Content vom User: <input type="text" value="<?= $view->escape('inVal') ?>" /></p>
    </div>


    <a href="<?= $router->getAnchor(array('_controller' => 'index', '_action' => 'getLogo')); ?>">Das ist ein Link baby!</a>

        <hr />

    <?php $mockClass = new \StdClass(); $mockClass->foobar = 'give-me-some-slug-baby'; ?>

    <?php echo $router->getAnchor(array('slug' => 'leerzeichen olee'), 'example') ?><br />
    <?php echo $router->getAnchor(array('_action' => 'someAction'), 'nocontroller') ?><br />
    <?php echo $router->getAnchor(array('_action' => 'someAction', 'qoo' => 'fuxx'), 'nocontroller') ?><br />
    <?php echo $router->getAnchor(array('_controller' => 'Auth', '_action' => 'login', 'param1key' => false)) ?><br />
    <?php echo $router->getAnchor(array('test' => $mockClass, 'param1key' => false), 'paramconvert') ?><br />
    <?php echo $router->getAnchor(array('test' => $mockClass, 'param1key' => false), 'paramconvertoptional') ?><br />
    <?php echo $router->getAnchor(array('_controller' => 'Auth', '_action' => 'login', 'param1key' => 'param1val'), 'doubleAttack') ?><br />
    <?php echo $router->getAnchor(array('_controller' => 'Auth', '_action' => 'login', 'param1key' => 'param1val'), 'shortroute') ?><br />

</div>