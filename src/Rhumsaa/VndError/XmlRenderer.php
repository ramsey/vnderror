<?php
/**
 * This file is part of the Rhumsaa\VndError library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2012 Ben Ramsey <http://benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Rhumsaa\VndError;

/**
 * Provides XML rendering functionality
 */
class XmlRenderer implements Renderer
{
    /**
     * Renders the VndError as an XML string
     *
     * @param VndError $vndError
     * @param bool $pretty
     * @return string
     */
    public function render(VndError $vndError, $pretty = false)
    {
        $doc = new \DOMDocument('1.0', 'utf-8');
        $rootNode = new \DOMElement('resource');
        $rootNode = $doc->appendChild($rootNode);
        $totalNode = new \DOMElement('total',count($vndError->getErrors()));
        $totalNode = $rootNode->appendChild($totalNode);
        foreach ($vndError->getErrors() as $error) {
            $errorDoc = $this->getDomForError($error);
            $errorNode = $doc->importNode($errorDoc->documentElement, true);
            $rootNode->appendChild($errorNode);
        }

        if ($pretty) {
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;
        }

        return $doc->saveXML();
    }

    /**
     * Returns a DOM representation of the Error object
     *
     * @param Error $error
     * @return \DOMDocument
     */
    protected function getDomForError(Error $error)
    {
        $doc = new \DOMDocument('1.0', 'utf-8');
        $errorNode = new \DOMElement('resource');
        $errorNode = $doc->appendChild($errorNode);
        $errorNode->setAttribute('logref', $error->getLogRef());

        $messageNode = new \DOMElement('message', $error->getMessage());
        $errorNode->appendChild($messageNode);

        foreach ($error->getLinks() as $rel => $links) {
            foreach ($links as $link) {
                $linkNode = new \DOMElement('link');
                $linkNode = $errorNode->appendChild($linkNode);
                $linkNode->setAttribute('rel', $rel);
                $linkNode->setAttribute('href', $link['uri']);
                if ($link['title'] !== null) {
                    $linkNode->setAttribute('title', $link['title']);
                }
                foreach ($link['attributes'] as $key => $value) {
                    $linkNode->setAttribute($key, $value);
                }
            }
        }

        return $doc;
    }
}
