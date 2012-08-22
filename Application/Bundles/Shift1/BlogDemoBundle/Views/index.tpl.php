<?php
/**
 * @var \Shift1\Core\View\View $view
 * @var \Shift1\Core\VariableSet\VariableSetInterface $vars
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sample Blog</title>
    <style>
        body {
            background-color: #eee;
            font-family: Arial;
            font-size: 11px;
            color: #444;
        }
        .wrapper {
            width: 760px;
            margin: 0 auto;
            background: #fff none;
            padding: 20px;
            box-shadow: 0 0 4px #aaa;
            overflow: hidden;
        }

        #content {
            float: left;
            width: 520px;
            margin-right: 20px;
        }

        #sidebar  {
            float: left;
            width: 210px;
            padding: 5px;
            background-color: #f4f4f4;
        }

        footer {
            border-top: 1px solid #ccc;
            margin-top: 30px;
            padding-top: 30px;
            clear: both;
        }

    </style>
</head>

<body>

    <div class="wrapper">

        <h1>A sample blog</h1>

        <div id="content">
            <?php echo $view->slot('content') ?>
        </div>

        <div id="sidebar">
            <?php echo $view->renderTemplate('shift1:blogDemo:page/sidebar') ?>
        </div>

        <footer>
            <?php if($vars->has('params')) : ?>
            <pre>
                <?php print_r($vars->params) ?>
            </pre>
            <?php endif; ?>
        </footer>

    </div>
</body>
</html>