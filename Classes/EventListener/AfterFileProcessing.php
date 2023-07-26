<?php
namespace Lemming\Imageoptimizer\EventListener;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\Event\AfterFileProcessingEvent;

class AfterFileProcessing extends AbstractEventListener
{
    public function __invoke(AfterFileProcessingEvent $event): void
    {
        if ($event->getProcessedFile()->isUpdated() && !$event->getProcessedFile()->usesOriginalFile()) {
            $typo3Version = GeneralUtility::makeInstance(Typo3Version::class);
            if($typo3Version->getMajorVersion() < 12) {
                $this->getService()->process(
                    Environment::getPublicPath() . '/' . ltrim($event->getProcessedFile()->getPublicUrl(true), '/'),
                    $event->getProcessedFile()->getExtension()
                );
            }
            else {
                $this->getService()->process(
                    Environment::getPublicPath() . '/' . ltrim($event->getProcessedFile()->getPublicUrl(), '/'),
                    $event->getProcessedFile()->getExtension()
                );
            }
        }
    }
}
