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
                    td, th      { width: 40%; border: 1px solid silver; padding: 10px }
                    td:first-child, th:first-child  { width: 20% } 
                    table       { width: 650px }
                </style>
            </head>
            <body>
                <xsl:apply-templates/>
            </body>
        </html>
    </xsl:template>


	<!-- osinfo version -->
    <xsl:template match="osinfo">
		<p>osinfo version <xsl:value-of select="@version"/></p>
    </xsl:template>
	

	<!-- hostname -->
    <xsl:template match="hostname">
        <h1><xsl:value-of select="@value"/></h1>
    </xsl:template>

	<!-- scanning date -->
    <xsl:template match="scanning_date">
		<p>Scanning date <xsl:value-of select="@value"/></p>
    </xsl:template>
	

	<!-- modules -->
    <xsl:template match="disk_information">
		<h2>Disk</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

    <xsl:template match="distribution">
		<h2>Distro</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

    <xsl:template match="kernel">
		<h2>Kernel</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

    <xsl:template match="system">
		<h2>Core system</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="processor">
		<h2>CPU</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="users">
		<h2>Users</h2>
        <xsl:apply-templates select="user"/>
	        <xsl:value-of select="@unixname"/>
    </xsl:template>

	<xsl:template match="devices">
		<h2>Devices</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="terminal">
		<h2>Terminal</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="network_information">
		<h2>Network</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="applications">
		<h2>Applications</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>

	<xsl:template match="dmi_information">
		<h2>OEM</h2>
        <xsl:apply-templates select="attribute"/>
    </xsl:template>





	<xsl:template match="services">
		<h2>Services</h2>
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
		<table border="0" cellpadding="0">
			<tr>
			<td>
	        <xsl:value-of select="description"/>
			</td>
			<td>
			<xsl:value-of select="value"/>
			</td>
			</tr>
		</table>
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
