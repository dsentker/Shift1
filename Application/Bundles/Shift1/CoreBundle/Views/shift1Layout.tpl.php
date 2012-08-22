<?php
/**
 * @var $view \Shift1\Core\View\View
 * @var $vars \Shift1\Core\VariableSet\VariableSet
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo (isset($pageTitle)) ? $pageTitle : 'Message' ?> &lt; Shift1 PHP Framework</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta charset="utf-8" />
    <style>
        body {
            background: #f4f0f8;
            font: 13px Arial, sans-serif;
            color: #444048;
        }

        .wrapper {
            width: 90%;
            min-width: 960px;
            max-width: 1400px;
            padding: 20px;
            border: 1px solid #ccc;
            background: #fff;
            margin: 20px auto;
            box-shadow: 0 0 2px rgba(128,110,146, .125);
            border-radius: 3px;
        }

        p {
            line-height: 155%;
        }

        b,
        strong {
            font-weight: bold;
            color: #0099cc;
        }

        pre,
        code,
        .fixed-width {
            display: block;
            font-family: Consolas, Andale Mono, Lucida Console, Lucida Sans Typewriter, DejaVu Sans Mono, Bitstream Vera Sans Mono, Liberation Mono, Nimbus Mono L, Monaco, Courier New, Courier;
            font-size: 12px;
        }

        code span.row {
            padding: 2px 0;
            display: block;
            white-space: pre-wrap;
            overflow: hidden;
        }

        code span.row span {
            display: inline-block;
            float: left;
        }

        code span.line {
            width: 40px;
            padding-right: 20px;
            text-align: right;
            color: #888;
        }

        code span.lineText {
            color: #000;
            width: 900px;
        }

        span.row:nth-child(odd) {
            background-color: #f3f3f3;
        }

        .fixed-width span.row {
            display: block;
            line-height: 20px;
        }

        .hidden-mouseover {
            visibility: hidden;
            color: #0099cc;
        }
        
        span:hover .hidden-mouseover {
            visibility: visible;
        }

        .highlight {
            background-color: #ffffcc !important;
            font-weight: bold;
        }

        h1 {
            font-size: 10px;
            color: #777;
            text-align: right;
            font-weight: normal;
        }

        h3 {
            font-size: 26px;
            color: #999;
            margin: 8px 0;
        }

        h4 {
            font-size: 18px;
            margin: 16px 0;
            font-style: italic;

        }

        h5 {
            font-weight: bold;
            font-size: 15px;
            margin: 16px 0 4px 0;
        }

    </style>

</head>
<body>

    <div class="wrapper">

        <h1><?php echo (isset($view->pageTitle)) ? $view->pageTitle : 'Message' ?> - SHIFT 1 Framework</h1>

        <?php echo $view->slot('content'); ?>

    </div>

</body>
</html>