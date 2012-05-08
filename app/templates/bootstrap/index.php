<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//время изменения скомпилированного файла CSS
$css_cache = joosFile::get_modified_date(JTEMPLATE_BASE . DS . 'styles'. DS . 'app' . DS . '_app.css');

//текущий роут
$page = joosController::$activroute;

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php echo joosDocument::head(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php if(joosConfig::get('debug_template')):?>
            <link href="<?php echo JTEMPLATE_LIVE ?>/styles/app/_app.less" rel="stylesheet/less" type="text/css" >
        <?php else: ?>
            <link href="<?php echo JTEMPLATE_LIVE ?>/styles/app/_app.css?ver=<?php echo $css_cache ?>" rel="stylesheet">
        <?php endif;?>


		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
        <script type="text/javascript" src="<?php echo JTEMPLATE_LIVE ?>/js/lib/html5.js"></script>
		<![endif]-->
	</head>


    <body class="<?php echo ($page == 'default') ? 'body-mainpage' : 'body-inside' ?> <?php echo $page?>">

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="<?php echo JPATH_SITE ?>"><?php echo joosConfig::get2('info', 'title') ?></a>

					<div class="nav-collapse">
                        <?php echo joosModule::execute('menu') ?>
					</div>

                    <?php echo joosModule::execute('login') ?>

				</div>
			</div>
		</div>

		<div class="container">

            <?php echo joosModule::load_by_position('top');?>

			<?php if (joosController::$activroute == 'default'): ?>
				<div class="hero-unit">
					<h1>Hello, world!</h1>

					<p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting
						pieces of content. Use it as a starting point to create something more unique.
					</p>
					<p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
				</div>
			<?php endif; ?>

			<div class="content">
				<?php echo joosDocument::body(); ?>
			</div>

			<hr>

			<footer>
				<p>&copy; Company 2012</p>
			</footer>

		</div>

        <div id="modal-output"></div>

    <?php

        joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/jquery.js', array('first' => true));

        if(joosConfig::get('debug_template')){
            joosDocument::instance()->add_js_file(JTEMPLATE_LIVE . '/js/lib/less-1.3.0.min.js');
        }

        joosDocument::instance()
                //http://twitter.github.com/bootstrap/javascript.html
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-transition.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-alert.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-modal.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-dropdown.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-scrollspy.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-tab.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-tooltip.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-popover.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-button.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-collapse.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-carousel.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-typeahead.js')
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-typeahead.js')

                //http://www.eyecon.ro/bootstrap-datepicker/
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/bootstrap/bootstrap-datepicker.js')

                //jQuery Noty Plugin v1.1.1 https://github.com/needim/noty
                ->add_js_file(JTEMPLATE_LIVE . '/js/plugins/jquery.plugins/jquery.noty.js')

                ->add_js_file(JTEMPLATE_LIVE . '/js/app.js')
        ;

        echo joosDocument::javascript();
        ?>
	</body>
</html>