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

use BelSmol\VivaWallet\Api\Data\TransactionResponseInterface;

/**
 * Class TransactionResponse
 * @package BelSmol\VivaWallet\Model\Http\Response
 */
class TransactionResponse extends AbstractResponse implements TransactionResponseInterface
{
    /**
     * @var string
     */
    protected $transactionId = '';

    /**
     * @param string $id
     * @return TransactionResponseInterface
     */
    public function setTransactionId(string $id): TransactionResponseInterface
    {
        $this->transactionId = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
}
