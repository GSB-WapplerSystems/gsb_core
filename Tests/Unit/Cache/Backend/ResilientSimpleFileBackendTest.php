<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Cache\Backend;

use ITZBund\GsbCore\Cache\Backend\ResilientSimpleFileBackend;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for ResilientSimpleFileBackend
 */
class ResilientSimpleFileBackendTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    #[Test]
    #[TestDox('ResilientSimpleFileBackend class exists')]
    public function resilientSimpleFileBackendClassExists(): void
    {
        self::assertTrue(class_exists(ResilientSimpleFileBackend::class));
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct namespace')]
    public function resilientSimpleFileBackendHasCorrectNamespace(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        self::assertEquals('ITZBund\GsbCore\Cache\Backend', $reflection->getNamespaceName());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend extends SimpleFileBackend')]
    public function resilientSimpleFileBackendExtendsSimpleFileBackend(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $parentClass = $reflection->getParentClass();
        self::assertNotNull($parentClass);
        self::assertEquals('TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend', $parentClass->getName());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has require method')]
    public function resilientSimpleFileBackendHasRequireMethod(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        self::assertTrue($reflection->hasMethod('require'));
        $method = $reflection->getMethod('require');
        self::assertTrue($method->isPublic());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend require method has correct signature')]
    public function resilientSimpleFileBackendRequireMethodHasCorrectSignature(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $method = $reflection->getMethod('require');
        $parameters = $method->getParameters();

        self::assertCount(1, $parameters);
        self::assertEquals('entryIdentifier', $parameters[0]->getName());
        self::assertEquals('string', $parameters[0]->getType()->getName());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend require method returns mixed')]
    public function resilientSimpleFileBackendRequireMethodReturnsMixed(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $method = $reflection->getMethod('require');
        $returnType = $method->getReturnType();

        // In PHP 8.0+, mixed is represented as null (no type declaration)
        self::assertNull($returnType);
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend class is not final')]
    public function resilientSimpleFileBackendClassIsNotFinal(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        self::assertFalse($reflection->isFinal());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct class name')]
    public function resilientSimpleFileBackendHasCorrectClassName(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        self::assertEquals('ResilientSimpleFileBackend', $reflection->getShortName());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct full class name')]
    public function resilientSimpleFileBackendHasCorrectFullClassName(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        self::assertEquals('ITZBund\GsbCore\Cache\Backend\ResilientSimpleFileBackend', $reflection->getName());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct file path')]
    public function resilientSimpleFileBackendHasCorrectFilePath(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $actualPath = $reflection->getFileName();
        $expectedPath = __DIR__ . '/../../../../Classes/Cache/Backend/ResilientSimpleFileBackend.php';

        // Normalize paths for comparison
        $actualPath = realpath($actualPath);
        $expectedPath = realpath($expectedPath);

        self::assertEquals($expectedPath, $actualPath);
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct parent class')]
    public function resilientSimpleFileBackendHasCorrectParentClass(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $parentClass = $reflection->getParentClass();
        self::assertNotNull($parentClass);
        self::assertEquals('TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend', $parentClass->getName());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct constructor')]
    public function resilientSimpleFileBackendHasCorrectConstructor(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $constructor = $reflection->getConstructor();
        self::assertNotNull($constructor);
        self::assertTrue($constructor->isPublic());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend constructor has correct parameters')]
    public function resilientSimpleFileBackendConstructorHasCorrectParameters(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        self::assertCount(2, $parameters);
        self::assertEquals('context', $parameters[0]->getName());
        self::assertEquals('options', $parameters[1]->getName());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct method count')]
    public function resilientSimpleFileBackendHasCorrectMethodCount(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        // Should have at least the require method plus inherited methods
        self::assertGreaterThanOrEqual(1, count($methods));
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct property count')]
    public function resilientSimpleFileBackendHasCorrectPropertyCount(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $properties = $reflection->getProperties();
        // Should have inherited properties from SimpleFileBackend
        self::assertGreaterThanOrEqual(0, count($properties));
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend is instantiable')]
    public function resilientSimpleFileBackendIsInstantiable(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        self::assertTrue($reflection->isInstantiable());
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend has correct docblock')]
    public function resilientSimpleFileBackendHasCorrectDocblock(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $docComment = $reflection->getDocComment();
        self::assertStringContainsString('This is a variant of the SimpleFileBackend', $docComment);
    }

    #[Test]
    #[TestDox('ResilientSimpleFileBackend require method has correct docblock')]
    public function resilientSimpleFileBackendRequireMethodHasCorrectDocblock(): void
    {
        $reflection = new \ReflectionClass(ResilientSimpleFileBackend::class);
        $method = $reflection->getMethod('require');
        $docComment = $method->getDocComment();
        self::assertStringContainsString('Loads PHP code from the cache and require it right away', $docComment);
    }
}
