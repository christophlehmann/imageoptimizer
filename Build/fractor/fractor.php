<?php

declare(strict_types=1);

use a9f\Fractor\Configuration\FractorConfiguration;
use a9f\FractorComposerJson\ChangePackageVersionComposerJsonFractor;
use a9f\FractorComposerJson\ValueObject\PackageAndVersion;
use a9f\Typo3Fractor\Set\Typo3LevelSetList;

return FractorConfiguration::configure()
    ->withPaths([
        __DIR__ . '/../../Classes/',
        __DIR__ . '/../../Configuration/',
        __DIR__ . '/../../Resources/',
        __DIR__ . '/../../composer.json',
        __DIR__ . '/../../ext_emconf.php',
    ])
    ->withSets([
        Typo3LevelSetList::UP_TO_TYPO3_13,
    ])
    ->withConfiguredRule(
        ChangePackageVersionComposerJsonFractor::class,
        [
            new PackageAndVersion('typo3/cms-core', '^13.4'),
            new PackageAndVersion('typo3/cms-reports', '^13.4'),
            // require-dev
            new PackageAndVersion('typo3/cms-backend', '^13.4'),
            new PackageAndVersion('typo3/cms-fluid', '^13.4'),
            new PackageAndVersion('typo3/cms-frontend', '^13.4'),
            new PackageAndVersion('typo3/cms-install', '^13.4'),
        ]
    );
