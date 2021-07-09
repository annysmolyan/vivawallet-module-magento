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

namespace Smolyan\VivaWallet\Api\Data;

/**
 * @api
 * Interface ChargeResponseInterface
 * @package Smolyan\VivaWallet\Api\Data
 */
interface ChargeResponseInterface
{
    const CHARGE_TOKEN_KEY = 'chargeToken';
    const REDIRECT_TO_ASC_FORM_KEY = 'redirectToACSForm';

    /**
     * @param string $token
     * @return ChargeResponseInterface
     */
    public function setChargeToken(string $token): self;

    /**
     * @return string
     */
    public function getChargeToken(): string;

    /**
     * @param string $redirectToACSForm
     * @return ChargeResponseInterface
     */
    public function setRedirectToACSForm(string $redirectToACSForm): self;

    /**
     * @return string
     */
    public function getRedirectToACSForm(): string;
}
