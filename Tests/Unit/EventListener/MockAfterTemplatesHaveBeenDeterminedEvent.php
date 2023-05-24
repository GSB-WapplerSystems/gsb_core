<?php

namespace ITZBund\GsbCore\Tests\Unit\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\Site;

class MockAfterTemplatesHaveBeenDeterminedEvent implements AfterTemplatesHaveBeenDeterminedEventInterface
{
    private array $rootline;
    private ?ServerRequestInterface $request;
    private array $templateRows;
    private ?Site $site;
    private array $pidsBeforeSite = [];

    public function __construct(array $rootline, ?ServerRequestInterface $request, array $templateRows, Site $site, array $pidsBeforeSite)
    {
        $this->rootline = $rootline;
        $this->request = $request;
        $this->templateRows = $templateRows;
        $this->site = $site;
        $this->pidsBeforeSite = $pidsBeforeSite;
    }

    public function getPidsBeforeSite(): array
    {
        return $this->pidsBeforeSite;
    }

    public function getRootline(): array
    {
        return $this->rootline;
    }

    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    public function getTemplateRows(): array
    {
        return $this->templateRows;
    }

    public function setTemplateRows(array $templateRows): void
    {
        $this->templateRows = $templateRows;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }
}
