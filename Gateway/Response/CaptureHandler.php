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
 * Class CaptureHandler
 * Handle capture response, change order status
 * @package BelSmol\VivaWallet\Gateway\Response
 */
class CaptureHandler implements HandlerInterface
{
    /**
     * @var SubjectReader
     */
    protected $subjectReader;

    /**
     * AuthorizeHandler constructor.
     * @param SubjectReader $subjectReader
     */
    public function __construct(SubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }

    /**
     * Handle capture response, change order status
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response): void
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);
        $transaction = $this->subjectReader->readPaymentTransactionId($response);

        $paymentDO
            ->getPayment()
            ->setTransactionId($transaction)
            ->setIsTransactionPending(false)
            ->setIsTransactionClosed(true)
            ->setPreparedMessage(__('VivaWallet: ')); //finally we get msg in order comment like "VivaWallet: Captured amount of $29.00 online. Transaction ID:XXX"
    }
}
