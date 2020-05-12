<?php
namespace Lemming\Imageoptimizer\EventListener;

use TYPO3\CMS\Core\Resource\Event\BeforeFileReplacedEvent;

class BeforeFileReplaced extends AbstractEventListener
{
    public function __invoke(BeforeFileReplacedEvent $event): void
    {
        $this->service->process(
            $event->getLocalFilePath(),
            $event->getFile()->getExtension(),
            true
        );
    }
}
