<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3 (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * @category BelSmol
 * @package BelSmol_VivaWallet
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3 (GPL 3.0)
 */

namespace BelSmol\VivaWallet\Model\Http\Response;

use BelSmol\VivaWallet\Api\Data\HttpResponseInterface;

/**
 * Class AbstractResponse
 * @package BelSmol\VivaWallet\Model\Http\Response
 */
abstract class AbstractResponse implements HttpResponseInterface
{
    /**
     * @var int
     */
    protected $statusCode = 0;

    /**
     * @param int $statusCode
     * @return HttpResponseInterface
     */
    public function setStatusCode(int $statusCode): HttpResponseInterface
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
