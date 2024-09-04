<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\Middleware;

use ITZBund\GsbCore\Utility\ExtendSiteUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SiteExtender implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-3288')) {
            return $handler->handle($request);
        }

        if ($request->getAttribute('site') instanceof Site && $request->getAttribute('language') instanceof SiteLanguage) {
            $request = $request->withAttribute(
                'site',
                ExtendSiteUtility::extendSiteWithLocalizationOverload($request->getAttribute('site'), $request->getAttribute('language'))
            );
        }

        return $handler->handle($request);
    }
}
