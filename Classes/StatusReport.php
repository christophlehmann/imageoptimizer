<?php
namespace Lemming\Imageoptimizer;

use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Reports\Status;

class StatusReport implements \TYPO3\CMS\Reports\StatusProviderInterface {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager = null;

	/**
	 * Default constructor
	 */
	public function __construct()
	{
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
	}

	/**
	 * Determines if the needed binaries are found
	 *
	 * @return array List of statuses
	 */
	public function getStatus() {
		$status['optipng'] = $this->checkBinary('optipng');
		$status['jpegtran'] = $this->checkBinary('jpegtran');
		$status['jpegoptim'] = $this->checkBinary('jpegoptim');

		return $status;
	}

	/**
	 * Check if binary is found
	 *
	 * @return \TYPO3\CMS\Reports\Status
	 */
	protected function checkBinary($name) {
		$binary = CommandUtility::getCommand($name);

		/** @var $status \TYPO3\CMS\Reports\Status */
		$status = $this->objectManager->get(
			Status::class,
			$name . ' executable',
			is_string($binary) ? 'Found: ' . $binary : 'Not found',
			'',
			is_string($binary) ? Status::OK : Status::ERROR
		);

		return $status;
	}
}
