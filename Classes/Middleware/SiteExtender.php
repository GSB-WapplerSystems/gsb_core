<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Middleware;

use ITZBund\GsbCore\Utility\ExtendSiteUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class SiteExtender implements MiddlewareInterface
{
    public function __construct(private readonly ExtendSiteUtility $extendSiteUtility) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('site') instanceof Site && $request->getAttribute('language') instanceof SiteLanguage) {
            $request = $request->withAttribute(
                'site',
                $this->extendSiteUtility->extendSiteWithLocalizationOverload($request->getAttribute('site'), $request->getAttribute('language'))
            );
        }

        return $handler->handle($request);
    }
}
