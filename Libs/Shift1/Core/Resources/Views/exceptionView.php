<?php
/** @var \Shift1\Core\View\View $this */
$this->wrappedBy($this->newSelf('shift1Layout'), 'content');

?>
<h3>Uncaught Exception</h3>
    <h4>"<?php echo \htmlentities($this->e->getMessage()); ?>"</h4>
    <div class="exception-block">
        <p>Uncaught <strong><?php echo \get_class($this->e); ?></strong>, Code <?php echo $this->e->getCode(); ?><br />
            File <strong><?php echo $this->e->getFile(); ?></strong>:</p>
        <code>
            <?php foreach($this->code as $line => $code) : ?>
                <span class="row<?php if($this->e->getLine() == $line) echo ' highlight'; ?>"><span class="line"><?php echo $line; ?></span><?php echo $code; ?></span>
            <?php endforeach; ?>
        </code>
    </div>