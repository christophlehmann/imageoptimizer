<?php
namespace Lemming\Imageoptimizer\EventListener;

use Lemming\Imageoptimizer\OptimizeImageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractEventListener
{
    public function getService(): OptimizeImageService
    {
        return GeneralUtility::makeInstance(OptimizeImageService::class);
    }
}
