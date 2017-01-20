# TYPO3 ImageOptimizer Extension

Lossless jpg/png image optimization with optipng and jpegtran.

## Features

* Optimize images on upload
* Optimize png images after processing (files in `fileadmin/_processed_/`)

## Installation

You need optipng and jpegtran on the server.

Debian/Ubuntu: `apt-get install optipng libjpeg-turbo-progs`

## Configuration options

See [here](ext_conf_template.txt)

## Debugging

There is a report in the backend report module. When the binaries are not found try to adapt `$GLOBALS['TYPO3_CONF_VARS']['SYS']['binpath']`

There is a also a debugging option in the Extension Manager configuration.
