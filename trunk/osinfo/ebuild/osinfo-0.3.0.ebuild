# Copyright 2006 Mikael Lammentausta
# Distributed under the terms of the GNU General Public License v2
# $Header: $

inherit eutils

DESCRIPTION="osinfo lists system information available to the OS"
HOMEPAGE="http://osinfo.berlios.de"
SRC_URI="ftp://ftp.berlios.de/pub/osinfo/osinfo.tar.bz2"

LICENSE="BSD"
SLOT="0"
KEYWORDS="~x86 ~amd64 ~x86-fbsd"
IUSE="xsl devices hdd ddc dvb"

DEPEND="xsl? (dev-libs/libxslt)
		devices? (sys-apps/lshw
		          sys-apps/pciutils
		          sys-apps/usbutils)
		hdd? (sys-apps/hdparm
		      app-admin/hddtemp
		      >=sys-apps/smartmontools-5.36)
		ddc? (sys-apps/ddcxinfo-knoppix)
		dvb? (media-tv/dvbtune
              media-tv/linuxtv-dvb-apps
              media-video/dvbsnoop)
		app-shells/bash
		sys-apps/coreutils
		>=sys-apps/grep-2.5.1
		sys-apps/sed
		sys-devel/bc
		|| (sys-apps/gawk sys-apps/mawk)"

RDEPEND="${DEPEND}"

src_unpack() {
	mkdir -p ${S} # S is our source dir, where we copy our source files to

	# does not work yet..
	tar xjf ${DISTDIR}/${A} -C ${S}

#	cp ${FILESDIR}/osinfo.gz ${S}
#	gzip -d ${S}/osinfo.gz

}


# src_compile() {}

src_install() {
	
	mkdir -p ${D}/usr/bin		# D is like a virtual / where we install our stuff, before emerge merge it with the real /
	cp osinfo/osinfo ${D}/usr/bin

	doman osinfo/osinfo.1

	#if use doc; then
	#	mkdir -p ${D}/usr/share/doc/${P}
	#	cp ${FILESDIR}/readme.gz ${D}/usr/share/doc/${P}/
	#fi
}

pkg_postinst() {
	einfo
	einfo "Osinfo is still beta; you can help be addressing bugs at"
	einfo "the osinfo mailinglist: osinfo@lists.berlios.de"
	einfo
	einfo "Osinfo has many nice features that are not obvious at first."
	einfo "You can create an HTML document of the computers in your"
	einfo "LAN, run osinfo in daemon mode on a box with Apache server."
	einfo "Send the xml sheet to the daemon with the --tcpsend option."
	einfo
	einfo "You can freely add more modules to osinfo. Check the source"
	einfo "code inside the tarball. Thank you for interest."
	einfo 
}

