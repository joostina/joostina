<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Well, Shit. Something went wrong.</title>
    <meta http-equiv="content-language" content="en"/>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link href="assets/css/exception/global.css" rel="stylesheet" type="text/css" charset="utf-8"/>
</head>

<body>
<div class="wrap">
    <h1>Well, Shit. <span class="i">Something</span> went wrong.</h1><br/>

    <h2>What happened?</h2>

    <p>A <span class="b"><?php echo get_class($e); ?></span> exception was thrown with the message "<span
            class="b"><?php echo $e->getMessage(); ?></span>".</p><br/>

    <h2>Where did this happen?</h2>

    <p>In <span class="b"><?php echo $e->getFile(); ?></span> around line <span
            class="b"><?php echo $e->getLine(); ?></span>.</p><br/>

    <h2>Stack Trace or GTFO.</h2>

    <p>Here is a formatted trace.</p>
    <ul class="stack">
        <?php echo $FFFUUUException->getTraceAsFancyHTML(); ?>
    </ul>
    <br/>

    <p>Here is a plain text trace.</p>

    <p class="plain"><?php echo $FFFUUUException->getTraceAsText(); ?></p>
</div>

<div class="ffffuuu"></div>
</body>
</html>