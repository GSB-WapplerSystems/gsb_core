<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Evaluation;

use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\UrlValidator;

final class HttpsUrlEvaluation
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @throws Exception
     */
    public function evaluateFieldValue(mixed $value, mixed $is_in = null, mixed &$set = null): string
    {
        if (is_array($value)) {
            $value = reset($value); // falls Array, nimm erstes Element
        }
        $value = trim((string)$value);

        if ($value === '') {
            return '';
        }

        /** @var UrlValidator $validator */
        $validator = GeneralUtility::makeInstance(UrlValidator::class);
        $result = $validator->validate($value);

        if ($result->hasErrors() || !str_starts_with($value, 'https://')) {
            $this->setFlashMessageForValidHttpsUrl();
            $set = false;
            return '';
        }

        return $value;
    }

    /**
     * @throws Exception
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function setFlashMessageForValidHttpsUrl(): void
    {
        /** @var FlashMessage $message */
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $GLOBALS['LANG']->sL('LLL:EXT:gsb_core/Resources/Private/Language/locallang.xlf:error.noValidHttpsUrl'),
            '',
            ContextualFeedbackSeverity::ERROR,
            true
        );
        /** @var FlashMessageService $flashMessageService */
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $flashMessageService->getMessageQueueByIdentifier()->enqueue($message);
    }
}
