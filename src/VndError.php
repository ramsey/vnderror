<?php
/**
 * This file is part of the Rhumsaa\VndError library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2012-2014 Ben Ramsey <http://benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Rhumsaa\VndError;

use Nocarrier\Hal;

/**
 * Represents an application/vnd.error document
 *
 * @see https://github.com/blongden/vnd.error
 */
class VndError extends Hal
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var string|int
     */
    protected $logref;

    /**
     * Creates a new vnd.error document
     *
     * @param string $message For expressing a human readable message related to the current error which may be displayed to the user of the api.
     * @param string $logref For expressing a (numeric/alpha/alphanumeric) identifier to refer to the specific error on the server side for logging purposes (i.e. a request number).
     */
    public function __construct($message, $logref = null)
    {
        parent::__construct();

        $this->message = $message;
        $this->logref = $logref;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = parent::getData();

        if (!isset($data['message'])) {
            $data['message'] = $this->message;
        }

        if ($this->logref !== null && !isset($data['@logref'])) {
            $data['@logref'] = $this->logref;
        }

        return $data;
    }

    /**
     * Return the logref
     *
     * @return string
     */
    public function getLogref()
    {
        return $this->logref;
    }

    /**
     * Return the error message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the logref
     *
     * @param string|int $logref
     */
    public function setLogref($logref)
    {
        $this->logref = $logref;
    }

    /**
     * Sets the error message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
