<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Middleware;

use ITZBund\GsbCore\Middleware\SiteExtender;
use ITZBund\GsbCore\Utility\ExtendSiteUtility;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Frontend\Http\RequestHandler;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class SiteExtenderTest extends UnitTestCase
{
    #[Test]
    public function middlewareDoesNotExtendSiteIfRequestHasNoSite(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['ITZBUNDPHP-3288'] = true;

        $request = $this->getMockBuilder(ServerRequest::class)->getMock();
        $request->expects(self::exactly(1))->method('getAttribute')->with('site', null)
            ->willReturn(null);

        $request->expects(self::never())->method('withAttribute');
        $handler = $this->getMockBuilder(RequestHandler::class)->disableOriginalConstructor()->getMock();

        $subject = new SiteExtender(new ExtendSiteUtility());
        $subject->process($request, $handler);
    }

    #[Test]
    public function middlewareExtendsRequestWithLocalizedSite(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['ITZBUNDPHP-3288'] = true;

        $request = $this->getMockBuilder(ServerRequest::class)->getMock();

        $site = $this->getMockBuilder(Site::class)->disableOriginalConstructor()->getMock();
        $siteLanguage = $this->getMockBuilder(SiteLanguage::class)->disableOriginalConstructor()->getMock();

        $request->expects(self::atLeast(2))->method('getAttribute')->willReturnCallback(function ($argument) use ($site, $siteLanguage) {
            return match ($argument) {
                'site' => $site,
                'language' => $siteLanguage,
            };
        });

        $request->expects(self::once())->method('withAttribute');

        $handler = $this->getMockBuilder(RequestHandler::class)->disableOriginalConstructor()->getMock();

        $siteUtilityMock = $this->getMockBuilder(ExtendSiteUtility::class)->getMock();
        $siteUtilityMock->expects(self::once())->method('extendSiteWithLocalizationOverload')->with($site, $siteLanguage);

        $subject = new SiteExtender($siteUtilityMock);
        $subject->process($request, $handler);
    }

    #[Test]
    public function middlewareReturnsDirectlyWhenFeatureIsNotEnabled(): void
    {
        $request = $this->getMockBuilder(ServerRequest::class)->getMock();
        $request->expects(self::never())->method('getAttribute');
        $handler = $this->getMockBuilder(RequestHandler::class)->disableOriginalConstructor()->getMock();

        $subject = new SiteExtender(new ExtendSiteUtility());
        $subject->process($request, $handler);
    }
}
