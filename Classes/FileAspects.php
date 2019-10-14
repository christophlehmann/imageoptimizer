<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FileAspects
{

    /**
     * @var OptimizeImageService
     */
    protected $service;

    public function __construct()
    {
        $this->service = GeneralUtility::makeInstance(OptimizeImageService::class);
    }

    /**
     * Called when a new file is uploaded
     *
     * @param string $targetFileName
     * @param Folder $targetFolder
     * @param string $sourceFilePath
     * @return string Modified target file name
     */
    public function addFile($targetFileName, Folder $targetFolder, $sourceFilePath)
    {
        $this->service->process($sourceFilePath, pathinfo($targetFileName)['extension'], true);
    }

    /**
     * Called when a file is overwritten
     *
     * @param FileInterface $file The file to replace
     * @param string $localFilePath The uploaded file
     */
    public function replaceFile(FileInterface $file, $localFilePath)
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
        if ($processedFile->isUpdated() === true && !$processedFile->usesOriginalFile()) {
            // ToDo: Find better possibility for getPublicUrl()
            $this->service->process(PATH_site . $processedFile->getPublicUrl(), $processedFile->getExtension());
        }
    }
}
