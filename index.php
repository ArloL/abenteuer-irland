<?php
	require_once 'site/main.php';
	$aPage = getConfig();
?><!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php echo $aPage['content-language'] ?>"> <![endif]-->
<!--[if IE 7]>		<html class="no-js lt-ie9 lt-ie8 ie7" lang="<?php echo $aPage['content-language'] ?>"> <![endif]-->
<!--[if IE 8]>		<html class="no-js lt-ie9" lang="<?php echo $aPage['content-language'] ?>"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $aPage['content-language'] ?>"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
			 More info: h5bp.com/i/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $aPage['title'] ?></title>
	<meta http-equiv="Content-Language" content="<?php echo $aPage['content-language'] ?>" />
	<meta name="description" content="<?php echo $aPage['description']; ?>" />
	<meta name="author" content="Mary O'Keeffe">

	<!-- Mobile viewport optimized: h5bp.com/viewport -->
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<base href="<?php echo $aPage['base']; ?>">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

	<!-- CSS: implied media=all -->
	<!-- CSS concatenated and minified via ant build script-->
	<link rel="stylesheet" href="css/style.css">
	<!-- end CSS-->

	<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

	<!-- All JavaScript at the bottom, except this Modernizr build.
			 Modernizr enables HTML5 elements & feature detects for optimal performance.
			 Create your own custom Modernizr build: www.modernizr.com/download/ -->
	<script src="js/libs/modernizr.custom.82050.js"></script>
</head>

<body id="<?php echo $aPage['css-id']?>">
	<div id="top">
	</div>
	<div id="center">
		<div id="container">
			<header>
				<a class="logo ir" href="." title="Abenteuer Irland">Abenteuer Irland</a>
				<div id="telBox">
					<p>
						<a href="mailto:mok@abenteuer-irland.de">mok@abenteuer-irland.de</a><br />
						+49 2871 3108999
					</p>
				</div>
				<div id="language">
					<p>
					<?php if($aPage['content-language'] !== 'de') { ?>
					<a href="<?php echo getTranslation($aPage, 'de') ?>"><img width="30" height="18" src="img/german.png" alt="German"></a>
					<?php } ?>
					<?php if($aPage['content-language'] !== 'en') { ?>
					<a href="<?php echo getTranslation($aPage, 'en'); ?>"><img width="30" height="15" src="img/english.png" alt="English"></a>
					<?php }?>
					</p>
				</div>
				<nav>
					<?php echo getContent('menu', $aPage['content-language']); ?>
				</nav>
			</header>
			<div id="content">
				<div id="main" role="main">
					<?php echo getContent($aPage['content']); ?>
				</div>
			</div>
			<footer>
				<?php echo getContent('footer', $aPage['content-language']); ?>
			</footer>
		</div> <!--! end of #container -->
	</div> <!--! end of #center -->

	<!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID.
       mathiasbynens.be/notes/async-analytics-snippet -->
	<script>
	    var _gaq=[['_setAccount','<?php echo $aPage['analytics-id']?>'],['_trackPageview']];
	    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	    s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
</body>
</html>
