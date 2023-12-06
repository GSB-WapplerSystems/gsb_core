<?php

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Patrick Schriner <patrick.schriner@diemedialen.de> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Log\Writer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Writer\FileWriter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Specialized writer that uses Symfony Serializer to encode the log data as json
 *
 * Best used in containers when directing the output to php://stderr
 */
class JsonFileWriter extends FileWriter
{
    /**
     * Writes the log record
     *
     * @param LogRecord $record Log record
     * @return WriterInterface $this
     * @throws \RuntimeException
     */
    public function writeLog(LogRecord $record)
    {
        $context = $record->getData();
        $message = $record->getMessage();

        if (!empty($context)) {
            // Fold an exception into the message, and string-ify it into context so it can be jsonified.
            if (isset($context['exception']) && $context['exception'] instanceof \Throwable) {
                $message .= $this->formatException($context['exception']);
                $context['exception'] = (string)$context['exception'];
            }
        }

        $payload = [
            'date' => date('r', (int)$record->getCreated()),
            'level' => strtoupper($record->getLevel()),
            'requestId' => $record->getRequestId(),
            'compontent' => $record->getComponent(),
            'message' => $this->interpolate($message, $context),
            'context' => $context,
        ];

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = GeneralUtility::makeInstance(Serializer::class, $normalizers, $encoders);

        /* we don't want to stumble over warnings like undefined keys */
        $oldReporting = (int)ini_get('error_reporting');
        error_reporting(E_ERROR);
        $jsonString = $serializer->serialize($payload, 'json');
        error_reporting($oldReporting);

        if (fwrite(self::$logFileHandles[$this->logFile], $jsonString . LF) === false) {
            throw new \RuntimeException('Could not write log record to log file', 1697542908);
        }

        return $this;
    }
}
