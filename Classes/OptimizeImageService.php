<?php
namespace Lemming\Imageoptimizer;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizeImageService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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
     * @var array
     */
    protected $configuration;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('imageoptimizer');
    }

    /**
     * Perform image optimization
     *
     * @param string $file
     * @param string|null $extension
     * @param bool $fileIsUploaded
     * @param bool $testMode
     * @return bool
     * @throws BinaryNotFoundException
     */
    public function process(
        string $file,
        string $extension = null,
        bool $fileIsUploaded = false,
        bool $testMode = false
    ): bool {
        $this->reset();

        if (!\file_exists($file)) {
            return false;
        }

        if ($extension === null) {
            $pathinfo = pathinfo($file);
            if ($pathinfo['extension'] !== null) {
                $extension = $pathinfo['extension'];
            }
        }
        $extension = strtolower($extension);
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }
        $when = $fileIsUploaded === true ? 'Upload' : 'Processing';

        if ((bool)$this->configuration[$extension . 'On' . $when] === false && $testMode === false) {
            return false;
        }

        $binaryName = $this->configuration[$extension . 'Binary'];
        $binary = CommandUtility::getCommand(escapeshellcmd($binaryName));

        if (!is_string($binary)) {
            if (!$testMode) {
                $this->logger->log(LogLevel::ERROR, self::BINARY_NOT_FOUND, [
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
     *
     * @return void
     */
    protected function reset(): void
    {
        $this->command = '';
        $this->output = [];
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getOutput(): array
    {
        return $this->output;
    }
}
