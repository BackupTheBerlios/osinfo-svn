<?xml version="1.0" encoding="UTF-8" ?>

<xsl:stylesheet version="1.0" 
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
        xmlns="http://www.w3.org/1999/xhtml">
    <xsl:output method="xml" indent="yes"
        doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
    
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

					<!--
					#wrapper {
					text-align:left;
					margin:0 auto;
					width:500px;
					min-height:100px;
					border:1px solid #ccc;
					padding:30px;
					}

					a {
					color:blue;
					cursor:pointer;
					}

					#myvar {
					border:1px solid #ccc;
					background:#f2f2f2;
					padding:20px;
					}
					-->

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
            <body>
                <xsl:apply-templates/>
            </body>
        </html>
    </xsl:template>


    <xsl:template match="osinfo">
		<!-- osinfo version 
		<p>osinfo version <xsl:value-of select="@version"/></p>
		-->
    </xsl:template>

<!--
<xsl:if test="contains($string,'text')"> 
-->

	<!-- hostname -->
    <xsl:template match="client">
		<h1><xsl:value-of select="@hostname"/></h1>
	</xsl:template>


	<!-- profile 
    <xsl:template match="computer">
        <h2><xsl:value-of select="@profile"/></h2>
    </xsl:template>
	-->

	<!-- scanning date 
    <xsl:template match="scanning">
		<p>Scanning date <xsl:value-of select="@date"/></p>
    </xsl:template>
	-->



	<!-- modules -->
    <xsl:template match="distribution">
	<!--
	<div id="wrapper">
		<p><a onclick="switchMenu('myvar');" title="Switch the Menu">Switch it now</a></p>

		<div id="myvar">
		-->
		<h2>Distribution</h2>
			<xsl:apply-templates select="attribute"/>
	<!--
		</div>
	</div>
	-->
    </xsl:template>




    <xsl:template match="system">
		<h2>Core system</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="processor">
		<h2>CPU</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="*">
		<!-- do not print -->
    </xsl:template>


    <!--Table headers and outline-->
    <xsl:template match="attribute">
		<table>
		<tbody>
			<tr>
				<td> <xsl:value-of select="description"/> </td>
				<td> <xsl:value-of select="value"/> </td>
			</tr>
		</tbody>
		</table>
    </xsl:template>


</xsl:stylesheet> 
