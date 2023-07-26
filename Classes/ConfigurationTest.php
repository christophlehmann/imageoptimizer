<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Messaging\Renderer\BootstrapRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurationTest
{
    /**
     * @var \Lemming\Imageoptimizer\OptimizeImageService
     */
    protected $service;

    /**
     * @var FlashMessageService
     */
    protected $flashMessageService;

    public function __construct()
    {
        $this->service = GeneralUtility::makeInstance(OptimizeImageService::class);
        $this->flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
    }

    public function testCommand(array $params): string
    {
        $fileExtension = $params['fieldValue'];
        $messageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
        $typo3Version = GeneralUtility::makeInstance(Typo3Version::class);

        foreach ([false, true] as $fileIsUploaded) {
            if ($fileExtension === 'svg' && !$fileIsUploaded) {
                continue;
            }

            $header = sprintf('%s%s', strtoupper($fileExtension), $fileIsUploaded ? ' on Upload' : '');
            $file = sprintf(
                '%s/Resources/Private/Images/example.%s',
                ExtensionManagementUtility::extPath('imageoptimizer'),
                $fileExtension
            );
            $temporaryFile = GeneralUtility::tempnam('imageoptimizer', $fileExtension);
            copy($file, $temporaryFile);

            try {
                $returnValue = $this->service->process(
                    $temporaryFile,
                    $fileExtension,
                    $fileIsUploaded,
                    true
                );

                if($typo3Version->getMajorVersion() < 12) {
                    /** @var FlashMessage $message */
                    $message = GeneralUtility::makeInstance(
                        FlashMessage::class,
                        implode(PHP_EOL, $this->service->getOutput()),
                        sprintf('%s: %s', $header, $this->service->getCommand()),
                        $returnValue ? FlashMessage::OK : FlashMessage::ERROR
                    );
                }
                else {
                    /** @var FlashMessage $message */
                    $message = GeneralUtility::makeInstance(
                        FlashMessage::class,
                        implode(PHP_EOL, $this->service->getOutput()),
                        sprintf('%s: %s', $header, $this->service->getCommand()),
                        $returnValue ? \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK : \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
                    );
                }

            } catch (BinaryNotFoundException $e) {
                if($typo3Version->getMajorVersion() < 12) {
                    /** @var FlashMessage $message */
                    $message = GeneralUtility::makeInstance(
                        FlashMessage::class,
                        OptimizeImageService::BINARY_NOT_FOUND,
                        sprintf(
                            $header,
                            strtoupper($fileExtension),
                            $fileIsUploaded ? 'on Upload' : ''
                        ),
                        FlashMessage::ERROR
                    );
                }
                else {
                    /** @var FlashMessage $message */
                    $message = GeneralUtility::makeInstance(
                        FlashMessage::class,
                        OptimizeImageService::BINARY_NOT_FOUND,
                        sprintf(
                            $header,
                            strtoupper($fileExtension),
                            $fileIsUploaded ? 'on Upload' : ''
                        ),
                        \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
                    );
                }

            unlink($temporaryFile);
            $messageQueue->addMessage($message);
        }

        $flashMessageRenderer = GeneralUtility::makeInstance(BootstrapRenderer::class);
        return $messageQueue->renderFlashMessages($flashMessageRenderer);
    }
}
