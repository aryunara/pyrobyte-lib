<?php

namespace Formatters;

use DOMDocument;
use DOMException;

class XMLFormatter implements FormatterInterface
{
    /**
     * @throws DOMException
     */
    public function format(array $pages): false|string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        foreach ($pages as $page) {
            $url = $dom->createElement('url');

            foreach ($page as $key => $value) {
                $url->appendChild($dom->createElement($key, $value));
            }

            $urlset->appendChild($url);
        }

        $dom->appendChild($urlset);

        return $dom->saveXML();
    }
}