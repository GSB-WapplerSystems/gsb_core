<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Willi Wehmeier
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VersionEndpoint implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUri()->getPath() !== '/api/version' || $request->getMethod() !== 'GET') {
            return $handler->handle($request);
        }

        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/plain; charset=utf-8');

        $response->getBody()->write($this->getGsbVersion());

        return $response;
    }

    protected function getGsbVersion(): string
    {
        $gsbFromEnv = getenv('GSB_VERSION');

        if ($gsbFromEnv === false) {
            return '11';
        }

        return (string)$gsbFromEnv;
    }
}
