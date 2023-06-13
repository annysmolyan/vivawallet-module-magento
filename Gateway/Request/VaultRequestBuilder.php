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

namespace BelSmol\VivaWallet\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class VaultRequestBuilder
 * @package BelSmol\VivaWallet\Gateway\Request
 */
class VaultRequestBuilder implements BuilderInterface
{
    /**
    * Additional options in request to gateway
    */
    const OPTIONS = 'options';

    /**
     * The option that determines whether the payment method associated with
     * the successful transaction should be stored in the Vault.
     */
    const STORE_IN_VAULT_ON_SUCCESS = 'storeInVaultOnSuccess';

    /**
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject): array
    {
        return [
            self::OPTIONS => [
                self::STORE_IN_VAULT_ON_SUCCESS => true
            ]
        ];
    }
}
