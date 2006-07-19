#!/bin/bash

sudo ln -s "$(pwd)/init.d/osinfo.gentoo" /etc/init.d/osinfo
make dist-all-in-one
sudo mv dist/osinfo/osinfo-all-in-one /usr/local/bin/osinfo
sudo ln -s "$(pwd)/osinfo.conf" /etc/
