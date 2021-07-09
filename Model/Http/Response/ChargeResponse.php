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

use Smolyan\VivaWallet\Api\Data\ChargeResponseInterface;

/**
 * Class ChargeResponse
 * @package Smolyan\VivaWallet\Model\Http\Response
 */
class ChargeResponse extends AbstractResponse implements ChargeResponseInterface
{
    /**
     * @var string
     */
    protected $chargeToken = '';

    /**
     * @var string
     */
    protected $redirectToACSForm = '';

    /**
     * @param string $token
     * @return ChargeResponseInterface
     */
    public function setChargeToken(string $token): ChargeResponseInterface
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

    /**
     * @param string $redirectToACSForm
     * @return ChargeResponseInterface
     */
    public function setRedirectToACSForm(string $redirectToACSForm): ChargeResponseInterface
    {
        $this->redirectToACSForm = $redirectToACSForm;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectToACSForm(): string
    {
        return $this->redirectToACSForm;
    }
}
