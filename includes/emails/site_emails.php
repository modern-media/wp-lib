<?php
/**
 * @var $body
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Message</title>

	<style type="text/css">
		.ExternalClass {width:100%;}

		.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
			line-height: 100%;
		}

		body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}

		body {margin:0; padding:0;}



		p {margin:0; padding:0; margin-bottom:0;}

		h1, h2, h3, h4, h5, h6 {
			color: black;
			line-height: 100%;
		}

		a, a:link {
			color:#3c96e2;
			text-decoration: underline;
		}

		body, #body_style {
			background:#FFF;
			min-height:1000px;
			color:#000;
			font-family:Arial, Helvetica, sans-serif;
			font-size:14px;
		}

		span.yshortcuts { color:#FFF; background-color:none; border:none;}
		span.yshortcuts:hover,
		span.yshortcuts:active,
		span.yshortcuts:focus {color:#FFF; background-color:none; border:none;}

		a:visited { color: #3c96e2; text-decoration: none}
		a:focus { color: #3c96e2; text-decoration: underline}
		a:hover { color: #3c96e2; text-decoration: underline}

	</style>


</head>
<body style="background:#FFF; min-height:1000px; color:#000;font-family:Arial, Helvetica, sans-serif; font-size:14px"
	  alink="#FF0000" link="#FF0000" bgcolor="#FFFFFF" text="#000000" yahoo="fix">


<div id="body_style">
<?php
echo wpautop($body);
?>
</div>

</body>
</html>
 