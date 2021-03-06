<?php
/** @var \Shift1\Core\View\View $view */
$view->setParent('Libs/Shift1/Core/Resources/Views/shift1Layout', 'content', false)->assign('pageTitle', 'Exception');

?>
<h3>Uncaught Exception</h3>
    <h4><?php echo $view->e->getMessage(); ?></h4>
    <div class="exception-block">
        <p>Uncaught <strong><?php echo \get_class($view->e); ?></strong>, Code <?php echo $view->e->getCode(); ?> in <br />
        File <strong><?php echo \str_replace(BASEPATH, '', $view->e->getFile()); ?></strong>:</p>
        <code>
        <?php foreach($view->code as $line => $code) : ?>
                <span class="row<?php if($view->e->getLine() === $line) echo ' highlight'; ?>"><span class="line"><?php echo $line; ?></span><span class="lineText"><?php echo $code; ?></span></span>
            <?php endforeach; ?>
        </code>

    <h5>Strack Trace</h5>
        <div class="fixed-width"><?php foreach($view->e->getTrace() as $ct => $trace) :
            $args = array();
            foreach($trace['args'] as $arg) {
                if(\is_array($arg)) {
                    $args[] = 'array('.\count($arg).' keys)';
                } elseif (\is_object($arg)) {
                    $args[] = \get_class($arg);
                } else {
                    $args[] = '\'' . (string) $arg . '\'';
                }
            }

            $class =    (empty($trace['class']))    ? ''            : '\\' . $trace['class'];
            $type  =    (empty($trace['type']))     ? ''            : $trace['type'];
            $file =     (empty($trace['file']))     ? '(unknown)'   : $trace['file'];
            $line =     (empty($trace['line']))     ? ''            : '(' . $trace['line'] .')';
            $function = (empty($trace['function'])) ? ''            : $trace['function'];

            echo '<span class="row"><strong>#' . $ct . '</strong> ' . $class . $type . $function . '(' . \implode(', ', $args) . ')
                        <span class="hidden-mouseover">' . \str_replace(BASEPATH, '', $file) .' ' . $line . '</span>
                  </span>';
        endforeach; ?>
        </div>
    </div>