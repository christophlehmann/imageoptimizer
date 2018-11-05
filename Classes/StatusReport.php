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
     * Determines if the needed binaries are found
     *
     * @return array List of statuses
     */
    public function getStatus()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configuration = $objectManager->get(ExtensionConfiguration::class)->get('imageoptimizer');
        $extensions = ['jpg', 'png', 'gif', 'svg'];
        foreach ($extensions as $extension) {
            $binary = escapeshellcmd($configuration[$extension . 'Binary']);
            $binaryFound = is_string(CommandUtility::getCommand($binary));
            $binaryUsed = ((bool)($configuration[$extension . 'OnUpload']) === true
                || (bool)($configuration[$extension . 'OnProcessing']) === true);

            $status[$extension] = $objectManager->get(
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
