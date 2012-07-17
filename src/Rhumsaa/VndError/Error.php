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
 * Represents a vnd.error error element
 *
 * @see https://github.com/blongden/vnd.error/blob/1b9174148ca3164e0bc8888eef46def7527c3db1/README.md#error
 */
class Error
{
    /**
     * For expressing a (numeric/alpha/alphanumeric) identifier to refer to
     * the specific error on the server side for logging purposes (i.e. a
     * request number).
     *
     * @var string
     */
    protected $logRef;

    /**
     * For expressing a human readable message related to the current error
     * which may be displayed to the user of the api.
     *
     * @var string
     */
    protected $message;

    /**
     * Collection of links.
     *
     * For expressing "outbound" hyperlinks to other, related resources.
     *
     * @var array
     */
    protected $links = array();

    /**
     * Constructs a new error element
     *
     * @param string $logRef
     * @param string $message
     */
    public function __construct($logRef, $message)
    {
        $this->logRef = $logRef;
        $this->message = $message;
    }

    /**
     * Adds a link to the error
     *
     * @param string $rel
     * @param string $uri
     * @param string $title
     * @param array $attributes
     * @return Error
     */
    public function addLink($rel, $uri, $title = null, array $attributes = array())
    {
        $this->links[$rel][] = array(
            'uri' => $uri,
            'title' => $title,
            'attributes' => $attributes,
        );

        return $this;
    }

    /**
     * Returns the array of links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Returns the log ref
     *
     * @return string
     */
    public function getLogRef()
    {
        return $this->logRef;
    }

    /**
     * Returns the message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
