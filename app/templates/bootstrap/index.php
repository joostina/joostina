<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php echo joosDocument::head(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link href="<?php echo JTEMPLATE_LIVE ?>css/app.css" rel="stylesheet">
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<script type="text/javascript" src="<?php echo JPATH_SITE ?>/media/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo JTEMPLATE_LIVE ?>js/lib/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo JTEMPLATE_LIVE ?>js/lib/bootstrap-datepicker.js"></script>

        <script type="text/javascript" src="<?php echo JTEMPLATE_LIVE ?>js/app.js"></script>

	</head>

	<body>



		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="<?php echo JPATH_SITE ?>"><?php echo joosConfig::get2('info', 'title') ?></a>

					<div class="nav-collapse">
						<ul class="nav">
							<li class="active"><a href="#">Home</a></li>
							<li><a href="#about">About</a></li>
							<li><a href="#contact">Contact</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Вёрстка здесь
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo joosRoute::href('layouts', array('tpl' => 'blog_index')) ?>">Блог</a></li>
									<li><a href="<?php echo joosRoute::href('layouts', array('tpl' => 'blog_post')) ?>">Блог/пост</a></li>
                                    <li><a href="<?php echo joosRoute::href('layouts', array('tpl' => 'users_index')) ?>">Пользователи. Список</a></li>
                                    <li><a href="<?php echo joosRoute::href('layouts', array('tpl' => 'users_profile')) ?>">Пользователи. Профиль</a></li>
                                    <li><a href="<?php echo joosRoute::href('layouts', array('tpl' => 'users_profile_edit')) ?>">Пользователи. Редактирование профиля</a></li>
								</ul>
							</li>
						</ul>
					</div>

                    <?php joosModule::module('login', array('template' => 'default')) ?>

				</div>
			</div>
		</div>

		<div class="container">

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

	</body>
</html>





