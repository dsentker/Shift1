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
            width: 960px;
            padding: 10px 20px;
            border: 1px solid #ccc;
            background: #fff;
            margin: 20px auto;
            box-shadow: 0 0 2px rgba(128,110,146, .125);
            border-radius: 8px;
        }

        p {
            line-height: 155%;
        }

        b, strong {
            font-weight: bold;
            color: #0099cc;
        }

        pre, code {
            font-size: 14px;
        }

        code {
            display: block;
            font-family: Consolas, Andale Mono, Lucida Console, Lucida Sans Typewriter, DejaVu Sans Mono, Bitstream Vera Sans Mono, Liberation Mono, Nimbus Mono L, Monaco, Courier New, Courier;
        }

        code span.row {
            padding: 2px 0;
            display: block;
            white-space: pre-wrap;
            font-size: 11px;
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

        code span.row:nth-child(odd) {
            background-color: #f3f3f3;
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
        
    </style>

</head>
<body>

    <div class="wrapper">

        <h1><?php echo (isset($pageTitle)) ? $pageTitle : 'Message' ?> - SHIFT 1 Framework</h1>

        <?php echo $this->content; ?>
    </div>

</body>
</html>