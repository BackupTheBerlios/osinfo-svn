# Copyright 2006 Mikael Lammentausta
# Distributed under the terms of the GNU General Public License v2
# $Header: $

inherit eutils

DESCRIPTION="osinfo lists system information available to the OS"
HOMEPAGE="http://osinfo.berlios.de"
SRC_URI=""
#SRC_URI="ftp://ftp.berlios.de/pub/osinfo/last"

LICENSE="BSD"
SLOT="0"
KEYWORDS="~x86 ~amd64 ~x86-fbsd"
IUSE="xsl lshw hdparm smartmontools ddc"

DEPEND="xsl? (dev-libs/libxslt)
		lshw? (sys-apps/lshw)
		hdparm? (sys-apps/hdparm)
		ddc? (sys-apps/ddcxinfo-knoppix)
		smartmontools? (sys-apps/smartmontools)
		app-admin/hddtemp
		app-shells/bash
		sys-apps/coreutils
		sys-apps/grep
		sys-apps/sed
		|| (sys-apps/gawk sys-apps/mawk)"

RDEPEND="${DEPEND}"

src_unpack() {

	mkdir ${S} # S is our source dir, where wo copy our source files to
	cp ${FILESDIR}/osinfo.gz ${S}
	gzip -d ${S}/osinfo.gz

}


# src_compile() {}

src_install() {
	
	mkdir ${D}/usr/sbin		# D is like a virtual / where we install our stuff, before emerge merge it with the real /
	cp osinfo ${D}/usr/sbin

	#if use doc; then
	#	mkdir -p ${D}/usr/share/doc/${P}
	#	cp ${FILESDIR}/readme.gz ${D}/usr/share/doc/${P}/
	#fi

}

