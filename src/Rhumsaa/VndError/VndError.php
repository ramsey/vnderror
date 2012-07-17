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

use Rhumsaa\VndError\Error;

/**
 * Represents an application/vnd.error document
 *
 * @see https://github.com/blongden/vnd.error
 */
class VndError
{
    /**
     * Collection of error objects
     *
     * @var Error[]
     */
    protected $errors = array();

    /**
     * Adds an error to the VndError document
     *
     * @param string|Error $logRef
     * @param string|null $message
     * @return Error
     */
    public function addError($logRef, $message = null)
    {
        if ($logRef instanceof Error) {
            $error = $logRef;
        } else {
            if (!$message) {
                throw new \InvalidArgumentException('Missing required message');
            }
            $error = new Error($logRef, $message);
        }

        $this->errors[] = $error;

        return $error;
    }

    /**
     * Returns the array of Error objects for this VndError
     *
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns a string representation for the application/vnd.error+json
     * media type
     *
     * @param bool $pretty Enable pretty-printing
     * @return string A JSON string
     */
    public function asJson($pretty = false)
    {
        $renderer = new JsonRenderer();
        return $renderer->render($this, $pretty);
    }

    /**
     * Returns a string representation for the application/vnd.error+xml
     * media type
     *
     * @param bool $pretty Enable pretty-printing
     * @return string An XML string
     */
    public function asXml($pretty = false)
    {
        $renderer = new XmlRenderer();
        return $renderer->render($this, $pretty);
    }
}
