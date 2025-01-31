<?php

declare(strict_types=1);

namespace Lemming\Imageoptimizer\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Resource\Event\BeforeFileAddedEvent;

#[AsEventListener(identifier: 'ext-imageoptimizer/before-file-added')]
class BeforeFileAdded extends AbstractEventListener
{
    public function __invoke(BeforeFileAddedEvent $event): void
    {
        $this->getService()->process(
            $event->getSourceFilePath(),
            pathinfo($event->getFileName(), PATHINFO_EXTENSION),
            true
        );
    }
}
