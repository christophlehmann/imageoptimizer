# TYPO3 ImageOptimizer Extension

Lossless jpg/png image optimization. Process jpg/png/gif/svg with binaries and parameters of your choice.

## Features

* Optimize images on upload or after processing (files in `fileadmin/_processed_/`)
* Binaries, paths and their parameters are configurable
* Supported image formats: jpg, png, gif, svg

## Installation

You need optipng and jpegoptim on the server.

Debian/Ubuntu: `apt-get install optipng libjpeg-turbo-progs gifsicle`

svgo is installable via NPM `npm install -g svgo`

## Configuration options

![Extension Manager configuration options](https://raw.githubusercontent.com/christophlehmann/imageoptimizer/master/Documentation/configuration.png)

## Debugging

There is a report in the backend report module. When the binaries are not found try to adapt `$GLOBALS['TYPO3_CONF_VARS']['SYS']['binpath']`

There is a also a debugging option in the Extension Manager configuration.
