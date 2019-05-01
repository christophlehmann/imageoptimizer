<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizeImageService
{

    const BINARY_NOT_FOUND = 'The Binary was not found in $PATH. $GLOBALS[\'TYPO3_CONF_VARS\'][\'SYS\'][\'binSetup\'] may help you. Good luck!';

    /**
     * @var string
     */
    protected $command;

    /**
     * @var array
     */
    protected $output = [];

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('imageoptimizer');
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
    }

    /**
     * Perform image optimization
     *
     * @param string $file
     * @param string $extension
     * @param bool $fileIsUploaded
     * @param bool $testMode
     * @throws BinaryNotFoundException
     * @return bool
     */
    public function process($file, $extension = null, $fileIsUploaded = false, $testMode = false)
    {
        $this->reset();

        if ($extension === null) {
            $pathinfo = pathinfo($file);
            if ($pathinfo['extension'] !== null) {
                $extension = $pathinfo['extension'];
            }
        }
        $extension = strtolower($extension);
        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }
        $when = $fileIsUploaded === true ? 'Upload' : 'Processing';

        if ((bool)$this->configuration[$extension . 'On' . $when] === false && $testMode === false) {
            return;
        }

        $binaryName = $this->configuration[$extension . 'Binary'];
        $binary = CommandUtility::getCommand(escapeshellcmd($binaryName));

        if (!is_string($binary)) {
            if (!$testMode) {
                $this->logger->error(self::BINARY_NOT_FOUND, [
                    'file' => $file,
                    'fileExtension' => $extension,
                    'binary' => $binaryName
                ]);
            }
            throw new BinaryNotFoundException('Binary ' . $binaryName . ' not found', 1488631746);
        }

        $parameters = $this->configuration[$extension . 'ParametersOn' . $when];
        $parameters = preg_replace('/[^A-Za-z0-9-%: =]/', '', $parameters);
        $parameters = preg_replace('/%s/', escapeshellarg($file), $parameters);

        $this->command = $binary . ' ' . $parameters . ' 2>&1';
        $returnValue = 0;
        CommandUtility::exec($this->command, $this->output, $returnValue);
        $executionWasSuccessful = $returnValue == 0;
        if (!$testMode) {
            $this->logger->log(
                $executionWasSuccessful ? LogLevel::INFO : LogLevel::ERROR,
                $executionWasSuccessful ? 'Optimization was successful' : 'Optimization failed',
                [
                    'file' => $file,
                    'fileExtension' => $extension,
                    'fileisUploaded' => $fileIsUploaded ? 1 : 0,
                    'command' => $this->command,
                    'returnValue' => $returnValue,
                    'output' => $this->output
                ]
            );

        }
        GeneralUtility::fixPermissions($file);

        return $executionWasSuccessful;
    }

    /**
     * Reset debug informations
     */
    protected function reset()
    {
        $this->command = '';
        $this->output = [];
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

}