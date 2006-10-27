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
				</style>
            </head>
			<body>
                <xsl:apply-templates select="script" />
			</body>
        </html>
    </xsl:template>


	<!-- computer -->
    <xsl:template match="script">

		<xsl:call-template name="titlebar"/>

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
						osinfo <xsl:apply-templates select="../script/@version"/>
						<xsl:apply-templates select="../scanning"/>
					</font>
				</td>
				</tr>
			</table>
		</div>

    </xsl:template>

    <xsl:template match="osinfov">
		<!-- osinfo version -->
    </xsl:template>

	<!-- hostname -->
    <xsl:template match="client">
        <h1><xsl:value-of select="@hostname"/></h1>
    </xsl:template>


	<!-- scanning date -->
    <xsl:template match="scanning">
		<p>Scanning date <xsl:value-of select="@date"/></p>
    </xsl:template>



	<!-- modules -->
    <xsl:template match="disk_information">
		<td>Disk</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

    <xsl:template match="distribution">
		<td>Distro</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

    <xsl:template match="kernel">
		<td>Kernel</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

    <xsl:template match="system">
		<td>
			<font size="+1">
				Core system
			</font>
			<hr/>
		</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="processor">
		<td>CPU</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="users">
		<td>Users</td>
        <xsl:apply-templates select="user"/>
	        <xsl:value-of select="@unixname"/>
    </xsl:template>

	<xsl:template match="devices">
		<td>Devices</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="terminal">
		<td>Terminal</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="network_information">
		<td>Network</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="applications">
		<td>Applications</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="dmi_information">
		<td>OEM</td>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>


	<xsl:template match="services">
		<td> Services</td>
		<!-- fixme; user for-each loop -->
		<table border="0" cellpadding="0">
			<tr>
			<td>
			<xsl:value-of select="value"/>
			</td>
			</tr>
		</table>
    </xsl:template>



    <!--Table headers and outline-->
    <xsl:template match="attribute">
		<tr>
			<td>
				<xsl:value-of select="description"/>
			</td>
			<td>
				<xsl:value-of select="value"/>
			</td>
		</tr>
    </xsl:template>

    <xsl:template match="user">
		<table border="0" cellpadding="0">
			<tr>
			<td>
	        <xsl:value-of select="@unixname"/>
			</td>
			<td>
			<xsl:value-of select="value"/>
			</td>
			</tr>
		</table>
    </xsl:template>
</xsl:stylesheet> 
