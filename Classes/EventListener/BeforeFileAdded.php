<?php
namespace Lemming\Imageoptimizer\EventListener;

use TYPO3\CMS\Core\Resource\Event\BeforeFileAddedEvent;

class BeforeFileAdded extends AbstractEventListener
{
    public function __invoke(BeforeFileAddedEvent $event): void
    {
        $this->getService()->process(
            $event->getSourceFilePath(),
            \pathinfo($event->getFileName(), PATHINFO_EXTENSION),
            true
        );
    }
}
