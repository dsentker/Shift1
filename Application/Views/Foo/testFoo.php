<?php
/** @var \Shift1\Core\View\View $view  */
?>
<p style="background-color: #ffe4c4;">This is a text part from FooController::fooTest !</p>

<?php if($view->has('insertme')) : ?>
    <div style="float:right;width:100px;border:3px double red; margin: -100px 200px 200px 200px"><?php echo $view->insertme; ?></div>
<?php endif; ?>