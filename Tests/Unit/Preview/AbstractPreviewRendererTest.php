<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Preview;

use Codeception\Test\Unit;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AbstractPreviewRendererTest extends Unit
{
    protected \UnitTester $tester;

    protected $subject;

    protected function setUp(): void
    {
        $beuser = \Codeception\Stub::make(
            \TYPO3\CMS\Core\Authentication\BackendUserAuthentication::class,
            [
                //'getRecord' => $this->getDummyDataArray(),
            ]
        );
        $GLOBALS['BE_USER'] = $beuser;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['IconFactory']['recordStatusMapping'] = [
            'hidden' => 'overlay-hidden',
            'fe_group' => 'overlay-restricted',
            'starttime' => 'overlay-scheduled',
            'endtime' => 'overlay-endtime',
            'futureendtime' => 'overlay-scheduled',
            'readonly' => 'overlay-readonly',
            'deleted' => 'overlay-deleted',
            'missing' => 'overlay-missing',
            'translated' => 'overlay-translated',
            'protectedSection' => 'overlay-includes-subpages',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['IconFactory']['overlayPriorities'] = [
            'hidden',
            'starttime',
            'endtime',
            'futureendtime',
            'protectedSection',
            'fe_group',
        ];
        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->disableOriginalConstructor()->getMock();
        $iconRegistry = $this->getMockBuilder(IconRegistry::class)->disableOriginalConstructor()->getMock();
        $container = $this->getMockBuilder(ContainerInterface::class)->disableOriginalConstructor()->getMock();

        $iconFatory = new IconFactory($eventDispatcher, $iconRegistry, $container);
        GeneralUtility::addInstance(IconFactory::class, $iconFatory);
    }
}
