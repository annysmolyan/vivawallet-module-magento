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

namespace BelSmol\VivaWallet\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use BelSmol\VivaWallet\Gateway\Helper\SubjectReader;

/**
 * Class VaultAuthorizeHandler
 * @package BelSmol\VivaWallet\Gateway\Response
 */
class VaultAuthorizeHandler implements HandlerInterface
{
    /**
     * @var SubjectReader
     */
    protected $subjectReader;

    /**
     * @param SubjectReader $subjectReader
     */
    public function __construct(SubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }
    /**
     * Handles response
     * Handle authorize response, change order status, cc transaction
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response): void
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);
        $payment = $paymentDO->getPayment();
        $ccTransaction = $this->subjectReader->readCcTransactionForDirectCard($response);

        $payment->setCcTransId($ccTransaction);
        $payment->setIsTransactionClosed(true);
        $payment->setPreparedMessage(
            __('VivaWallet Authorize link was created in gateway. After user confirmation will be captured and ')
        ); //will be used for prepared message. finally we get msg in order comment like "VivaWallet Authorize link was created in gateway. After user confirmation will be Authorized amount of $29.00."
    }
}