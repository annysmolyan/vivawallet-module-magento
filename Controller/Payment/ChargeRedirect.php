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

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Smolyan\VivaWallet\Api\Data\ChargeResponseInterface;
use Smolyan\VivaWallet\Helper\ConfigHelper;

/**
 * Class Charge
 * Return charge redirection form which we get from ChargeCallApi
 *
 * @package Smolyan\VivaWallet\Controller\Payment
 */
class ChargeRedirect extends Action
{
    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @param Context $context
     * @param ConfigHelper $configHelper
     * @param JsonFactory $resultJsonFactory
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        ConfigHelper $configHelper,
        JsonFactory  $resultJsonFactory,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->configHelper = $configHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * Return charge redirection form which we get from ChargeCallApi
     * @return Json
     * @throws \Exception
     */
    public function execute(): Json
    {
        if (!$this->getRequest()->isXmlHttpRequest() || !$this->configHelper->isPaymentEnabled()) { //check if is ajax call and module enabled
            throw new \Exception("Access denied");
        }

        $redirectForm = $this->customerSession->getVivaWalletRedirectToACSForm();
        $this->customerSession->setVivaWalletRedirectToACSForm(''); // remove form from session
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData([
            ChargeResponseInterface::REDIRECT_TO_ASC_FORM_KEY => $redirectForm,
        ]);

        return $resultJson;
    }
}
