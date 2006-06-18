<?php

/*
Prints a menuitem.
Parameters:
$prefix       Text to put before the item
$title        The text of the link
$relativeroot A string like "../.." that is the relative path to the root level.
$url          The path to the link target from the root level.
$me           The path to the file this menuitem will be used in.
$islast       If true this is the last menuitem.
Returns:
Nothing
*/
function print_menuitem($prefix, $title, $relativeroot, $url, &$me, $islast) {
	// Is this a link to the file this item will be used in?
	if ($me == $url) {
		// If true: Print $title bold.
		echo $prefix."<strong>".$title."</strong>";
	} else {
		// If false: Print a link.
		echo $prefix."<a href=\"".$relativeroot.$url."\">".$title."</a>";
	}

	// No linebreak if this is the last item.
	if (! $islast)
		echo "<br />";
}


/*
Prints the title and the menu.
Parameters:
 $relativeroot A string like "../.." that is the relative path to the root level.
 $me           The path to the file this menu will be used in.
Returns:
  Nothing
*/
function print_menu($relativeroot, $me, $title) {
	?>
		<h1 class="siteheader">
			<a name="top" id="top" />osinfo
		</h1>
		<hr class="hide" />
		<div class="menu">
			<p><strong>Menu</strong><br />
				<?php
					$bullet="<img src=\"".$relativeroot."/res/images/bullet.gif\" alt=\"-\" />";
					$lev1="&nbsp;&nbsp;".$bullet."&nbsp;";
					$lev2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$bullet."&nbsp;";

					print_menuitem("", "Home",
					               $relativeroot, "/index.php",                                                      $me, false);
					print_menuitem("",            "Project page",
					               "",            "http://developer.berlios.de/projects/osinfo/",                    $me, false);
					print_menuitem("",            "Browse the SVN Tree",
					               "",            "http://svn.berlios.de/wsvn/osinfo/",                              $me, false);
					print_menuitem("",            "Downloads",
					               "",            "http://developer.berlios.de/project/showfiles.php?group_id=6372", $me, false);
					print_menuitem("",            "Coding guidelines",
					               $relativeroot, "/pages/guidelines.php",                                           $me, true);
			?>
		</p>
		<a href="http://developer.berlios.de" title="BerliOS Developer">
			<img src="http://developer.berlios.de/bslogo.php?group_id=6372" class="berliosimage" alt="BerliOS Developer Logo" />
		</a>
		</div>
	<?php
}
?>
