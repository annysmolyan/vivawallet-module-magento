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

namespace Smolyan\VivaWallet\Controller\Payment;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Smolyan\VivaWallet\Helper\ConfigHelper;
use Smolyan\VivaWallet\Model\Logger\Logger;
use Smolyan\VivaWallet\Model\UI\PaymentConfigProvider;

/**
 * Class CompleteCheckout
 * Capture payment here
 * @package Smolyan\VivaWallet\Controller\Payment
 */
class CompleteCheckout extends Action
{
    const SUCCESS_CHECKOUT_URL = 'checkout/onepage/success';
    const FAIL_CHECKOUT_URL = 'checkout/cart';

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var OrderManagementInterface
     */
    protected $orderManagement;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param ConfigHelper $configHelper
     * @param OrderManagementInterface $orderManagement
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        ConfigHelper $configHelper,
        OrderManagementInterface $orderManagement,
        Logger $logger
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->configHelper = $configHelper;
        $this->logger = $logger;
        $this->orderManagement = $orderManagement;
    }

    /**
     * Capture payment after user card payment confirmation
     * Didn't make here additional checks, because transaction executes with one-time charge token.
     * In other words, impossible to pay twice
     *
     * @return ResultInterface
     * @throws \Exception
     */
    public function execute(): ResultInterface
    {
        /** @var OrderInterface $order */
        $order = $this->checkoutSession->getLastRealOrder();

        if (
            !$this->configHelper->isPaymentEnabled()
            || !$order
            || $order->getPayment()->getMethod() != PaymentConfigProvider::CODE
            || $order->hasInvoices()
        ) {
            throw new \Exception(__("Access denied"));
        }

        /** @var  $payment OrderPaymentInterface */
        $payment = $order->getPayment();
        $redirectPath = self::SUCCESS_CHECKOUT_URL;

        try {
            $payment->capture()->save();
            $order->save();
        } catch (CommandException $exception) {
            $this->orderManagement->cancel($order->getEntityId()); //Cancel Order means to close the order. In Order canceled you can’t order to be modified after the cancel order.
            $this->checkoutSession->restoreQuote();
            $this->messageManager->addErrorMessage(
                __('VivaWallet payment transaction declined. Your order was cancelled. See more details in your account.')
            );
            $this->logger->critical("Failed CompleteCheckout controller: " . $exception->getMessage());
            $redirectPath = self::FAIL_CHECKOUT_URL;
        } catch (\Exception $exception) {
            // do nothing
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($redirectPath);
        return $resultRedirect;
    }
}
