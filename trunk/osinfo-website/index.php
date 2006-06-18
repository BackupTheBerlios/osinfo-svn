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
	require_once("res/includes/menu.php");
	require_once("res/includes/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php
		print_header(".", "");
	?>
</head>

<body>
<?php
	print_menu(".", "/index.php", "");

?>
	<div class="text">
		<p class="medium">Osinfo is a script for showing information about Linux and BSD systems. It is written in bash. Osinfo supports both colour coded console output and exporting to XML.</p>
		<p class="medium">Where it is possible it only uses standard *nix/POSIX tools. Some information is unavailable (but quite common) packages, but the availability of these are tested, and no error messaged are produced if these rarer tools are missing.</p>
	</div>
	<?php 
		print_footer();
	?>
</body>
</html>
