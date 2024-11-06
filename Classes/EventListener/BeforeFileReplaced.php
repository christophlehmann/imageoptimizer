<?php

declare(strict_types=1);

namespace Lemming\Imageoptimizer\EventListener;

use TYPO3\CMS\Core\Resource\Event\BeforeFileReplacedEvent;

class BeforeFileReplaced extends AbstractEventListener
{
    public function __invoke(BeforeFileReplacedEvent $event): void
    {
        $this->getService()->process(
            $event->getLocalFilePath(),
            $event->getFile()->getExtension(),
            true
        );
    }
}
