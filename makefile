PHP ?=
TEST ?=
VOLUME_BINDS ?= src,tests,build,.php-cs-fixer.php,phpunit.xml,ruleset.xml

docs:
	docker run --rm -v $$(pwd)/src:/data/src -v $$(pwd)/docs:/data/docs -w /data php:cli bash -c "\
		curl -s -L -O https://phpdoc.org/phpDocumentor.phar;\
		php phpDocumentor.phar --directory=src --target=docs --visibility=public --defaultpackagename='PathConverter' --title='Path converter';"

test:
	VOLUMES=""
	for VOLUME in $$(echo "$(VOLUME_BINDS)" | tr "," "\n"); do VOLUMES="$$VOLUMES -v $$(pwd)/$$VOLUME:/var/www/$$VOLUME"; done;\
	VERSION=$$(echo "$(PHP)-cli" | sed "s/^-//");\
	test $$(docker images -q matthiasmullie/path-converter:$$VERSION) || docker build -t matthiasmullie/path-converter:$$VERSION . --build-arg VERSION=$$VERSION;\
	docker run$$VOLUMES matthiasmullie/path-converter:$$VERSION env XDEBUG_MODE=coverage vendor/bin/phpunit $(TEST) --coverage-clover build/coverage-$(PHP)-$(TEST).clover

format:
	VOLUMES=""
	for VOLUME in $$(echo "$(VOLUME_BINDS)" | tr "," "\n"); do VOLUMES="$$VOLUMES -v $$(pwd)/$$VOLUME:/var/www/$$VOLUME"; done;\
	test $$(docker images -q matthiasmullie/path-converter:cli) || docker build -t matthiasmullie/path-converter:cli .;\
	docker run $$VOLUMES matthiasmullie/path-converter:cli sh -c "vendor/bin/php-cs-fixer fix && vendor/bin/phpcbf --standard=ruleset.xml"

.PHONY: docs
