<?php
namespace Lemming\Imageoptimizer\EventListener;

use Lemming\Imageoptimizer\OptimizeImageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractEventListener
{
    /**
     * @var OptimizeImageService
     */
    protected $service;

    public function __construct()
    {
        $service = GeneralUtility::makeInstance(OptimizeImageService::class);
    }
}