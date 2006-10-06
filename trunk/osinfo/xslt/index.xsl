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
					h3          { font-size: 8px }
                    td, th      { width: 40%; border: 0px solid silver; padding: 0px }
                    td:first-child, th:first-child  { width: 30% } 

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
                <xsl:apply-templates select="computer"/>
            </body>
        </html>
    </xsl:template>


	<!-- profile -->
	<xsl:template name="profile" match="computer">

		<xsl:variable name="profile" select="@profile"/>

		<!-- desktop -->
		<xsl:if test="contains($profile,'desktop')">

			<xsl:call-template name="titlebar"/>
			<xsl:call-template name="core"/>
			
		</xsl:if>

		<!-- server -->
		<xsl:if test="contains($profile,'server')">
			
			<xsl:call-template name="titlebar"/>
			<xsl:call-template name="core"/>

		</xsl:if>

		<!-- laptop -->
		<xsl:if test="contains($profile,'laptop')">

			<xsl:call-template name="titlebar"/>
			<xsl:call-template name="core"/>
			
		</xsl:if>

    </xsl:template>



	<xsl:template name="titlebar">

		<div style="padding: 12px; padding-width: 100%; background-color: silver;">
			<table width="100%" >
				<tr>
				<td>
					<font color="#000000" face="Times New Roman" size="+3" >
						<xsl:value-of select="../computer/@hostname"/>
					</font>						
				</td>
				<td align="right">
					<font color="#000000" face="Times New Roman" size="-1" >
						<xsl:apply-templates select="../osinfo"/>
						<xsl:apply-templates select="../scanning"/>
					</font>
				</td>
				</tr>
			</table>
		</div>

    </xsl:template>


	<xsl:template name="core">

		<div style="padding: 12px; padding-width: 100%; background-color: white;">

			<table border="0">
				<tbody>
					<xsl:apply-templates select="../system"/>
					<xsl:apply-templates select="../ip"/>
					<xsl:apply-templates select="../processor"/>
					<xsl:apply-templates select="../system_memory"/>
					<xsl:apply-templates select="../disk_information"/>
				</tbody>
			</table>

		</div>

    </xsl:template>



	<!-- osinfo version  -->
    <xsl:template name="osinfo" match="osinfo">
		osinfo version <xsl:value-of select="@version"/><br/>
    </xsl:template>

	<!-- scanning date --> 
    <xsl:template match="scanning">
		Scanning date <xsl:value-of select="@date"/>
    </xsl:template>



	<!-- modules -->

	<!--
    <xsl:template name="distro" match="distribution">
	<div id="wrapper">
		<p><a onclick="switchMenu('myvar');" title="Switch the Menu">Switch it now</a></p>

		<div id="myvar">
		<h2>Distribution</h2>
			<xsl:apply-templates select="attribute"/>
		</div>
	</div>
    </xsl:template>
	-->


    <xsl:template match="system">
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="processor">
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template name="memory" match="system_memory">
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template name="network" match="network_information/computer/iface">
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template name="ip" match="ip">
		<tr>
			<td> IPv4 address </td>
			<td>
				<xsl:apply-templates select="@v4"/>
			</td>
		</tr>
    </xsl:template>

	
	<xsl:template name="disk" match="disk_information">
        <xsl:apply-templates select="attribute"/>
    </xsl:template>


    <!--Table headers and outline-->
    <xsl:template match="attribute">
			<tr>
				<td> <xsl:value-of select="description"/> </td>
				<td> <xsl:value-of select="value"/> </td>
			</tr>
    </xsl:template>


</xsl:stylesheet> 
