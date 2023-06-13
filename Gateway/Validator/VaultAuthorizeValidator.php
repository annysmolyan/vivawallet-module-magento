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

namespace BelSmol\VivaWallet\Gateway\Validator;

/**
 * Class VaultAuthorizeValidator
 * @package BelSmol\VivaWallet\Gateway\Validator
 */
class VaultAuthorizeValidator extends AbstractValidator
{
    const GATEWAY_NAME = 'Vault Authorize';

    /**
     * @return string
     */
    public function getGatewayName(): string
    {
        return self::GATEWAY_NAME;
    }
}
