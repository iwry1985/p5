

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="utf-8">
		<link rel="icon" href="<?= base_url('web/img/logo/favicon.ico.png') ?>">

		<!--css-->
		<link href="<?= base_url('web/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= base_url('web/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('default'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('frontend'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('backend'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('accueil'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('fontface'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('responsive'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('animate'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('slick'); ?>" rel="stylesheet" type="text/css">
		<link href="<?= css_url('slick-theme'); ?>" rel="stylesheet" type="text/css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!--Twitter Card data-->
		<meta name="twitter:card" content="summary">
		<meta name="twitter:site" content="@seriesdom">
		<meta name="twitter:title" content="seriesDOM">
		<meta name="twitter:description" content="SeriesDOM : le site des sérievores indécis !">

		<!-- Open Graph data -->
		<link rel="canonical" href="http://seriesdom.com" />
		<meta property="og:title" content="<?= $title ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="http://seriesdom.com">
		<meta property="og:image" content="http://seriesdom.com/web/img/logo/screen.jpg">
		<meta property="og:description" content="SeriesDOM : le site des sérievores indécis !">		
	</head>
	
	<body>
	<div id="wrapper">
	    	<?php require 'default/_nav.php'; ?>	

	    <div id="content">

			<?php echo $content; ?>
	    </div><!--end content-->
	</div><!-- end wrapper-->

		<?php require 'default/_footer.php'; ?>

		<!--js-->
		<script type="text/javascript" src="<?= base_url('web/js/jquery/jquery-min.js'); ?>"></script>
		<script type="text/javascript" src="<?= base_url('web/bootstrap/js/bootstrap.min.js'); ?>"></script>
		<script type="text/javascript" src="<?= js_url('ajax'); ?>"></script>
		<script type="text/javascript" src="<?= js_url('function'); ?>"></script>
		<script type="text/javascript" src="<?= js_url('frontend'); ?>"></script>
		<script type="text/javascript" src="<?= js_url('backend'); ?>"></script>
		<script type="text/javascript" src="<?= js_url('default'); ?>"></script>
		<script type="text/javascript" src="<?= js_url('random_json'); ?>"></script>	
		<script type="text/javascript" src="<?= js_url('slick.min'); ?>"></script>
		<div id="fb-root"></div>
		
	</body>
</html>