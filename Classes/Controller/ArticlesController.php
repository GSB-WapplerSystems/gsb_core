<?php

namespace ITZBund\GsbTemplate\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ArticlesController extends ActionController
{
    /**
     * @param int $startDate
     * @param int $endDate
     * @param string $title
     * @param string $description
     * @param string $location
     */
    public function icsAction($startDate = null, $endDate = null, $title = '', $description = '', $location = ''): void
    {
        if ($startDate === null || $endDate === null) {
            return;
        }

        $data = "BEGIN:VCALENDAR\n" .
            "VERSION:2.0\n" .
            "METHOD:PUBLISH\n" .
            "BEGIN:VEVENT\n" .
            'DTSTART:' . date("Ymd\THis", $startDate) . "\n" .
            'DTEND:' . date("Ymd\THis", $endDate) . "\n" .
            "TRANSP: OPAQUE\n" .
            "SEQUENCE:0\n" .
            'UID:' . md5(uniqid((string)mt_rand(), true)) . "\n" .
            'DTSTAMP:' . date("Ymd\THis\Z") . "\n" .
            'SUMMARY:' . strip_tags($title) . "\n" .
            'DESCRIPTION:' . str_replace("\r\n", '\\n', strip_tags($description)) . "\n" .
            'LOCATION:' . strip_tags($location) . "\n" .
            "PRIORITY:1\n" .
            "CLASS:PUBLIC\n" .
            "END:VEVENT\n" .
            "END:VCALENDAR\n";

        header('Content-type:text/calendar');
        header('Content-Disposition: attachment; filename="event.ics"');
        header('Content-Length: ' . strlen($data));
        header('Connection: close');
        echo $data;
        die();
    }
}
