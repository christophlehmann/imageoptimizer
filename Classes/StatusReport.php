<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
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
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * Determines if the needed binaries are found
     *
     * @return array List of statuses
     */
    public function getStatus()
    {
        $configuration = $this->objectManager->get(ExtensionConfiguration::class)->get('imageoptimizer');
        $extensions = ['jpg', 'png', 'gif', 'svg'];
        foreach ($extensions as $extension) {
            $binary = escapeshellcmd($configuration[$extension . 'Binary']);
            $binaryFound = is_string(CommandUtility::getCommand($binary));
            $binaryUsed = ((bool)($configuration[$extension . 'OnUpload']) === true || (bool)($configuration[$extension . 'OnProcessing']) === true);

            $status[$extension] = $this->objectManager->get(
                Status::class,
                'Binary ' . $binary,
                $binaryFound ? 'Found' : OptimizeImageService::BINARY_NOT_FOUND,
                $binaryUsed ? 'In use' : 'Not in use',
                $binaryFound || $binaryUsed === false ? Status::OK : Status::ERROR
            );
        }
        return $status;
    }
}
