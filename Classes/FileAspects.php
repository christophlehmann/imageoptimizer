<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FileAspects
{

    /**
     * @var \Lemming\Imageoptimizer\OptimizeImageService
     */
    protected $service;

    public function __construct()
    {
        $this->service = GeneralUtility::makeInstance('Lemming\Imageoptimizer\OptimizeImageService');
    }

    /**
     * Called when a new file is uploaded
     *
     * @param string $targetFileName
     * @param \TYPO3\CMS\Core\Resource\Folder $targetFolder
     * @param string $sourceFilePath
     * @return string Modified target file name
     */
    public function addFile($targetFileName, \TYPO3\CMS\Core\Resource\Folder $targetFolder, $sourceFilePath)
    {
        $this->service->process($sourceFilePath, pathinfo($targetFileName)['extension'], true);
    }

    /**
     * Called when a file is overwritten
     *
     * @param \TYPO3\CMS\Core\Resource\FileInterface $file The file to replace
     * @param string $localFilePath The uploaded file
     */
    public function replaceFile(\TYPO3\CMS\Core\Resource\FileInterface $file, $localFilePath)
    {
        $this->service->process($localFilePath, $file->getExtension(), true);
    }

    /**
     * Called when a file was processed
     *
     * @param \TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService
     * @param \TYPO3\CMS\Core\Resource\Driver\DriverInterface $driver
     * @param \TYPO3\CMS\Core\Resource\ProcessedFile $processedFile
     */
    public function processFile($fileProcessingService, $driver, $processedFile)
    {
        if (!$processedFile->exists()) {
            return;
        }

        if ($processedFile->usesOriginalFile() === true || $processedFile->isUpdated() === true) {
            $fileForLocalProcessing = $processedFile->getForLocalProcessing();
            $this->service->process($fileForLocalProcessing, $processedFile->getExtension());
            $processedFile->updateWithLocalFile($fileForLocalProcessing);

            $processedFileRepository = GeneralUtility::makeInstance(ProcessedFileRepository::class);
            $processedFileRepository->add($processedFile);
        }
    }
}
