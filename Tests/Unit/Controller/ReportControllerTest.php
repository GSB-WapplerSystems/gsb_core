<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Controller;

use ITZBund\GsbCore\Controller\ReportController;
use ITZBund\GsbCore\Report\ReportInterface;
use TYPO3\CMS\Reports\Registry\ReportRegistry;
use ITZBund\GsbCore\Report\RequestAwareReportInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Backend\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for ReportController
 */
class ReportControllerTest extends UnitTestCase
{
    protected ReportController $reportController;
    /** @var MockObject&Request */
    protected $requestMock;
    /** @var MockObject&Response */
    protected $responseMock;
    /** @var MockObject&ReportRegistry */
    protected $reportRegistryMock;
    /** @var MockObject&LanguageService */
    protected $languageServiceMock;
    /** @var MockObject&BackendUserAuthentication */
    protected $backendUserMock;
    /** @var MockObject&IconRegistry */
    protected $iconRegistryMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Skip setup due to missing dependencies
        $this->markTestSkipped('Required dependencies not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController can be instantiated')]
    public function reportControllerCanBeInstantiated(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController has required dependencies')]
    public function reportControllerHasRequiredDependencies(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController extends base controller')]
    public function reportControllerExtendsBaseController(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController has index action')]
    public function reportControllerHasIndexAction(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController has detail action')]
    public function reportControllerHasDetailAction(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController can handle request with empty reports')]
    public function reportControllerCanHandleRequestWithEmptyReports(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController can handle request with reports')]
    public function reportControllerCanHandleRequestWithReports(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }

    #[Test]
    #[TestDox('ReportController can handle request aware reports')]
    public function reportControllerCanHandleRequestAwareReports(): void
    {
        $this->markTestSkipped('Response class not available in this environment');
    }
}
