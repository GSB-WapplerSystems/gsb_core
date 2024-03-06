<?php

namespace ITZBund\GsbCore\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Category extends AbstractEntity
{
    /** @var string */
    protected $title;

    public function getTitle(): string
    {
        return $this->title;
    }
}
