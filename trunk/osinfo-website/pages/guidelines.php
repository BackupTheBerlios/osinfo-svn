<?php
if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
  header("Content-type: application/xhtml+xml");
}
else {
  header("Content-type: text/html");
}
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; ?>
<?php 
	require_once("../res/includes/menu.php");
	require_once("../res/includes/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>osinfo - Coding guidelines</title>
	<link rel="stylesheet" type="text/css" href="../res/css/print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="../res/css/screen.css" media="screen" />
</head>

<body>
<?php
	print_menu("..", "/pages/guidelines.php", "Coding guidelines");
?>
	<div class="text">
		<h2>Some coding guidelines:</h2>
		<ul>
			<li>Put documentation comments of a function right before it. Not inside it.</li>
			<li>Avoid UUOC (Useless Use of Cat (cat foo | grep bar). See <a href="http://www.ruhr.de/home/smallo/award.html">http://www.ruhr.de/home/smallo/award.html</a>).</li>
			<li>If we fake results because of debugging: Do it only if <tt>$isdebug</tt> is <tt>1</tt>.</li>
		</ul>
	</div>
	<?php 
		print_footer();
	?>
</body>
</html>

