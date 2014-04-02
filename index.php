<?php
////////////////////////////////////////////////////////////////////////////////
//    _________         __   __      _____        __________                    
//    \_   ___ \_____  |  | |  |    /     \   ____\______   \______   ____      
//    /    \  \/\__  \ |  | |  |   /  \ /  \_/ __ \|       _/\____ \_/ ___\     
//    \     \____/ __ \|  |_|  |__/    Y    \  ___/|    |   \|  |_> >  \___     
//     \______  (____  /____/____/\____|__  /\___  >____|_  /|   __/ \___  >    
//            \/     \/                   \/     \/       \/ |__|        \/     
// =============================================================================
//          Designed and Developed by Brad Jones <brad @="bjc.id.au" />         
// =============================================================================
////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="no-js ie6"><![endif]--> 
<!--[if IE 7 ]><html class="no-js ie7"><![endif]--> 
<!--[if IE 8 ]><html class="no-js ie8"><![endif]--> 
<!--[if IE 9 ]><html class="no-js ie9"><![endif]--> 
<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js"><!--<![endif]--> 
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>CallMeRpc</title>
		<meta name="robots" content="index, follow">
		<meta name="keywords" content="json, xml, rpc, server, rest">
		<meta name="description" content="A very simple http rpc server, go on callme baby!">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require('assets/min.php'); ?>
		<?php AssetMini::$debug = true; ?>
		<?php AssetMini::css(['bootstrap', 'jumbotron-narrow', 'customisations']); ?>
		<?php AssetMini::js(['modernizr']); ?>
	</head>
	<body>
		<!--[if lt IE 9]>
			<p class="chromeframe">
				You are using an <strong>outdated</strong> browser.
				Please <a href="http://browsehappy.com/">upgrade your browser</a>
				or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a>
				to improve your experience.
			</p>
		<![endif]-->
		
		<div class="container">
			<div class="header"> <!-- <div class="masthead clearfix"> -->
				<img style="width:50px;" class="pull-right img-thumbnail" src="/assets/img/callme-logo.png">
				<h3 class="text-muted">CallMeRpc</h3>
			</div>
			<div class="jumbotron">
				<h1>A very simple http rpc server, go on callme baby!</h1>
				<p class="lead">
					I needed a centerlised REST come RPC HTTP server to help me
					pull various Vendor APIs and other services together.
					This is my take on JSON-RPC...
				</p>
				<p>
					<a class="btn btn-lg btn-primary" role="button" href="#">Download Now</a>
					<a class="btn btn-lg btn-info" role="button" href="#">GitHub Project</a>
				</p>
			</div>
			<div class="row marketing">
				<div class="col-lg-6">
					<h4>What is this?</h4>
					<p>
						This is a HTTP RPC server. You define methods as single namespaced PHP files
						containing a single function or class (some methods might make use of private
						helper methods to achieve their task). You can then call these methods via
						normal GET or POST requests (a standard HTML web form could easily be tied to
						a method).
					</p>
					<p>
						Each PHP function will return an array value which will then be
						transformed into JSON or XML or some other serialisable format.
						Configured as a server wide default and/or per request.
					</p>
					<p>
						POST requests can also be made using JSON or XML instead of POST values.
						In this case whatever format is used for the request will be used for the
						response.
					</p>
					<p>
						There is no set structure or scheme to the RPC methods, this is entirely left
						up to the developers who create the methods.
					</p>
					<p>
						A decriptor file is automatically created, similar to WSDL for SOAP services.
						This file again can be requested in JSON or XML which can then in turn be
						parsed by the remote end to create callable stub methods.
						However the decriptor file when requested by a web browser is transformed
						into a human readable HTML5 page. With testing functionality built in.
					</p>
				</div>
				<div class="col-lg-6">
					<h4>How do I use it?</h4>
					<p>
						Well you can either download the zip file above or
						fork/clone the project on GitHub. Once you have the
						files on a PHP server and the you should be pretty
						much right to go.
					</p>
					<p>TODO: More instructions here</p>
				</div>
			</div>
			<div class="row marketing">
				<h4>Making Contributions</h4>
				<p>
					This project is first and foremost a tool to help me create
					awsome websites. Thus naturally I am going to tailor it for
					my use. I am just a really kind person that has decided to
					share my code so I feel warm and fuzzy inside. Thats what
					Open Source is all about, right :)
				</p>
				<p>
					If you feel like you have some awsome new feature, or have
					found a bug I overlooked I would be more than happy to hear
					from you. Simply create a new issue on the github project,
					including a link to a patch file if you have some changes
					already developed and I will consider your request.
				</p>
				<ul>
					<li>If it does not impede on my use of the software.</li>
					<li>If I feel it will benefit us and/or the greater community.</li>
					<li>If you make it easy for me to implement - ie: provide a patch file.</li>
				</ul>
				<p>
					Then the chances are I will include your code.
				</p>
			</div>
			<div class="footer">
				<p>
					&copy <?php date_default_timezone_set('Australia/Melbourne'); echo date('Y'); ?>
					Brad Jones
					- <a href="mailto:brad@bjc.id.au">brad@bjc.id.au</a>
				</p>
			</div>
		</div>
		
		<!-- Load our Javascript -->
		<?php /*AssetMini::js(['jquery','bootstrap','main']);*/ ?>
	</body>
</html>