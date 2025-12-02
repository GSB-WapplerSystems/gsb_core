<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Evaluation;

use ITZBund\GsbCore\Evaluation\HttpsUrlEvaluation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Localization\Locale;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class HttpsUrlEvaluationTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    private HttpsUrlEvaluation $subject;
    private FlashMessageService&MockObject $flashMessageServiceMock;
    private FlashMessageQueue&MockObject $flashMessageQueueMock;
    private LanguageService&MockObject $languageServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new HttpsUrlEvaluation();

        // Mock LanguageService für $GLOBALS['LANG']
        $this->languageServiceMock = $this->createMock(LanguageService::class);
        $this->languageServiceMock->method('sL')
            ->willReturn('Keine gültige HTTPS-URL');
        $GLOBALS['LANG'] = $this->languageServiceMock;

        // Mock Locales (wird vom UrlValidator verwendet, ist ein Singleton)
        $localeMock = $this->createMock(Locale::class);
        $localesMock = $this->getMockBuilder(Locales::class)
            ->disableOriginalConstructor()
            ->getMock();
        $localesMock->method('createLocaleFromRequest')
            ->willReturn($localeMock);
        GeneralUtility::setSingletonInstance(Locales::class, $localesMock);

        // Mock LanguageServiceFactory (wird vom UrlValidator verwendet)
        // Verwende getMockBuilder mit enableProxyingToOriginalMethods(false) um alle Methoden zu mocken
        $languageServiceFactoryMock = $this->getMockBuilder(LanguageServiceFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createFromUserPreferences', 'create'])
            ->getMock();
        // Mock mit beliebigen Parametern
        $languageServiceFactoryMock->method('createFromUserPreferences')
            ->withAnyParameters()
            ->willReturn($this->languageServiceMock);
        $languageServiceFactoryMock->method('create')
            ->withAnyParameters()
            ->willReturn($this->languageServiceMock);
        GeneralUtility::addInstance(LanguageServiceFactory::class, $languageServiceFactoryMock);

        // Mock CacheManager (wird vom UrlValidator verwendet)
        $runtimeCacheMock = $this->createMock(FrontendInterface::class);
        $cacheManagerMock = $this->createMock(CacheManager::class);
        $cacheManagerMock->method('getCache')
            ->with('runtime')
            ->willReturn($runtimeCacheMock);
        GeneralUtility::setSingletonInstance(CacheManager::class, $cacheManagerMock);

        // Mock FlashMessageService (ist ein Singleton)
        $this->flashMessageServiceMock = $this->createMock(FlashMessageService::class);
        $this->flashMessageQueueMock = $this->createMock(FlashMessageQueue::class);
        $this->flashMessageServiceMock->method('getMessageQueueByIdentifier')
            ->willReturn($this->flashMessageQueueMock);
        GeneralUtility::setSingletonInstance(FlashMessageService::class, $this->flashMessageServiceMock);
    }

    protected function tearDown(): void
    {
        // Verbrauche die hinzugefügten Instanzen, damit der Integrity-Check erfolgreich ist
        // Die Factory wird vom UrlValidator verwendet, daher wird sie automatisch verbraucht
        // Für Tests ohne Validator-Aufruf müssen wir sie manuell verbrauchen
        try {
            GeneralUtility::makeInstance(LanguageServiceFactory::class);
        } catch (\Exception $e) {
            // Ignoriere Fehler, wenn die Instanz bereits verbraucht wurde
        }
        parent::tearDown();
    }

    #[Test]
    #[TestDox('evaluateFieldValue returns empty string for empty input')]
    public function evaluateFieldValueReturnsEmptyStringForEmptyInput(): void
    {
        $result = $this->subject->evaluateFieldValue('', null, $set);

        self::assertEquals('', $result);
        self::assertNull($set ?? null);
    }

    #[Test]
    #[TestDox('evaluateFieldValue returns empty string for whitespace-only input')]
    public function evaluateFieldValueReturnsEmptyStringForWhitespaceOnlyInput(): void
    {
        $result = $this->subject->evaluateFieldValue('   ', null, $set);

        self::assertEquals('', $result);
        self::assertNull($set ?? null);
    }

    #[Test]
    #[TestDox('evaluateFieldValue handles array input by taking first element')]
    public function evaluateFieldValueHandlesArrayInputByTakingFirstElement(): void
    {
        $result = $this->subject->evaluateFieldValue(['https://example.com', 'https://other.com'], null, $set);

        self::assertEquals('https://example.com', $result);
    }

    #[Test]
    #[TestDox('evaluateFieldValue returns valid HTTPS URL')]
    public function evaluateFieldValueReturnsValidHttpsUrl(): void
    {
        $result = $this->subject->evaluateFieldValue('https://example.com', null, $set);

        self::assertEquals('https://example.com', $result);
        self::assertNull($set ?? null);
    }

    #[Test]
    #[TestDox('evaluateFieldValue trims whitespace from valid HTTPS URL')]
    public function evaluateFieldValueTrimsWhitespaceFromValidHttpsUrl(): void
    {
        $result = $this->subject->evaluateFieldValue('  https://example.com  ', null, $set);

        self::assertEquals('https://example.com', $result);
    }

    #[Test]
    #[TestDox('evaluateFieldValue returns empty string and sets flash message for HTTP URL')]
    public function evaluateFieldValueReturnsEmptyStringAndSetsFlashMessageForHttpUrl(): void
    {
        $this->flashMessageQueueMock->expects(self::once())
            ->method('enqueue')
            ->with(self::isInstanceOf(FlashMessage::class));

        $result = $this->subject->evaluateFieldValue('http://example.com', null, $set);

        self::assertEquals('', $result);
        self::assertFalse($set);
    }

    #[Test]
    #[TestDox('evaluateFieldValue handles complex valid HTTPS URLs')]
    public function evaluateFieldValueHandlesComplexValidHttpsUrls(): void
    {
        $testCases = [
            'https://example.com/path/to/page',
            'https://example.com:8080/path?query=value',
            'https://subdomain.example.com',
            'https://example.com/path#fragment',
        ];

        foreach ($testCases as $testUrl) {
            $result = $this->subject->evaluateFieldValue($testUrl, null, $set);

            self::assertEquals($testUrl, $result, "Failed for URL: {$testUrl}");
        }
    }

    #[Test]
    #[TestDox('evaluateFieldValue handles array with empty first element')]
    public function evaluateFieldValueHandlesArrayWithEmptyFirstElement(): void
    {
        $result = $this->subject->evaluateFieldValue(['', 'https://example.com'], null, $set);

        self::assertEquals('', $result);
        self::assertNull($set ?? null);
    }

    #[Test]
    #[TestDox('evaluateFieldValue handles array with whitespace-only first element')]
    public function evaluateFieldValueHandlesArrayWithWhitespaceOnlyFirstElement(): void
    {
        $result = $this->subject->evaluateFieldValue(['   ', 'https://example.com'], null, $set);

        self::assertEquals('', $result);
        self::assertNull($set ?? null);
    }

    #[Test]
    #[TestDox('evaluateFieldValue handles array with HTTP URL as first element')]
    public function evaluateFieldValueHandlesArrayWithHttpUrlAsFirstElement(): void
    {
        $this->flashMessageQueueMock->expects(self::once())
            ->method('enqueue')
            ->with(self::isInstanceOf(FlashMessage::class));

        $result = $this->subject->evaluateFieldValue(['http://example.com', 'https://example.com'], null, $set);

        self::assertEquals('', $result);
        self::assertFalse($set);
    }

    #[Test]
    #[TestDox('evaluateFieldValue handles null value')]
    public function evaluateFieldValueHandlesNullValue(): void
    {
        $result = $this->subject->evaluateFieldValue(null, null, $set);

        self::assertEquals('', $result);
        self::assertNull($set ?? null);
    }

}
