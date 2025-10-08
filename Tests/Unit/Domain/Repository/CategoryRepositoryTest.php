<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Domain\Repository;

use ITZBund\GsbCore\Domain\Repository\CategoryRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CategoryRepositoryTest extends UnitTestCase
{
    protected CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = new CategoryRepository();
    }

    #[Test]
    #[TestDox('CategoryRepository can be instantiated')]
    public function categoryRepositoryCanBeInstantiated(): void
    {
        self::assertInstanceOf(CategoryRepository::class, $this->categoryRepository);
    }

    #[Test]
    #[TestDox('CategoryRepository extends Repository')]
    public function categoryRepositoryExtendsRepository(): void
    {
        self::assertInstanceOf(\TYPO3\CMS\Extbase\Persistence\Repository::class, $this->categoryRepository);
    }

    #[Test]
    #[TestDox('CategoryRepository has correct object type')]
    public function categoryRepositoryHasCorrectObjectType(): void
    {
        $reflection = new \ReflectionClass($this->categoryRepository);
        $property = $reflection->getProperty('objectType');
        $property->setAccessible(true);

        self::assertSame(\ITZBund\GsbCore\Domain\Model\Category::class, $property->getValue($this->categoryRepository));
    }
}
