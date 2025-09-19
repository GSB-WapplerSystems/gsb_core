<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2025 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Christian Rath-Ulrich
  * higli inspired by t3g/usercentrics
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Settings\Type;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use TYPO3\CMS\Core\Settings\SettingDefinition;
use TYPO3\CMS\Core\Settings\SettingsTypeInterface;
use TYPO3\CMS\Core\Utility\MathUtility;

#[AsTaggedItem(index: 'file')]
readonly class FileType implements SettingsTypeInterface
{
    public function __construct(
        protected LoggerInterface $logger,
    ) {}

    /**
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate(mixed $value, SettingDefinition $definition): bool
    {
        if (is_int($value)) {
            return true;
        }

        if (is_string($value) && MathUtility::canBeInterpretedAsInteger($value)) {
            return true;
        }

        return false;
    }

    public function transformValue(mixed $value, SettingDefinition $definition): int
    {
        if (!$this->validate($value, $definition)) {
            $this->logger->warning('Setting validation field, reverting to default: {key}', ['key' => $definition->key]);
            return (int)$definition->default;
        }

        return (int)$value;
    }

    public function getJavaScriptModule(): string
    {
        return '@itzbund/gsb-core/site-sets-type/file.js';
    }
}
