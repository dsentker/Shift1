<?php
/** @var \Shift1\Core\View\View $this  */

$this->wrappedBy(self::instance('index')->assignArray(array('right' => 'lol panic OMG')));
?>

<h3>This is a sub-heading</h3>
    <p>And another paragraph....</p>
    <?php if($this->has('sub')) echo $this->sub; ?>