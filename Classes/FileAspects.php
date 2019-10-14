<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Folder;

class FileAspects
{

    /**
     * @var OptimizeImageService
     */
    protected $service;

    /**
     * @param OptimizeImageService $optimizeImageService
     */
    public function __construct(OptimizeImageService $optimizeImageService)
    {
        $this->service = $optimizeImageService;
    }

    /**
     * Called when a new file is uploaded
     *
     * @param string $targetFileName
     * @param Folder $targetFolder
     * @param string $sourceFilePath
     * @return string Modified target file name
     * @throws BinaryNotFoundException
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
     * @throws BinaryNotFoundException
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
     * @throws BinaryNotFoundException
     */
    public function processFile($fileProcessingService, $driver, $processedFile)
    {
        if ($processedFile->isUpdated() === true && !$processedFile->usesOriginalFile()) {
            $this->service->process(Environment::getPublicPath() . '/' . $processedFile->getPublicUrl(), $processedFile->getExtension());
        }
    }
}
