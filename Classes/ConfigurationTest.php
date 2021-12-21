<?php
namespace Lemming\Imageoptimizer;

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
                /** @var FlashMessage $message */
                $message = GeneralUtility::makeInstance(
                    FlashMessage::class,
                    implode(PHP_EOL, $this->service->getOutput()),
                    sprintf('%s: %s', $header, $this->service->getCommand()),
                    $returnValue ? FlashMessage::OK : FlashMessage::ERROR
                );
            } catch (BinaryNotFoundException $e) {
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

            unlink($temporaryFile);
            $messageQueue->addMessage($message);
        }

        $flashMessageRenderer = GeneralUtility::makeInstance(BootstrapRenderer::class);
        return $messageQueue->renderFlashMessages($flashMessageRenderer);
    }
}
