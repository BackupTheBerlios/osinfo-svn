#!/bin/bash

cat osinfo.man.txt |  txt2man -t osinfo -s 1 -r "OSinfo manual" > osinfo.1
