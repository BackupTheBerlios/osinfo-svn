#!/bin/bash
bindir="/usr/local/bin"
vardir="/var/run/osinfo"
sshkey="${1}"

mkdir -p "$vardir"
[ $? -ne 0 ] &&	echo "could not create $vardir"


# look for user osinfo
if [ -e "/etc/passwd" ]; then

	if [ ! "$(grep osinfo /etc/passwd)" ]; then

		# create new user to /etc/passwd
		if [ "$(type -p useradd)" ]; then
			useradd -d "$vardir" -m -c osinfo\ client -s /bin/false osinfo

		else
			echo "osinfo:x:7776:1000:osinfo client:${vardir}:/bin/false" >> /etc/passwd

		fi

		[ $? -ne 0 ] &&	echo "could not add user osinfo!"

	else
		echo "user osinfo already created"
	fi
fi


# add sudoers entry
if [ -e "/etc/sudoers" ]; then
	echo "# osinfo added by $0" >> /etc/sudoers 
	echo "osinfo  ALL=NOPASSWD: ${bindir}/osinfo" >> /etc/sudoers

	[ $? -ne 0 ] &&	echo "could not add user osinfo to sudoers!"
fi



# copy ssh key to $vardir/.ssh
if [ ! "$sshkey" ]; then
	echo "no ssh key defined - not copying"

else
	sshdir="${vardir}/.ssh/"
	mkdir -p "${sshdir}"

	if [ "$(type -p rsync)" ]; then	
		echo "retrieving sshkey via rsync"
		rsync "${sshkey}" "${sshdir}"
		
	elif [ "$(type -p rcp)" ]; then
		echo "retrieving sshkey via rcp"
		rcp "${sshkey}" "${sshdir}"

	else
		echo "rsync nor rcp binaries not found - ssh key cannot be retrieved."
	fi

	chown osinfo -R "${sshdir}"
fi

exit 0
