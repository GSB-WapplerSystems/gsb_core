<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Thorsten Müller
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\Domain\Model\Category;
use ITZBund\GsbCore\Domain\Repository\CategoryRepository;
use ITZBund\GsbCore\ViewHelpers\CategoryViewHelper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;

class CategoryViewHelperTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;
    protected ?Category $category = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
        $this->category->_setProperty('title', 'Category title');

        $categoryRepositoryMock = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findByUid', 'injectPersistenceManager'])
            ->getMock();
        $categoryRepositoryMock
            ->method('findByUid')
            ->willReturn($this->category);

        GeneralUtility::setSingletonInstance(CategoryRepository::class, $categoryRepositoryMock);
    }

    #[Test]
    #[TestDox('Render method sets new fluid variable containing a category model')]
    public function renderSetsNewVariable(): void
    {
        /*###########
        ## Arrange ##
        ###########*/
        /** PersistenceManager **/
        $persistenceManagerMock = $this->getMockBuilder(PersistenceManagerInterface::class)
            ->getMock();

        /** RenderingContext **/
        $renderingContext = new RenderingContext();
        $viewHelper = new CategoryViewHelper($persistenceManagerMock);

        /*#######
        ## Act ##
        #######*/
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->initializeArguments();
        $viewHelper->setArguments(['category' => 1, 'as' => 'catVariable']);
        $viewHelper->render();

        /*##########
        ## Assert ##
        ##########*/
        $ref = new \ReflectionClass($viewHelper);
        $property = $ref->getProperty('templateVariableContainer');
        $assert = $property->getValue($viewHelper);

        self::assertArrayHasKey('catVariable', $assert);
        self::assertSame($this->category, $assert['catVariable']);
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
    }
}
