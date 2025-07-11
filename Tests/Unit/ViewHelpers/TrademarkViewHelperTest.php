<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Thorsten Müller
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\Event\GetTrademarkLogoEvent;
use ITZBund\GsbCore\Event\GetTrademarkTextEvent;
use ITZBund\GsbCore\ViewHelpers\TrademarkViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\ListenerProviderInterface;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TrademarkViewHelperTest extends UnitTestCase
{
    protected EventDispatcher $eventDispatcher;
    protected ListenerProviderInterface&MockObject $listenerProviderMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->listenerProviderMock = $this->createMock(ListenerProviderInterface::class);
        $this->eventDispatcher = new EventDispatcher(
            $this->listenerProviderMock
        );
    }

    /**
     * @param callable $callable
     * @param GetTrademarkLogoEvent|GetTrademarkTextEvent $event
     * @param string $argumentType
     * @param string $expectedResult
     */
    #[Test]
    #[DataProvider('callables')]
    #[TestDox('Dispatches the event $_dataName and changes it`s value from "" to "$expectedResult"')]
    public function trademarkViewHelperReturnsString(
        callable $callable,
        object $event,
        string $argumentType,
        string $expectedResult
    ): void {
        /*###########
        ## Arrange ##
        ###########*/
        $this->listenerProviderMock
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturnCallback(static function (object $event) use ($callable): iterable {
                yield $callable;
                yield $callable;
            });

        $trademarkViewHelper = new TrademarkViewHelper($this->eventDispatcher);

        /*#######
        ## Act ##
        #######*/
        $trademarkViewHelper->initializeArguments();
        $trademarkViewHelper->setArguments(['type' => $argumentType]);
        $assert = $trademarkViewHelper->render();

        /*##########
        ## Assert ##
        ##########*/
        self::assertEquals('', $event->getValue());
        self::assertEquals($expectedResult, $assert);
    }

    public static function callables(): \Generator
    {
        yield 'GetTrademarkTextEvent' => [
            [
                // Class + method
                new TrademarkData(),
                'getText',
            ],
            new GetTrademarkTextEvent(),
            'text',
            'GetTrademarkTextEvent dispatched',
        ];
        yield 'GetTrademarkLogoEvent' => [
            [
                // Class + method
                new TrademarkData(),
                'getLogo',
            ],
            new GetTrademarkLogoEvent(),
            'logo',
            'GetTrademarkLogoEvent dispatched',
        ];
        yield 'GetTrademarkLogoEvent but wrong type' => [
            [
                // Class + method
                new TrademarkData(),
                'getLogo',
            ],
            new GetTrademarkLogoEvent(),
            'wrongType',
            '',
        ];
    }
}

final class TrademarkData
{
    public function getText(GetTrademarkTextEvent $event): void
    {
        $event->setValue('GetTrademarkTextEvent dispatched');
    }

    public function getLogo(GetTrademarkLogoEvent $event): void
    {
        $event->setValue('GetTrademarkLogoEvent dispatched');
    }
}
