<?php

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Patrick Schriner
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

namespace ITZBund\GsbCore\ViewHelpers;

use ITZBund\GsbCore\Domain\Repository\CategoryRepository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CategoryViewHelper extends AbstractViewHelper
{
    protected PersistenceManagerInterface $persistenceManager;

    public function __construct(PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('category', 'int', 'The uid of the category', false);
        $this->registerArgument('as', 'string', 'The name to give the variable', true);
    }

    public function render(): string
    {
        $as = $this->arguments['as'];
        $uid = $this->arguments['category'] ?? $this->renderChildren();
        $categoryRepository = GeneralUtility::makeInstance(CategoryRepository::class);
        $categoryRepository->injectPersistenceManager($this->persistenceManager);
        $category = $categoryRepository->findByUid($uid);
        $this->templateVariableContainer->add($as, $category);
        return '';
    }
}
