<?php

declare(strict_types=1);

namespace Lemming\Imageoptimizer;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizeImageService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const BINARY_NOT_FOUND = 'The Binary was not found in $PATH. $GLOBALS[\'TYPO3_CONF_VARS\'][\'SYS\'][\'binSetup\'] may help you.';

    private string $command;

    /**
     * @var array<int, string>
     */
    private array $output = [];

    private ExtensionConfiguration $extensionConfiguration;

    /**
     * DI does NOT work in Install Tool context!
     */
    public function __construct()
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    /**
     * Perform image optimization
     */
    public function process(
        string $file,
        string $extension = null,
        bool $fileIsUploaded = false,
        bool $testMode = false
    ): bool {
        $this->reset();

        if (! file_exists($file)) {
            return false;
        }

        $configuration = $this->extensionConfiguration->get('imageoptimizer');

        if ($extension === null) {
            $pathInfo = pathinfo($file);
            if ($pathInfo['extension'] !== null) {
                $extension = $pathInfo['extension'];
            }
        }
        $extension = strtolower($extension);
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        $when = $fileIsUploaded === true ? 'Upload' : 'Processing';
        $on = $extension . 'On' . $when;
        if (! isset($configuration[$on])) {
            return false;
        }

        if ((bool) $configuration[$on] === false && $testMode === false) {
            return false;
        }

        $binaryName = $configuration[$extension . 'Binary'];
        $binary = CommandUtility::getCommand(escapeshellcmd($binaryName));

        if (! is_string($binary)) {
            if (! $testMode) {
                $this->logger->log(LogLevel::ERROR, self::BINARY_NOT_FOUND, [
                    'file' => $file,
                    'fileExtension' => $extension,
                    'binary' => $binaryName,
                ]);
            }
            throw new BinaryNotFoundException('Binary ' . $binaryName . ' not found', 1488631746);
        }

        $parametersOn = $extension . 'ParametersOn' . $when;
        $parameters = (string) $configuration[$parametersOn];
        $parameters = (string) preg_replace('/[^A-Za-z0-9-%: =]/', '', $parameters);
        $parameters = (string) preg_replace('/%s/', escapeshellarg($file), $parameters);

        $this->command = $binary . ' ' . $parameters . ' 2>&1';
        $returnValue = 0;
        CommandUtility::exec($this->command, $this->output, $returnValue);
        $executionWasSuccessful = $returnValue === 0;
        if (! $testMode) {
            $this->logger->log(
                $executionWasSuccessful ? LogLevel::INFO : LogLevel::ERROR,
                $executionWasSuccessful ? 'Optimization was successful' : 'Optimization failed',
                [
                    'file' => $file,
                    'fileExtension' => $extension,
                    'fileIsUploaded' => $fileIsUploaded ? 1 : 0,
                    'command' => $this->command,
                    'returnValue' => $returnValue,
                    'output' => $this->output,
                ]
            );
        }
        GeneralUtility::fixPermissions($file);

        return $executionWasSuccessful;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return array<int, string>
     */
    public function getOutput(): array
    {
        return $this->output;
    }

    protected function reset(): void
    {
        $this->command = '';
        $this->output = [];
    }
}
