<?php
/** @var \Shift1\Core\View\View $this  */

#$this->wrappedBy('index', array('foobaa' => 'lol rofl panic OMG'), 'content');
$this->wrappedBy(self::instance('index')->assignArray(array('foobaa' => 'lol panic OMG', 'right' => self::instance('subpage'))));
?>

<h3>This is a sub-heading</h3>
    <p>And another paragraph....</p>