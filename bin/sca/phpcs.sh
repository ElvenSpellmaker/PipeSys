#!/usr/bin/env bash

if [[ `uname -s` == *"CYGWIN"* ]]
then
	export ConEmuANSI=ON
	if php -i | grep -i "Host => .*-unknown-cygwin"; then
		COMMAND='phpcs'
	else
		COMMAND='phpcs.bat'
	fi
else
	COMMAND='phpcs'
fi

./vendor/bin/$COMMAND --standard=phpcs.xml src/ > bin/sca/phpcs.txt
