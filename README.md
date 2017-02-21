# TYPO3 ImageOptimizer Extension

Lossless jpg/png image optimization with optipng, jpegtran and jpegoptim.

## Features

* Optimize images on upload
* Optimize images after processing (files in `fileadmin/_processed_/`)

## Installation

You need optipng, jpegtran and jpegoptim on the server.

Debian/Ubuntu: `apt-get install optipng libjpeg-turbo-progs jpegoptim`

## Configuration options

See [here](ext_conf_template.txt)

## Debugging

There is a report in the backend report module. When the binaries are not found try to adapt `$GLOBALS['TYPO3_CONF_VARS']['SYS']['binpath']`

There is a also a debugging option in the Extension Manager configuration.
