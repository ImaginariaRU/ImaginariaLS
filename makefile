#!/usr/bin/make
# SHELL	 = /bin/bash
PROJECT  = 47news
PATH_PROJECT = $(DESTDIR)/var/www/$(PROJECT)
PATH_WWW = $(PATH_PROJECT)/www
SEARCH_ENGINE_DIR = manticoresearch

help:
	@perl -e '$(HELP_ACTION)' $(MAKEFILE_LIST)

install: 	##@system Install package. Don't run it manually!!!
	@echo Installing...
	install -d $(PATH_PROJECT)
	cp -r www $(PATH_PROJECT)
	cp -r admin.cron $(PATH_PROJECT)
	cp -r admin.tools $(PATH_PROJECT)
	cp -r config.site $(PATH_PROJECT)
	cp composer.json $(PATH_PROJECT)
	cp $(PATH_WWW)/frontend/favicon/favicon.ico $(PATH_WWW)/
	git rev-parse --short HEAD > $(PATH_WWW)/_version
	git log --oneline --format=%B -n 1 HEAD | head -n 1 >> $(PATH_WWW)/_version
	git log --oneline --format="%at" -n 1 HEAD | xargs -I{} date -d @{} +%Y-%m-%d >> $(PATH_WWW)/_version
	cd $(PATH_PROJECT)/ && composer install && rm composer.lock
# cd $(PATH_WWW)/ && composer install && rm composer.json && rm composer.lock
	cp makefile.production-toolkit $(PATH_PROJECT)/makefile
	install -d $(PATH_PROJECT)/bin
	install -d $(PATH_PROJECT)/cache
	install -d $(PATH_PROJECT)/config
	install -d $(PATH_PROJECT)/config.site
	install -d $(PATH_PROJECT)/logs
	install -d $(PATH_PROJECT)/rss
	install -d $(PATH_WWW)/sitemaps
	mkdir -p $(DESTDIR)/etc/$(SEARCH_ENGINE_DIR)/conf.d/$(PROJECT)
	cp -r config.sphinx/* $(DESTDIR)/etc/$(SEARCH_ENGINE_DIR)/conf.d/$(PROJECT)/

update:		##@build Update project from GIT
	@echo Updating project from GIT
	git pull

build:		##@build Build project to DEB Package
	@echo Building project to DEB-package
	export COMPOSER_HOME=/tmp/ && dpkg-buildpackage -rfakeroot --no-sign

setup_env:	##@localhost Setup environment at localhost
	@echo Setting up local environment
	@mkdir -p $(PATH_PROJECT)/cache
	@mkdir -p $(PATH_PROJECT)/logs
	@mkdir -p $(PATH_PROJECT)/rss
	@sudo mkdir -p /var/cache/nginx/fastcgi/$(PROJECT)

sync_db:	##@localhost Sync remote DB backup to local database
	@echo Downloading database
	@scp wombat@source.fontanka.ru:/var/spool/acmsbackup/mysql/47news.mysql.gz /tmp
	@echo Archive contains:
	@gunzip --list /tmp/47news.mysql.gz
	@echo Replacing local database
	@gunzip < /tmp/47news.mysql.gz | pv | mysql 47news

rebuild_rt:	##@localhost Rebuild RT indexes only
	@php $(PATH_PROJECT)/admin.tools/tool.rebuild_rt_indexes.php

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

