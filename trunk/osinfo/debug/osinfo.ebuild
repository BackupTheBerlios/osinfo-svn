# Copyright 2006 Mikael Lammentausta
# Distributed under the terms of the GNU General Public License v2
# $Header: $

DESCRIPTION="osinfo lists system information available to the OS"
HOMEPAGE="http://osinfo.berlios.de"
SRC_URI="ftp://ftp.berlios.de/pub/osinfo/last"

LICENSE="BSD"
SLOT="0"
KEYWORDS="~x86 ~amd64 ~x86-fbsd"
IUSE="xsl lshw hdparm smartmontools ddc"

DEPEND="xsl? (dev-libs/libxslt)
		lshw? (sys-apps/lshw)
		hdparm? (sys-apps/hdparm)
		ddc? (sys-apps/ddcxinfo-knoppix)
		smartmontools? (sys-apps/smartmontools)
		app-shells/bash
		sys-apps/coreutils
		sys-apps/grep
		sys-apps/sed
		sys-apps/gawk || sys-apps/mawk"
RDEPEND="${DEPEND}"

