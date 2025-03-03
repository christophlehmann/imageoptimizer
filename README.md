# TYPO3 ImageOptimizer Extension

[![Latest Stable Version](https://poser.pugx.org/christophlehmann/imageoptimizer/v/stable)](https://packagist.org/packages/christophlehmann/imageoptimizer)
[![Total Downloads](https://poser.pugx.org/christophlehmann/imageoptimizer/downloads)](https://packagist.org/packages/christophlehmann/imageoptimizer)
[![License](https://poser.pugx.org/christophlehmann/imageoptimizer/license)](https://packagist.org/packages/christophlehmann/imageoptimizer)
[![TYPO3](https://img.shields.io/badge/TYPO3-13-orange.svg)](https://get.typo3.org/version/13)

Lossless image optimization. Process jpg/png/gif/svg images with binaries and parameters of your choice.

Note: JPG and PNG were yesterday.
If you want to serve images in new formats like avif and webp including auto detection and asynchronous generation,
then [imgproxy](https://github.com/christophlehmann/imgproxy) may fit your needs. #webvitals

## Features

* Optimize images on upload or after processing (files in `fileadmin/_processed_/`)
* Binaries, paths and their parameters are configurable

## Installation

Debian/Ubuntu: `apt-get install optipng jpegoptim libjpeg-turbo-progs gifsicle`

svgo is installable via NPM `npm install -g svgo`

## Configuration options

![Extension Manager configuration options](https://raw.githubusercontent.com/christophlehmann/imageoptimizer/master/Documentation/configuration.png)

## Debugging

* Commands are tested in the Extension Settings module
* Logging API: See file/database log
