#!/bin/bash

sudo ln -s "$(pwd)/init.d/osinfo.gentoo" /etc/init.d/osinfo
make clean
make dist
sudo mv dist/osinfo/osinfo /usr/local/bin/osinfo
sudo ln -s "$(pwd)/osinfo.conf" /etc/
sudo ln -s "$(pwd)/man/osinfo.1" /usr/share/man/man1/
sudo ln -s "$(pwd)/html" /var/www/localhost/htdocs/osinfo
