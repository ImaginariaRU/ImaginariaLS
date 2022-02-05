#!/usr/bin/make
PATH_ROOT = $(DESTDIR)/var/www/imaginaria
PATH_PUBLIC = $(PATH_ROOT)/www
SEARCH_ENGINE_DIR = manticoresearch

help:
	@perl -e '$(HELP_ACTION)' $(MAKEFILE_LIST)

install: 	##@system Install package. Don't run it manually!!!
	@echo Installing...
	install -d $(PATH_ROOT)
	cp -r www $(PATH_ROOT)
	cp -r admin.cron $(PATH_ROOT)
	cp -r admin.tools $(PATH_ROOT)
	cp -r config $(PATH_ROOT)
	cp composer.json $(PATH_ROOT)
	cp $(PATH_PUBLIC)/templates/favicon/favicon.ico $(PATH_PUBLIC)/
	git rev-parse --short HEAD > $(PATH_PUBLIC)/_version
	git log --oneline --format=%B -n 1 HEAD | head -n 1 >> $(PATH_PUBLIC)/_version
	git log --oneline --format="%at" -n 1 HEAD | xargs -I{} date -d @{} +%Y-%m-%d >> $(PATH_PUBLIC)/_version
	cd $(PATH_ROOT)/ && composer install && rm composer.lock
#	cp makefile.production-toolkit $(PATH_ROOT)/makefile
	install -d $(PATH_PUBLIC)/cache
	install -d $(PATH_ROOT)/config
	install -d $(PATH_ROOT)/logs
	install -d $(PATH_PUBLIC)/sitemaps
	mkdir -p $(DESTDIR)/etc/$(SEARCH_ENGINE_DIR)/conf.d/$(PROJECT)
	cp -r config.searchd/* $(DESTDIR)/etc/$(SEARCH_ENGINE_DIR)/conf.d/$(PROJECT)/

update:		##@build Update project from GIT
	@echo Updating project from GIT
	git pull

build:		##@build Build project to DEB Package
	@echo Building project to DEB-package
	export COMPOSER_HOME=/tmp/ && dpkg-buildpackage -rfakeroot --no-sign

setup_env:	##@localhost Setup environment at localhost
	@echo Setting up local environment
	@mkdir -p $(PATH_PUBLIC)/cache
	@mkdir -p $(PATH_ROOT)/logs
	@sudo mkdir -p /var/cache/nginx/fastcgi/$(PROJECT)

# ------------------------------------------------
# Add the following 'help' target to your makefile, add help text after each target name starting with '\#\#'
# A category can be added with @category
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
WHITE  := $(shell tput -Txterm setaf 7)
RESET  := $(shell tput -Txterm sgr0)
HELP_ACTION = \
	%help; while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-_]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
	print "usage: make [target]\n\n"; for (sort keys %help) { print "${WHITE}$$_:${RESET}\n"; \
	for (@{$$help{$$_}}) { $$sep = " " x (32 - length $$_->[0]); print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; }; \
	print "\n"; }

# -eof-

