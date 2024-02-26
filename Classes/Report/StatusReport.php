<?php
namespace Lemming\Imageoptimizer\Report;

use Lemming\Imageoptimizer\OptimizeImageService;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Reports\Status;
use TYPO3\CMS\Reports\StatusProviderInterface;

class StatusReport implements StatusProviderInterface
{
    private ExtensionConfiguration $extensionConfiguration;

    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function getLabel(): string
    {
        return 'Image Optimizer';
    }

    /**
     * Determines if the needed binaries are found
     */
    public function getStatus(): array
    {
        $configuration = $this->extensionConfiguration->get('imageoptimizer');
        $extensions = ['jpg', 'png', 'gif', 'svg'];
        $status = [];
        foreach ($extensions as $extension) {
            $binary = escapeshellcmd($configuration[$extension . 'Binary']);
            $binaryFound = is_string(CommandUtility::getCommand($binary));
            $binaryUsed = ((bool)($configuration[$extension . 'OnUpload'] ?? false) === true
                || (bool)($configuration[$extension . 'OnProcessing'] ?? false) === true);

            $status[$extension] = GeneralUtility::makeInstance(
                Status::class,
                'Binary ' . $binary,
                $binaryFound ? 'Found' : OptimizeImageService::BINARY_NOT_FOUND,
                $binaryUsed ? 'In use' : 'Not in use',
                $binaryFound || $binaryUsed === false ? ContextualFeedbackSeverity::OK : ContextualFeedbackSeverity::ERROR
            );
        }
        return $status;
    }
}
