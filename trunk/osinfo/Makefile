helpers=bash.functions osinfo.modules xml.functions lanforce.functions
mainapp=osinfo
subdirs=docs
appname=osinfo

all:
	@echo "Provide a command please"
	@echo "See make help"

help:
	@echo "Targets:"
	@echo "  dist            - makes a dist dir, ready for $(appname) tarball"
	@echo "  dist-tarbz2     - tar and bzip2 the dist"
	@echo "  distclean       - remove the dist dir and *~"
	@echo "  clean           - remove *~"
	@echo "  dist-all-in-one - makes a huge all in one script. (replaces source foo with foo)"

dist:
	mkdir -p dist/$(appname)
	cp $(helpers) dist/$(appname)
	cp $(mainapp) dist/$(appname)
	for dir in $(subdirs); do \
		mkdir dist/$(appname)/$$dir ; \
		cp $$dir/* dist/$(appname)/$$dir ; \
	done

# Not very elegant
# It greps the definitons out of osinfo and then evals it.
# After that it echos the thing.
# And then include the output in the paramerter of tar.
# Bad that it doesn't work in a variable.
dist-tarbz2: dist
	cd dist && tar -jcf "$$(eval $$(head -n16 ../osinfo | pcregrep 'appname|_version') && echo $${appname}-$${major_version}.$${minor_version}.$${micro_version}-$${patch_version}).tar.bz2" $(appname)

dist-all-in-one: dist
	@echo "THIS IS BROKEN! To still use it edit the Makefile and uncomment some lines in the rule dist-all-in-one"
#	@echo "WARNING: this is quite untested"
#	@echo "         therefore the output is saved in \"osinfo-all-in-one\" not \"osinfo\""
#	cd dist/$(appname) && perl -npe 's/^source (.*)/`cat $1`/e' <$(mainapp) > osinfo-all-in-one
#	chmod +x dist/$(appname)/osinfo-all-in-one

distclean: clean
	-rm -rf dist

clean:
	-rm -f *~ *.tmp
	-rm -f docs/*~ docs/*.tmp

.PHONY : all clean distclean
