# builds the distribution archives and cleans up

BUILD=$(shell expr `cat scripts/BUILD` + 1)
VERSION=$(shell grep "'RAXSDK_VERSION'" lib/globals.inc | sed -e 's/[^0-9\.]//g')
LIBRARY=php-opencloud-$(VERSION)_$(BUILD)
ARCHIVE=$(LIBRARY).tar
TARFILE=$(ARCHIVE).gz
ZIPFILE=$(LIBRARY).zip
FILES=*.md COPYING lib docs samples tests

all: $(TARFILE) $(ZIPFILE)
	echo $(BUILD) > scripts/BUILD

$(TARFILE): $(ARCHIVE)
	gzip $(ARCHIVE)

$(ARCHIVE):
	tar cvf $(ARCHIVE) $(FILES)

$(ZIPFILE):
	zip -r $(ZIPFILE) $(FILES)

clean:
	rm *.gz *.zip
