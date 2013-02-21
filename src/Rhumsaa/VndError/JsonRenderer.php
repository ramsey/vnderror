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
        $supportsJsonPrettyPrint = (version_compare(PHP_VERSION, '5.4.0') >= 0); 

        if ($supportsJsonPrettyPrint and $pretty) {
            $options = JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
        }
        
        $json = json_encode($this->buildArrayForJson($vndError), $options);

        if (!$supportsJsonPrettyPrint and $pretty) {
            $json = $this->indentJSON($json);
        }

        return $json; 
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
                $data[$rel] = array('href' => $links[0]['uri']);
                if (!is_null($links[0]['title'])) {
                    $data[$rel]['title'] = $links[0]['title'];
                }
                foreach ($links[0]['attributes'] as $attribute => $value) {
                    $data[$rel][$attribute] = $value;
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
    
    /**
     * Indents a flat JSON string to make it more human-readable.
     * @param  string $json The original JSON string to process.
     * @return string Indented version of the original JSON string.
     *
     * via http://recursive-design.com/blog/2008/03/11/format-json-with-php/
     * Slightly modified to add spaces after colons and remove escaped slashes.
     */
    protected function indentJSON($json)
    {
        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '    ';
        $newLine     = "\n";
        $prevChar    = '';
        $prevPrevChar    = '';
        $outOfQuotes = true;
        for ($i=0; $i<=$strLen; $i++) {
            // Grab the next character in the string.
            $char = substr($json, $i, 1);
            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;
                // If this character is the end of an element,
                // output a new line and indent the next line.
            } elseif (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }
            // Add the character to the result string.
            $result .= $char;
            
            if ($char == ':' && $prevChar == '"' && $prevPrevChar != '\\') {
                $result .= ' ';
            }
            
            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $prevPrevChar = $prevChar;
            $prevChar = $char;
        }
    
        return str_replace('\/', '/', $result);
    }    
    
}
