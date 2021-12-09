<?php
if (!\defined('TYPO3')) {
    exit;
}

\call_user_func(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['ImageOptimizer'][] =
        \Lemming\Imageoptimizer\StatusReport::class;
});
