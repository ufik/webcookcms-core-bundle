#!/bin/bash

if [ "$1" == "coverage" ]; then
	rm -rf src/Webcook/Cms/CoreBundle/Tests/cache
	rm -rf vendor/webcookcms/core-bundle/src/Webcook/Cms/CoreBundle/Tests/cache
	phpunit --coverage-text
elif [ "$1" == "html" ]; then
	rm -rf vendor/webcookcms/core-bundle/src/Webcook/Cms/CoreBundle/Tests/cache
	phpunit --coverage-html ./coverage-html
elif [ "$1" == "ci" ]; then
	rm -rf src/Webcook/Cms/CoreBundle/Tests/cache
	rm -rf vendor/webcookcms/core-bundle/src/Webcook/Cms/CoreBundle/Tests/cache
	phpunit --coverage-clover=coverage.clover tests;result=$?
        wget https://scrutinizer-ci.com/ocular.phar
        php ocular.phar code-coverage:upload --format=php-clover coverage.clover
	exit $result
else
	phpunit
fi
