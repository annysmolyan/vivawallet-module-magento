<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3 (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * @category Smolyan
 * @package Smolyan_VivaWallet
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3 (GPL 3.0)
 */

namespace Smolyan\VivaWallet\Model\Http\Response;

use Smolyan\VivaWallet\Api\Data\ChargeByCardResponseInterface;

/**
 * Class ChargeByCardResponse
 * @package Smolyan\VivaWallet\Model\Http\Response
 */
class ChargeByCardResponse extends AbstractResponse implements ChargeByCardResponseInterface
{
    /**
     * @var string
     */
    protected $chargeToken = '';

    /**
     * @param string $token
     * @return ChargeByCardResponseInterface
     */
    public function setChargeToken(string $token): ChargeByCardResponseInterface
    {
        $this->chargeToken = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getChargeToken(): string
    {
        return $this->chargeToken;
    }
}
