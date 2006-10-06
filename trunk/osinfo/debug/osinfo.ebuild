# Copyright 1999-2006 Gentoo Foundation
# Distributed under the terms of the GNU General Public License v2
# $Header: $

DESCRIPTION="osinfo lists system information available to the OS"
HOMEPAGE="http://osinfo.berlios.de"
SRC_URI="ftp://ftp.berlios.de/pub/osinfo/last"

LICENSE="BSD"
SLOT="0"
KEYWORDS="~x86 ~amd64 ~x86-fbsd"
IUSE="xsl lshw hdparm"

DEPEND="xsl? (dev-libs/libxslt)
		lshw? (sys-apps/lshw)
		hdparm? (sys-apps/hdparm)
		app-shells/bash"
RDEPEND="${DEPEND}"

