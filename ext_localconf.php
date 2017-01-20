<?php

$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imageoptimizer']);

if (isset($configuration['optimizeOnUpload']) && (bool)$configuration['optimizeOnUpload'] === TRUE) {
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

if (isset($configuration['optimizeAfterProcessing']) && (bool)$configuration['optimizeAfterProcessing'] === TRUE) {
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