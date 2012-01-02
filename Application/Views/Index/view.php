<?php
/** @var \Shift1\Core\View\View $this  */

$this->wrappedBy(self::instance('index')->assign('foo', 'This is the text for the "foo" placeholder'));
?>

<div style="border: 1px solid #dc143c; padding-left: 10px; background-color: #dcdcdc; ">
    <h3>This is a sub-heading from /index/view.php</h3>
    <p>Testing a paragraph...</p>

    <p>Testing ::has('sub') &raquo; <?php \var_export($this->has('sub')); ?></p>
    <?php if($this->has('sub')) echo $this->sub; ?>
</div>