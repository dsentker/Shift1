<!DOCTYPE html>
<html>
<head>
    <title>SHIFT1 PHP Framework Test page</title>
</head>
<body>
<h1>index.php Test page</h1>
<p>Hello, World...</p>

<p>Testing $view->foo : <br />
<?php echo $view->foo; ?></p>
<p>This a paragraph between $view->right and $view->content.</p>
<?php echo $view->content; ?>

<p style="border-top: 1px solid #aaa;">This is the last paragraph</p>
</body>
</html>