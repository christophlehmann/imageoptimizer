<?php

declare(strict_types=1);

namespace Lemming\Imageoptimizer\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\Event\AfterFileProcessingEvent;

#[AsEventListener(identifier: 'ext-imageoptimizer/after-file-processing')]
class AfterFileProcessing extends AbstractEventListener
{
    public function __invoke(AfterFileProcessingEvent $event): void
    {
        if ($event->getProcessedFile()->isUpdated() && ! $event->getProcessedFile()->usesOriginalFile()) {
            $publicUrl = $event->getProcessedFile()->getPublicUrl();
            if ($publicUrl === null) {
                return;
            }
            if (str_starts_with($publicUrl, 'http://') || str_starts_with($publicUrl, 'https://')) {
                return;
            }
            $this->getService()->process(
                Environment::getPublicPath() . '/' . ltrim($publicUrl, '/'),
                $event->getProcessedFile()->getExtension()
            );
        }
    }
}
