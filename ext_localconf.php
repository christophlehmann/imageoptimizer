<?php

$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imageoptimizer']);

if (!isset($signalSlotDispatcher)) {
    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\SignalSlot\Dispatcher');
}

if ((isset($configuration['jpgOnUpload']) && (bool)$configuration['jpgOnUpload'] === true) ||
    (isset($configuration['pngOnUpload']) && (bool)$configuration['pngOnUpload'] === true) ||
    (isset($configuration['gifOnUpload']) && (bool)$configuration['gifOnUpload'] === true) ||
    (isset($configuration['svgOnUpload']) && (bool)$configuration['svgOnUpload'] === true)) {
    $signalSlotDispatcher->connect(
        'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
        \TYPO3\CMS\Core\Resource\ResourceStorage::SIGNAL_PreFileAdd,
        'Lemming\\Imageoptimizer\\FileAspects',
        'addFile'
    );

    $signalSlotDispatcher->connect(
        'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
        \TYPO3\CMS\Core\Resource\ResourceStorage::SIGNAL_PreFileReplace,
        'Lemming\\Imageoptimizer\\FileAspects',
        'replaceFile'
    );
}

if ((isset($configuration['jpgOnProcessing']) && (bool)$configuration['jpgOnProcessing'] === true) ||
    (isset($configuration['pngOnProcessing']) && (bool)$configuration['pngOnProcessing'] === true) ||
    (isset($configuration['gifOnProcessing']) && (bool)$configuration['gifOnProcessing'] === true)) {
    $signalSlotDispatcher->connect(
        'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
        \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PostFileProcess,
        'Lemming\\Imageoptimizer\\FileAspects',
        'processFile'
    );
}

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['ImageOptimizer'][] =
        \Lemming\Imageoptimizer\StatusReport::class;
}
