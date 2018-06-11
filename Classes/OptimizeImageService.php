<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizeImageService {

	/**
	 * Initialize
	 */
	public function __construct() {
		$this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imageoptimizer']);
	}

	/**
	 * Perform image optimization
	 *
	 * @param string $file
	 * @param string $extension
	 * @param boolean $fileIsUploaded
	 */
	public function process($file, $extension = NULL, $fileIsUploaded = FALSE) {
		if ($extension === NULL) {
			$pathinfo = pathinfo($file);
			if ($pathinfo['extension'] !== NULL) {
				$extension = $pathinfo['extension'];
			}
		}
		$extension = strtolower($extension);
		if ($extension == 'jpeg') {
			$extension = 'jpg';
		}
		$when = $fileIsUploaded === TRUE ? 'Upload' : 'Processing';

		if ((bool)$this->configuration[$extension . 'On' . $when] === FALSE) {
			return;
		}

		$binary = CommandUtility::getCommand(escapeshellcmd($this->configuration[$extension . 'Binary']));

		if (!is_string($binary)) {
			throw new \RuntimeException('Binary ' . $binary . ' not found', 1488631746);
		}

		$parameters = $this->configuration[$extension . 'ParametersOn' . $when];
		$parameters = preg_replace('/[^A-Za-z0-9-%: =]/', "", $parameters);
		$parameters = preg_replace('/%s/', $file, $parameters);

		$command = $binary . ' ' . $parameters . ' 2>&1';
		$output = [];
		$returnValue = 0;
		CommandUtility::exec($command, $output, $returnValue);
		if ((bool)$this->configuration['debug'] === TRUE && is_object($GLOBALS['BE_USER'])) {
			$error = $returnValue == 0 ? 0 : 1;
			$GLOBALS['BE_USER']->writelog(4, 0, $error, 0, $command . ' exited with ' . $returnValue . '. Output was: ' . implode(' ', $output), $output);
		}
		
		GeneralUtility::fixPermissions($file);
	}
}
