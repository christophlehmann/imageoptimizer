<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Reports\Status;

class StatusReport implements \TYPO3\CMS\Reports\StatusProviderInterface
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
    }

    /**
     * Determines if the needed binaries are found
     *
     * @return array List of statuses
     */
    public function getStatus()
    {
        $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imageoptimizer']);
        $extensions = ['jpg', 'png', 'gif', 'svg'];
        foreach ($extensions as $extension) {
            $binary = escapeshellcmd($configuration[$extension . 'Binary']);
            $binaryFound = is_string(CommandUtility::getCommand($binary));
            $binaryUsed = ((bool)($configuration[$extension . 'OnUpload']) === true || (bool)($configuration[$extension . 'OnProcessing']) === true);

            $status[$extension] = $this->objectManager->get(
                Status::class,
                'Binary ' . $binary,
                $binaryFound ? 'Found' : 'Not found',
                $binaryUsed ? 'In use' : 'Not in use',
                $binaryFound || $binaryUsed === false ? Status::OK : Status::ERROR
            );
        }
        return $status;
    }
}
