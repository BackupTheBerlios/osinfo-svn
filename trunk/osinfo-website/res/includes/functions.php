<?php

function print_footer() {
	?>
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
	<?php
}

function print_header($relativeroot, $title) {
	echo "<title>osinfo".$title."</title>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$relativeroot."/res/css/print.css\" media=\"print\" />";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$relativeroot."/res/css/screen.css\" media=\"screen\" />";
}

?>