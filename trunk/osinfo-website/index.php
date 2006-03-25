<?php
if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
  header("Content-type: application/xhtml+xml");
}
else {
  header("Content-type: text/html");
}
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>osinfo</title>
	<link rel="stylesheet" type="text/css" href="./res/css/print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="./res/css/screen.css" media="screen" />
</head>

<body>
	<h1 class="siteheader">
		<a name="top" id="top" />osinfo
	</h1>
	<hr class="hide" />
	<div class="menu">
		<p><strong>Menu</strong><br />
			<a href="http://developer.berlios.de/projects/osinfo/">Project page</a><br />
			<a href="http://svn.berlios.de/wsvn/osinfo/">Browse the SVN Tree</a><br />
			<a href="http://developer.berlios.de/project/showfiles.php?group_id=6372">Downloads</a>
		</p>
		<a href="http://developer.berlios.de" title="BerliOS Developer">
			<img src="http://developer.berlios.de/bslogo.php?group_id=6372" class="berliosimage" alt="BerliOS Developer Logo" />
		</a>
	</div>
	<div class="text">
		<p class="medium">osinfo is a script for showing information about Linux systems. It is written in bash. Osinfo will support exporting to XML.</p>
	</div>
	<hr class="hide" />
	<div class="center">
		<p>
			<a href="http://validator.w3.org/check?uri=referer" rel="nofollow">
			<img class="footerimage" src="http://www.w3.org/Icons/valid-xhtml10.gif" alt="Valid XHTML 1.0 Strict" />
			</a> <a href="http://jigsaw.w3.org/css-validator/">
			<img class="footerimage" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" /></a>
		</p>
		<hr />
		<p class="footer">&copy; Arvid Norlander 2006</p>
	</div>
</body>
</html>

