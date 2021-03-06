<!doctype html>
<!--[if lt IE 8 ]><html lang="en" class="no-js ie ie7 dark"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie dark"><![endif]-->
<!--[if (gt IE 8)|!(IE)]><!--><html lang="en" class="no-js dark"><!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>B.I.M.A</title>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Combined stylesheets load -->
	<link href="<?=base_url()?>assets/constellation/assets/css/mini74d5.css?files=reset,common,form,standard,special-pages" rel="stylesheet" type="text/css">
	
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>constellation/favicon.ico">
	<link rel="icon" type="image/png" href="<?=base_url()?>constellation/favicon-large.png">
	
	<!-- Modernizr for support detection, all javascript libs are moved right above </body> for better performance -->
	<script src="<?=base_url()?>assets/constellation/assets/js/libs/modernizr.custom.min.js"></script>
	
	<!--
	
	This file is from the demo website of the Constellation Admin Skin
	
	If you like it and wish to use it, please consider buying it on ThemeForest:
	http://themeforest.net/item/constellation-complete-admin-skin/116461
	
	Thanks!
	
	-->
	
</head>

<!-- the 'special-page' class is only an identifier for scripts -->
<body class="special-page login-bg dark">
<!-- The template uses conditional comments to add wrappers div for ie8 and ie7 - just add .ie, .ie7 or .ie6 prefix to your css selectors when needed -->
<!--[if lt IE 9]><div class="ie"><![endif]-->
<!--[if lt IE 8]><div class="ie7"><![endif]-->

	<section id="message">
	
		<div class="block-border"><div class="block-content no-title dark-bg">
		
		<center>
			<img src="<?=base_url()?>assets/constellation/assets/images/bijak.png" width="60" height="60">
			</center>
			<p align="center"><b>PT. BINTANG JASA ARTHA KELOLA</b><br>BIJAK INTEGRATED MONITORING APPLICATION  
			
			</p>
		</div></div>
	</section>
	
	<section id="login-block">
		<div class="block-border"><div class="block-content">
			
			<!--
			IE7 compatibility: if you want to remove the <h1>,
			add style="zoom:1" to the above .block-content div
			-->
			<h1>Login System</h1>
			<div class="block-header">
			
			</div>
				
			<form class="form with-margin" name="login-form" id="login-form" method="post" action="<?=base_url()?>login/proses">
				<input type="hidden" name="a" id="a" value="send">
				<p class="inline-small-label">
					<label for="login"><span class="big">User name</span></label>
					<input type="text" name="login" id="login" class="full-width" value="">
				</p>
				<p class="inline-small-label">
					<label for="pass"><span class="big">Password</span></label>
					<input type="password" name="pass" id="pass" class="full-width" value="">
				</p>
				
				<button type="submit" class="float-right">Login</button>
				<p class="input-height">
					<input type="checkbox" name="keep-logged" id="keep-logged" value="1" class="mini-switch" checked="checked">
					<label for="keep-logged" class="inline">Keep me logged in</label>
				</p>
			</form>
			
			<form class="form" id="password-recovery" method="post" action="#">
				<fieldset class="grey-bg no-margin collapse">
					<legend><a href="javascript:void(0)">Lost password?</a></legend>
					<p class="input-with-button">
						<label for="recovery-mail">Enter your e-mail address</label>
						<input type="text" name="recovery-mail" id="recovery-mail" value="">
						<button type="button">Send</button>
					</p>
				</fieldset>
			</form>
		</div></div>
		
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
			<p align="center"><b>Copyright &copy <br>PT. BINTANG JASA ARTHA KELOLA</b><br><small style="color:black">BIJAK INTEGRATED MONITORING APPLICATION</small>  <br>
			<?=date("Y")?>
			</p>
	</section>
	
	
	
	
	<!-- Combined JS load -->
	<script src="<?=base_url()?>assets/constellation/assets/js/minif92b.php?files=libs/jquery-1.6.3.min,old-browsers,common,standard,jquery.tip.js"></script>
	<!--[if lte IE 8]><script src="<?=base_url()?>assets/constellation/assets/js/standard.ie.js"></script><![endif]-->
	
	<!-- example login script -->
	<script>
	
		$(document).ready(function()
		{
			// We'll catch form submission to do it in AJAX, but this works also with JS disabled
			$('#login-form').submit(function(event)
			{
				// Stop full page load
				event.preventDefault();
				
				// Check fields
				var login = $('#login').val();
				var pass = $('#pass').val();
				
				if (!login || login.length == 0)
				{
					$('#login-block').removeBlockMessages().blockMessage('Please enter your user name', {type: 'warning'});
				}
				else if (!pass || pass.length == 0)
				{
					$('#login-block').removeBlockMessages().blockMessage('Please enter your password', {type: 'warning'});
				}
				else
				{
					var submitBt = $(this).find('button[type=submit]');
					submitBt.disableBt();
					
					// Target url
					var target = $(this).attr('action');

					if (!target || target == '')
					{
						// Page url without hash
						target = document.location.href.match(/^([^#]+)/)[1];
					}
					
					// Request
					var data = {
						a: $('#a').val(),
						username: login,
						password: pass,
						'keep-logged': $('#keep-logged').attr('checked') ? 1 : 0
					};
					var redirect = $('#redirect');
					if (redirect.length > 0)
					{
						data.redirect = redirect.val();
					}
					
					// console.log(data);
					// Start timer
					var sendTimer = new Date().getTime();
					// Send
					$.ajax({
						url: target,
						dataType: 'json',
						type: 'POST',
						data: data,
						success: function(data, textStatus, XMLHttpRequest)
						{
							console.log(data);
							if (data.valid)
							{
								// Small timer to allow the 'cheking login' message to show when server is too fast
								var receiveTimer = new Date().getTime();
								if (receiveTimer-sendTimer < 500)
								{
									setTimeout(function()
									{
										document.location.href = data.redirect;
										
									}, 500-(receiveTimer-sendTimer));
								}
								else
								{
									document.location.href = data.redirect;
								}
							}
							else
							{
								// Message
								$('#login-block').removeBlockMessages().blockMessage(data.error || 'An unexpected error occured, please try again', {type: 'error'});
								
								submitBt.enableBt();
							}
						},
						error: function(XMLHttpRequest, textStatus, errorThrown)
						{
							// Message
							$('#login-block').removeBlockMessages().blockMessage('Error while contacting server, please try again', {type: 'error'});
							
							submitBt.enableBt();
						}
					});
					
					// Message
					$('#login-block').removeBlockMessages().blockMessage('Please wait, cheking login...', {type: 'loading'});
				}
			});
		});
	
	</script>
	
</html>
