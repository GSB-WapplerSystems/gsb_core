<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    // UnitTests
    ->ignoreErrorsOnPackage('phpunit/phpunit', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/testing-framework', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    // GSB-Core Base
    ->ignoreErrorsOnPackage('b13/container', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('b13/host-variants', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('derhansen/form_crshield', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('helhum/config-loader', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('helhum/typo3-console', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('ichhabrecht/content-defender', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('kitzberger/form-mailtext', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/serializer-pack', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-belog', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-beuser', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-dashboard', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-extensionmanager', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-filelist', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-filemetadata', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-fluid', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-fluid-styled-content', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-felogin', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-form', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-impexp', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-info', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-linkvalidator', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-lowlevel', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-opendocs', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-recycler', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-redirects', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-rte-ckeditor', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-scheduler', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-seo', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-setup', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-sys-note', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-tstemplate', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-viewpage', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3/cms-workspaces', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('wapplersystems/multisite-belogin', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('wapplersystems/save_and_close', [ErrorType::UNUSED_DEPENDENCY])
    // included by bacon/bacon-qr-code, which is included by typo3/cms-core
    ->ignoreErrorsOnPackage('dasprid/enum', [ErrorType::SHADOW_DEPENDENCY])
    // included by typo3/cms-core
    ->ignoreErrorsOnPackage('psr/event-dispatcher', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('psr/http-factory', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('psr/http-message', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('psr/http-server-handler', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('psr/http-server-middleware', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('psr/log', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/console', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/dependency-injection', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/finder', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/yaml', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('typo3fluid/fluid', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('doctrine/dbal', [ErrorType::SHADOW_DEPENDENCY])
    // included by symfony/serializer-pack
    ->ignoreErrorsOnPackage('symfony/serializer', [ErrorType::SHADOW_DEPENDENCY])
;
