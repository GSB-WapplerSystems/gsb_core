<?php

namespace ITZBund\GsbCore\Tests\Unit\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\Site;

interface AfterTemplatesHaveBeenDeterminedEventInterface
{
    public function getRootline(): array;

    public function getSite(): ?Site;

    public function getRequest(): ?ServerRequestInterface;

    public function getTemplateRows(): array;

    public function getPidsBeforeSite(): array;
}
