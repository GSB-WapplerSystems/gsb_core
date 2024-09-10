<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Resource\OnlineMedia\Helpers;

use ITZBund\GsbCore\Resource\OnlineMedia\Helpers\GenericExternalVideoHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class GenericExternalVideoHelperTest extends UnitTestCase
{
    protected GenericExternalVideoHelper $genericExternalVideoHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genericExternalVideoHelper = new GenericExternalVideoHelper('externalvideo');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->genericExternalVideoHelper);
    }

    #[Test]
    public function getMetaDataReturnsEmptyArray()
    {
        $resourceStorage = $this->getMockBuilder(ResourceStorage::class)->disableOriginalConstructor()->getMock();
        $file = new File(['size' => 50, 'uid' => 42], $resourceStorage);
        self::assertEquals([], $this->genericExternalVideoHelper->getMetaData($file));
    }

    public static function getAllowedDomains()
    {
        yield 'Fails if domain array is null' => [ null, 'https://gsb.de/test/test.mp4', false];
        yield 'Fails if domain array is empty' => [ [], 'https://gsb.de/test/test.mp4', false];
        yield 'Matches if simple domain' => [ ['gsb.de'], 'https://gsb.de/test/test.mp4', true];
        yield 'Fails if empty' => [ [], 'https://gsb.de/test/test.mp4', false];
        yield 'Matches if multiple domains' => [ ['gsb.de', 'gsb1.de'], 'https://gsb.de/test/test.mp4', true];
        yield 'Fails with wrong domains' => [ ['gsb1.de'], 'https://gsb.de/test/test.mp4', false];
        yield 'Matches if wildcard domains' => [ ['*.gsb.de'], 'https://media.gsb.de/test/test.mp4', true];
        yield 'Fails if wildcard domains, but no subdomain' => [ ['*.gsb.de'], 'https://gsb.de/test/test.mp4', false];
        yield 'Matches with protocol' => [ ['https://media.gsb.de'], 'https://media.gsb.de/test/test.mp4', true];
        yield 'Fails with protocol' => [ ['http://media.gsb.de'], 'https://media.gsb.de/test/test.mp4', false];
        yield 'Matches with subsub domain' => [ ['*.gsb.de'], 'https://media.media.gsb.de/test/test.mp4', true];
        yield 'Does not work as regex' => [ ['*sb.de'], 'https://media.media.gsb.de/test/test.mp4', false];
    }

    #[Test]
    #[DataProvider('getAllowedDomains')]
    public function matchesAllowedDomainsMatches($conf, $url, $expected)
    {
        $reflectionClass = new \ReflectionClass($this->genericExternalVideoHelper);
        $matchesAllowedDomain = $reflectionClass->getMethod('matchesAllowedDomains');
        $matchesAllowedDomain->setAccessible(true);

        self::assertEquals($expected, $matchesAllowedDomain->invoke($this->genericExternalVideoHelper, $url, $conf));
    }
}
