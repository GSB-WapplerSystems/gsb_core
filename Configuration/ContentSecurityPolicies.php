<?php

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceKeyword;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceScheme;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use TYPO3\CMS\Core\Type\Map;

if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('cspForBitvTestTools')) {
    $bitvTestFormMutation = new Mutation(
        MutationMode::Extend,
        Directive::FormAction,
        new UriValue('https://*.w3.org'),
    );
    $bitvTestDefaultMutation = new Mutation(
        MutationMode::Extend,
        Directive::DefaultSrc,
        new UriValue('*.w3.org'),
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestFrameMutation = new Mutation(
        MutationMode::Extend,
        Directive::FrameSrc,
        new UriValue('*.w3.org'),
    );
    $bitvTestScriptSrcMutation = new Mutation(
        MutationMode::Extend,
        Directive::ScriptSrc,
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestScriptSrcElmMutation = new Mutation(
        MutationMode::Extend,
        Directive::ScriptSrcElem,
        SourceKeyword::unsafeInline,
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestStyleSrcElemMutation = new Mutation(
        MutationMode::Extend,
        Directive::StyleSrcElem,
        SourceKeyword::unsafeInline,
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
} else {
    $bitvTestFormMutation = new Mutation(
        MutationMode::Reduce,
        Directive::FormAction,
        new UriValue('*.w3.org'),
    );
    $bitvTestDefaultMutation = new Mutation(
        MutationMode::Reduce,
        Directive::DefaultSrc,
        new UriValue('*.w3.org'),
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestFrameMutation = new Mutation(
        MutationMode::Reduce,
        Directive::FrameSrc,
        new UriValue('*.w3.org'),
    );
    $bitvTestScriptSrcMutation = new Mutation(
        MutationMode::Reduce,
        Directive::ScriptSrc,
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestScriptSrcElmMutation = new Mutation(
        MutationMode::Reduce,
        Directive::ScriptSrcElem,
        SourceKeyword::unsafeInline,
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestStyleSrcElemMutation = new Mutation(
        MutationMode::Reduce,
        Directive::StyleSrcElem,
        SourceKeyword::unsafeInline,
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
}

if (\TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment()) {
    $inscureRequestMutation = new Mutation(
        MutationMode::Reduce,
        Directive::UpgradeInsecureRequests,
    );
} else {
    $inscureRequestMutation = new Mutation(
        MutationMode::Set,
        Directive::UpgradeInsecureRequests,
    );
}

$cspCollection = new MutationCollection(
    $inscureRequestMutation,
    $bitvTestFormMutation,
    $bitvTestDefaultMutation,
    $bitvTestFrameMutation,
    $bitvTestScriptSrcMutation,
    $bitvTestScriptSrcElmMutation,
    $bitvTestStyleSrcElemMutation,
    new Mutation(
        MutationMode::Set,
        Directive::BaseUri,
        SourceKeyword::self,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::FrameAncestors,
        SourceKeyword::self,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::WorkerSrc,
        SourceKeyword::self,
        SourceScheme::blob,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::FormAction,
        SourceKeyword::self,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::FontSrc,
        SourceKeyword::self,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::ConnectSrc,
        SourceKeyword::self,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::ObjectSrc,
        SourceKeyword::self,
        SourceKeyword::none,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::ManifestSrc,
        SourceKeyword::self,
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::MediaSrc,
        new UriValue('https://www.youtube.com'),
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::ScriptSrcElem,
        new UriValue('https://www.youtube.com'),
    )
);

return Map::fromEntries(
    [Scope::frontend(), $cspCollection]
);
