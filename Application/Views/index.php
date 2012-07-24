<!DOCTYPE html>
<html>
<head>
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
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <div id="content">
            <?= $view->slot('content') ?>
        </div>

    </div>
</body>
</html>