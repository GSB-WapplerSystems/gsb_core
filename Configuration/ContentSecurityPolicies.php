<?php

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
    $bitvTestScriptSrcElmMutation = new Mutation(
        MutationMode::Extend,
        Directive::ScriptSrcElem,
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestStyleSrcElemMutation = new Mutation(
        MutationMode::Extend,
        Directive::StyleSrcElem,
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
    $bitvTestScriptSrcElmMutation = new Mutation(
        MutationMode::Reduce,
        Directive::ScriptSrcElem,
        new UriValue('cdn.jsdelivr.net'),
        new UriValue('ajax.googleapis.com'),
    );
    $bitvTestStyleSrcElemMutation = new Mutation(
        MutationMode::Reduce,
        Directive::StyleSrcElem,
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
