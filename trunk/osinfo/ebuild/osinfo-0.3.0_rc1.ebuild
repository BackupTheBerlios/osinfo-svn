# Copyright 1999-2006 Gentoo Foundation
# Distributed under the terms of the GNU General Public License v2
# $Header: $

inherit eutils

DESCRIPTION="OSinfo lists system information available to the OS"
HOMEPAGE="http://osinfo.berlios.de"
SRC_URI="ftp://ftp.berlios.de/pub/${PN}/${P}.tar.bz2"

S="${WORKDIR}/${PN}"

LICENSE="BSD"
SLOT="0"
KEYWORDS="~x86 ~amd64"
IUSE="html devices hdd tcp"

DEPEND=""
	# grep 2.5.0 will bug with osinfo
	#>=sys-apps/grep-2.5.1
RDEPEND="sys-devel/bc
		|| ( sys-apps/gawk sys-apps/mawk )
		html? ( dev-libs/libxslt )
		devices? ( sys-apps/lshw
		          sys-apps/pciutils
		          sys-apps/usbutils )
		hdd? ( sys-apps/hdparm
		      app-admin/hddtemp
		      >=sys-apps/smartmontools-5.36 )
		tcp? ( || ( net-analyzer/netcat net-analyzer/netcat6 net-analyzer/gnu-netcat ))"

src_install() {
	dobin osinfo
	doman man/osinfo.1
	dodoc docs/*
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
	elog "These features are still incomplete, not installed by the ebuild,"
	elog "but you can help to improve them!"
	elog
	elog "You can freely add more modules to osinfo. Check the source"
	elog "code inside the tarball. Thank you for interest."
	elog 
}
