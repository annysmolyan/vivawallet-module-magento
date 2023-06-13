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

namespace BelSmol\VivaWallet\Api\Data;

/**
 * @api
 * Interface SaveCardResponseInterface
 * @package BelSmol\VivaWallet\Api\Data
 */
interface CardTokenResponseInterface
{
    const TOKEN_KEY = 'token';

    /**
     * @param string $token
     * @return CardTokenResponseInterface
     */
    public function setToken(string $token): self;

    /**
     * @return string
     */
    public function getToken(): string;
}
