<?php

namespace App\Utils;

class XmlUtils
{
    public function FormatXML($xml):string
    {
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        $formatted = $dom->saveXML();
        return $formatted;
    }
}
