# TYPO3 ImageOptimizer Extension

[![Latest Stable Version](https://poser.pugx.org/christophlehmann/imageoptimizer/v/stable)](https://packagist.org/packages/christophlehmann/imageoptimizer)
[![Total Downloads](https://poser.pugx.org/christophlehmann/imageoptimizer/downloads)](https://packagist.org/packages/christophlehmann/imageoptimizer)
[![License](https://poser.pugx.org/christophlehmann/imageoptimizer/license)](https://packagist.org/packages/christophlehmann/imageoptimizer)
[![TYPO3](https://img.shields.io/badge/TYPO3-13-orange.svg)](https://get.typo3.org/version/13)

Lossless image optimization.
Process jpg/png/gif/svg/webp images with binaries and parameters of your choice.

Note: JPG and PNG were yesterday.
If you want to serve images in new formats like avif and webp including auto detection and asynchronous generation,
then [imgproxy](https://github.com/christophlehmann/imgproxy) may fit your needs. #webvitals

## Features

* Optimize images on upload or after processing (files in `fileadmin/_processed_/`)
* Binaries, paths and their parameters are configurable

## Installation

Debian/Ubuntu: `apt-get install optipng jpegoptim libjpeg-turbo-progs gifsicle`

svgo is installable via NPM `npm install -g svgo`

### Installation for optimizing webp images with cwebp

See https://developers.google.com/speed/webp/download for downloading and installation.

If the binaries are not under `/usr/bin/`, then add the absolute path in TYPO3's global configuration like so:

`system/settings.php`:

```php
return [
    'SYS' => [
        'binSetup' => 'cwebp=/absolute/path/to/bin/cwebp',
    ],
];
```

## Configuration options

![Extension Manager configuration options](https://raw.githubusercontent.com/christophlehmann/imageoptimizer/master/Documentation/configuration.png)

### Available Parameters

- Available parameters for gifsicle are found [here](https://www.lcdf.org/gifsicle/man.html).
- Available parameters for jpegoptim are found [here](https://www.kokkonen.net/tjko/src/man/jpegoptim.txt).
- Available parameters for cwebp are found [here](https://developers.google.com/speed/webp/docs/cwebp).

## Debugging

* Commands are tested in the Extension Settings module
* Logging API: See file/database log
