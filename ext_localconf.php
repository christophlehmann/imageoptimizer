<?php

$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imageoptimizer']);

if (!isset($signalSlotDispatcher)) {
    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
}

if ((isset($configuration['jpgOnUpload']) && (bool)$configuration['jpgOnUpload'] === true) ||
    (isset($configuration['pngOnUpload']) && (bool)$configuration['pngOnUpload'] === true) ||
    (isset($configuration['gifOnUpload']) && (bool)$configuration['gifOnUpload'] === true) ||
    (isset($configuration['svgOnUpload']) && (bool)$configuration['svgOnUpload'] === true)) {
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Core\Resource\ResourceStorage::class,
        \TYPO3\CMS\Core\Resource\ResourceStorageInterface::SIGNAL_PreFileAdd,
        \Lemming\Imageoptimizer\FileAspects::class,
        'addFile'
    );

    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Core\Resource\ResourceStorage::class,
        \TYPO3\CMS\Core\Resource\ResourceStorageInterface::SIGNAL_PreFileReplace,
        \Lemming\Imageoptimizer\FileAspects::class,
        'replaceFile'
    );
}

if ((isset($configuration['jpgOnProcessing']) && (bool)$configuration['jpgOnProcessing'] === true) ||
    (isset($configuration['pngOnProcessing']) && (bool)$configuration['pngOnProcessing'] === true) ||
    (isset($configuration['gifOnProcessing']) && (bool)$configuration['gifOnProcessing'] === true)) {
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Core\Resource\ResourceStorage::class,
        \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PostFileProcess,
        \Lemming\Imageoptimizer\FileAspects::class,
        'processFile'
    );
}

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['ImageOptimizer'][] =
        \Lemming\Imageoptimizer\StatusReport::class;
}
