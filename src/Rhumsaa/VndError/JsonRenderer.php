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
 * Provides JSON rendering functionality
 */
class JsonRenderer implements Renderer
{
    /**
     * Renders the VndError as a JSON string
     *
     * @param VndError $vndError
     * @param bool $pretty
     * @return string
     */
    public function render(VndError $vndError, $pretty = false)
    {
        $options = 0;

        if (version_compare(PHP_VERSION, '5.4.0') >= 0 and $pretty) {
            $options = JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
        }

        return json_encode($this->buildArrayForJson($vndError), $options);
    }

    /**
     * Returns an array of links for converting to JSON
     *
     * @param array $links
     * @return array
     */
    protected function buildLinksForJson(array $links)
    {
        $data = array();

        foreach($links as $rel => $links) {
            if (count($links) === 1) {
                $data[$rel] = array(array('href' => $links[0]['uri']));
                if (!is_null($links[0]['title'])) {
                    $data[$rel][0]['title'] = $links[0]['title'];
                }
                foreach ($links[0]['attributes'] as $attribute => $value) {
                    $data[$rel][0][$attribute] = $value;
                }
            } else {
                $data[$rel] = array();
                foreach ($links as $link) {
                    $item = array('href' => $link['uri']);
                    if (!is_null($link['title'])) {
                        $item['title'] = $link['title'];
                    }
                    foreach ($link['attributes'] as $attribute => $value) {
                        $item[$attribute] = $value;
                    }
                    $data[$rel][] = $item;
                }
            }
        }

        return $data;
    }

    /**
     * Returns an array for converting to JSON
     *
     * @param VndError $vndError
     * @return array
     */
    protected function buildArrayForJson(VndError $vndError)
    {
        $data = array();

        foreach ($vndError->getErrors() as $error) {
            $dataError = array(
                'logref' => $error->getLogRef(),
                'message' => $error->getMessage(),
            );

            if ($error->getLinks()) {
                $dataError['_links'] = $this->buildLinksForJson($error->getLinks());
            }

            $data[] = $dataError;
        }

        return $data;
    }
}
