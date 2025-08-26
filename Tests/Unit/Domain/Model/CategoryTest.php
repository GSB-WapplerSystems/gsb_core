<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Domain\Model;

use ITZBund\GsbCore\Domain\Model\Category;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CategoryTest extends UnitTestCase
{
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    #[Test]
    #[TestDox('Category can be instantiated')]
    public function categoryCanBeInstantiated(): void
    {
        self::assertInstanceOf(Category::class, $this->category);
    }

    #[Test]
    #[TestDox('Category extends AbstractEntity')]
    public function categoryExtendsAbstractEntity(): void
    {
        self::assertInstanceOf(\TYPO3\CMS\Extbase\DomainObject\AbstractEntity::class, $this->category);
    }

    #[Test]
    #[TestDox('Title can be set and retrieved')]
    public function titleCanBeSetAndRetrieved(): void
    {
        $title = 'Test Category Title';
        $this->category->_setProperty('title', $title);

        self::assertSame($title, $this->category->getTitle());
    }

    #[Test]
    #[TestDox('Title returns string type')]
    public function titleReturnsStringType(): void
    {
        $title = 'Test Category Title';
        $this->category->_setProperty('title', $title);

        self::assertIsString($this->category->getTitle());
    }

    #[Test]
    #[TestDox('Title can handle empty string')]
    public function titleCanHandleEmptyString(): void
    {
        $title = '';
        $this->category->_setProperty('title', $title);

        self::assertSame($title, $this->category->getTitle());
    }

    #[Test]
    #[TestDox('Title can handle special characters')]
    public function titleCanHandleSpecialCharacters(): void
    {
        $title = 'Kategorie mit Umlauten: äöüß';
        $this->category->_setProperty('title', $title);

        self::assertSame($title, $this->category->getTitle());
    }

    #[Test]
    #[TestDox('Title can handle unicode characters')]
    public function titleCanHandleUnicodeCharacters(): void
    {
        $title = 'Category with unicode: 🚀🌟🎉';
        $this->category->_setProperty('title', $title);

        self::assertSame($title, $this->category->getTitle());
    }

    #[Test]
    #[TestDox('Title can handle very long strings')]
    public function titleCanHandleVeryLongStrings(): void
    {
        $title = str_repeat('a', 1000);
        $this->category->_setProperty('title', $title);

        self::assertSame($title, $this->category->getTitle());
    }
}
