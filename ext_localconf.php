<?php

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['ImageOptimizer'][] =
        \Lemming\Imageoptimizer\StatusReport::class;
}
