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
 * The VndError Renderer Interface
 */
interface Renderer
{
    /**
     * Render the VndError document in the appropriate form. Returns a string representation.
     *
     * @param VndError $vndError
     * @param bool $pretty
     * @return string
     */
    public function render(VndError $vndError, $pretty = false);
}

