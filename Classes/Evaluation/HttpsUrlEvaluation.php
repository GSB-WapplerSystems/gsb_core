<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

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
     * Serverseitige Validierung (wie bisher)
     *
     * @throws Exception
     */
    public function evaluateFieldValue(mixed $value, mixed $is_in = null, mixed &$set = null): string
    {
        if (is_array($value)) {
            $value = reset($value);
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
     * Clientseitige Validierung.
     * Wird von der FormEngine automatisch vor dem Absenden aufgerufen.
     */
    public function returnFieldJS(): string
    {
        // nur zum Testen
        file_put_contents(
            sys_get_temp_dir() . '/https_eval_js_called.log',
            date('c') . " returnFieldJS() called\n",
            FILE_APPEND
        );

        return 'function(value){ return value; }';

//        return '
//            function(value) {
//                if (typeof value !== "string") {
//                    value = "" + value;
//                }
//                value = value.trim();
//
//                if (value === "") {
//                    // Leere Werte hier durchlassen – "required" kümmert sich separat darum
//                    return value;
//                }
//
//                // Einfache HTTPS-Validierung
//                var isValid = /^https:\/\/.+/i.test(value);
//
//                if (!isValid) {
//                    // Hier analog zum required-Dialog eine Meldung ausgeben.
//                    // Einfacher Fallback: Browser-Alert.
//                    alert("Die eingegebene URL ist ungültig. Es muss eine gültige HTTPS-URL sein.");
//                    // Ungültigen Wert verwerfen, Formular-Submit wird dadurch abgebrochen.
//                    return "";
//                }
//
//                return value;
//            }
//        ';
    }

    /**
     * Serverseitige Fehlermeldung (wenn jemand die JS-Validierung umgeht).
     *
     * @throws Exception
     */
    private function setFlashMessageForValidHttpsUrl(): void
    {
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
