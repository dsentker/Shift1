<?php
/** @var $this \Shift1\Core\View\View */
$this->wrappedBy(self::instance('EchoVar'), 'key');
?>
This is a <?php if($this->has('key')) echo $this->key; ?> test!