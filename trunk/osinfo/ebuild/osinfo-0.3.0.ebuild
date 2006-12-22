# Copyright 1999-2006 Gentoo Foundation
# Distributed under the terms of the GNU General Public License v2
# $Header: $

inherit eutils

DESCRIPTION="OSinfo lists system information available to the OS"
HOMEPAGE="http://osinfo.berlios.de"
SRC_URI="ftp://ftp.berlios.de/pub/osinfo/osinfo.tar.bz2"

LICENSE="BSD"
SLOT="0"
KEYWORDS="~x86 ~amd64"
IUSE="xsl devices hdd tcp" #add later: dvb ddc

DEPEND=""
RDEPEND="xsl? (dev-libs/libxslt)
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
		tcp? || (net-analyzer/netcat net-analyzer/netcat6
		net-analyzer/gnu-netcat)
		|| (sys-apps/gawk sys-apps/mawk)"


src_install() {
	S="${WORKDIR}/${PN}"
	dobin ${S}/osinfo
	doman ${S}/osinfo.1
}

pkg_postinst() {
	elog
	elog "Osinfo is still beta; you can help be addressing bugs at"
	elog "the osinfo mailinglist: osinfo@lists.berlios.de"
	elog
	elog "Osinfo has many nice features that are not obvious at first."
	elog "You can create an HTML document of the computers in your"
	elog "LAN, and run osinfo in daemon mode on a box with Apache server."
	elog "Send the xml sheet to the daemon with the --tcpsend option."
	elog "These features are still incomplete, but you can help to improve them!"
	elog
	elog "You can freely add more modules to osinfo. Check the source"
	elog "code inside the tarball. Thank you for interest."
	elog 
}
