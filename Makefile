help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  coverage       to perform unit tests with code coverage. Provide TEST to perform a specific test."
	@echo "  coverage-show  to show the code coverage report"
	@echo "  dump-routes    to build the json routes file from yaml"

coverage:
	vendor/bin/phpunit --coverage-html=build/artifacts/coverage

coverage-show: view-coverage

view-coverage:
	open build/artifacts/coverage/index.html

dump-routes:
	php build/dumpRoutes.php

.PHONY: coverage-show view-coverage
