<?php

defined('TYPO3') or die();

$typo3Version = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
if($typo3Version->getMajorVersion() < 12) {
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['ImageOptimizer'][] =
    \Lemming\Imageoptimizer\StatusReport::class;
}
