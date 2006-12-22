# osinfo Makefile
#
mainapp=osinfo
subdirs=docs html html/xslt html/hosts
src_subdir=conf man init.d lib modules modules/experimental xslt profiles ebuild ebuild/files

# helpers go only to the src/ directory
helpers=sources

appname=osinfo
our_dtd=osinfo.dtd

all:
	@echo "Provide a command please"
	@echo "See make help"

help:
	@echo "Targets:"
	@echo "  install         - install the application"
	@echo "  dist            - makes a dist dir, ready for $(appname) tarball"
	@echo "  dist-tarbz2     - tar and bzip2 the dist"
	@echo "  distclean       - remove the dist dir and *~"
	@echo "  clean           - remove *~"

dist: distclean man
	mkdir -p dist/$(appname)

	######################
	# copy subdirectories
	######################
	for dir in $(subdirs); do \
		cp -r $$dir dist/$(appname)/ ; \
	done

	######################
	# copy sources
	######################
	mkdir -p dist/$(appname)/src
	for dir in $(src_subdir); do \
		cp -r $$dir dist/$(appname)/src/ ; \
	done
	cp $(helpers) dist/$(appname)/src
	cp $(mainapp) dist/$(appname)/src
	cp $(our_dtd) dist/$(appname)/src

	######################
	# make all-in-one package
	######################
	cd dist/$(appname) && \
	for sources in "src/lib/version" "src/profiles/*" "src/modules/*" "src/modules/experimental/*" "src/lib/*[^version]*" ; do \
		cat $$sources >> osinfo 2>/dev/null ; \
	done
	cd dist/$(appname) && \
		sed 's/^source[a-zA-Z. _\/]*//' src/$(mainapp) >> osinfo
		# clear out sourcing external files, since it's now all-in-one
	chmod +x dist/$(appname)/osinfo

	######################
	# copy manual
	######################
	cp man/osinfo.1 dist/osinfo

	# done!

# Not very elegant
# It greps the definitions out of osinfo and then evals it.
# After that it echos the thing.
# And then include the output in the paramerter of tar.
# Bad that it doesn't work in a variable.
dist-tarbz2: dist
	cd dist && tar -jcf "$$(eval $$(grep appname -A 10 -m 1 ../lib/version | pcregrep 'appname|_version') && echo $${appname}-$${major_version}.$${minor_version}.$${micro_version}_$${patch_version}).tar.bz2" $(appname)

distclean: clean
	-rm -rf dist

install: dist

man: 
	cd man
	cat osinfo.man.txt |  txt2man -t osinfo -s 1 -r "OSinfo manual" > osinfo.1
	cd ..

clean:
	-rm -f *~ *.tmp
	-rm -f docs/*~ docs/*.tmp

checkxml:
	xmllint --valid osinfo.xml

tarbz2: dist-tarbz2

.PHONY : all clean dist distclean checkxml
