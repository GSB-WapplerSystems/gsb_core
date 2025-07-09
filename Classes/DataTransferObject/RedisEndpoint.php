<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
* This file is part of the package itzbund/gsb-public-notice of the GSB 11 Project by ITZBund.
*
* Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
* BMI/ITZBund. Author: Thorsten Müller
*
* It is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License, either version 3
* of the License, or any later version.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*
* The TYPO3 project - inspiring people to share!
*/

namespace ITZBund\GsbCore\DataTransferObject;

readonly final class RedisEndpoint
{
    public function __construct(
        private string $host,
        private int $port,
        private float $timeout,
        private ?string $persistentId
    ) {}

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    public function getPersistentId(): ?string
    {
        return $this->persistentId;
    }
}