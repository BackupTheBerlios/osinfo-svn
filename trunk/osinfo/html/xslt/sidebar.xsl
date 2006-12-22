<!-- THIS FILE IS NOT USED IN OSINFO -->

<?xml version="1.0" encoding="UTF-8" ?>


<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/1999/xhtml">
	<xsl:output method="xml" indent="yes"
	            doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
	            doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

	<xsl:variable name="hostdir">html/hosts</xsl:variable>

	<!--XHTML document outline-->
	<xsl:template match="/*">
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>osinfo</title>
				<style type="text/css">
					h1          { padding: 10px; padding-width: 100%; background-color: silver }
					h2          { font-size: 20px }
					td, th      { width: 20%; border: 0px solid silver; padding: 0px }
					td:first-child, th:first-child  { width: 20% }
					table       { width: 70% }


				</style>

				<script type="text/javascript">

				function switchMenu(obj) {
					var el = document.getElementById(obj);
					if ( el.style.display == "none" ) {
						el.style.display = '';
					}
					else {
						el.style.display = 'none';
					}
				}
				//-->
				</script>

			</head>
			<body bgcolor="#FFFFFF" link="#000000" vlink="#000000" text="#000000">

				<div style="position:absolute;top:0px;left:0px;width:100%:height:100%;">

					<!-- overview -->
					<font color="#000000" face="Times New Roman" size="+3">
						<p align="center">osinfo</p>
					</font>

					<hr/>
					<xsl:text><!--computers--></xsl:text>
					<div style="position:relative;top:0px;left:10px;right:10px;width:100%:height:95%;">

							<xsl:apply-templates select="//desktops|servers|laptops"/>

					</div>

					<hr/>

					<!--index link and date -->
					<div style="position:relative;bottom:5%;left:10px;right:10px;width:100%;height:10%;">
						<p align="center">
							<a href="overview.html" target="main">index view</a>
						</p>

						<font color="#000000" face="Times New Roman" size="-1">
							<xsl:apply-templates select="//scanning" />
						</font>
					</div>
				</div>
			</body>
		</html>
	</xsl:template>



	<xsl:template match="*"/>


	<xsl:template name="version" match="osinfo">
		<!-- osinfo version
		<p>osinfo version <xsl:value-of select="@version"/></p>
		-->
	</xsl:template>


	<!-- profiles  -->
	<xsl:template name="profile" match="desktops|servers|laptops">

		<xsl:for-each select="*/computer" >

			<xsl:variable name="profile" select="@profile"/>

			<h2><xsl:value-of select="$profile"/></h2>

			<table width="97%" border="1" cellpadding="4" cellspacing="5">

				<xsl:if test="contains($profile,'desktop')">
					<xsl:apply-templates select="../computer"/>
				</xsl:if>


				<xsl:if test="contains($profile,'server')">
					<xsl:apply-templates select="../computer"/>
				</xsl:if>


				<xsl:if test="contains($profile,'laptop')">
				</xsl:if>

			</table>

		</xsl:for-each>



		<!--
		-->

	</xsl:template>


	<xsl:template name="computers">
		foobar
	</xsl:template>


	<!-- scanning date -->
	<xsl:template name="date" match="scanning">
		<p>Scanning date <xsl:value-of select="@date"/></p>
	</xsl:template>


	<!--Table headers and outline-->
	<xsl:template match="computer">

		<xsl:param name="hostname" select="@hostname"/>
		<xsl:param name="OS" select="@OS"/>
		<xsl:param name="CPU" select="@CPU"/>

			<tr valign="top">
			<td bgcolor="#FFFFFF">
				<p align="left">
					<font color="#000000" face="Times New Roman" size="+1">
						<a href="{$hostdir}/{$hostname}.xml" target="main">
							<xsl:value-of select="$hostname"/>
						</a>
					</font>
				</p>

				<p align="left">
					<font color="#000000" face="Times New Roman" size="-2">
						<xsl:value-of select="$OS"/>
						<xsl:value-of select="$CPU"/>
					</font>
				</p>
			</td>
			</tr>

	</xsl:template>


</xsl:stylesheet>
