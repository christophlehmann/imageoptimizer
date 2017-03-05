# TYPO3 ImageOptimizer Extension

Lossless jpg/png image optimization. Process jpg/png/gif/svg with binaries and parameters of your choice.

## Features

* Optimize images on upload or after processing (files in `fileadmin/_processed_/`)
* Binaries, paths and their parameters are configurable
* Supported image formats: jpg, png, gif, svg

## Installation

Debian/Ubuntu: `apt-get install optipng libjpeg-turbo-progs gifsicle`

svgo is installable via NPM `npm install -g svgo`

## Configuration options

![Extension Manager configuration options](https://raw.githubusercontent.com/christophlehmann/imageoptimizer/master/Documentation/configuration.png)

## Debugging

* Check the backend report module. When the binaries are not found try to adapt their bin path in the Extension Manager.
* Activate the debugging option in the Extension Manager. Full commands are then logged to the log module.