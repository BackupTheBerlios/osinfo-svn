#!/bin/bash
# 1 : Username
user="$1"
[ ! "$user" ] && echo "Please give username" && exit 1
/usr/bin/rsync --progress -t -e "ssh -p 22" dist/osinfo*tar.bz2 "$user"@shell.berlios.de:/home/groups/ftp/pub/osinfo/
[ $? -eq 1 ] && echo "Run this script from osinfo root" && exit 1
