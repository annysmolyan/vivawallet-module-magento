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

namespace Smolyan\VivaWallet\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator as GatewayAbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;
use Smolyan\VivaWallet\Api\Data\HttpResponseInterface;
use Smolyan\VivaWallet\Model\Logger\Logger;

/**
 * Class AbstractValidator
 * @package Smolyan\VivaWallet\Gateway\Validator
 */
abstract class AbstractValidator extends GatewayAbstractValidator
{
    protected const SUCCESS_SERVER_RESPONSE = 200;
    protected const RESPONSE_KEY = 'response';

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param ResultInterfaceFactory $resultFactory
     * @param Logger $logger
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        Logger $logger
    ) {
        parent::__construct($resultFactory);
        $this->logger = $logger;
    }

    /**
     * Performs domain-related validation for business object
     * Validate response, fail if not OK server code or empty values
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject): ResultInterface
    {
        $response = $validationSubject[self::RESPONSE_KEY];
        $isValid = true;
        $errorMessages = [];
        $errorCodes = [];

        if ($response[HttpResponseInterface::STATUS_CODE_KEY] != self::SUCCESS_SERVER_RESPONSE) {
            $isValid = false;
            $errorMessages[] = __($this->getGatewayName() . ' payment failed');
            $errorCodes[] = $response[HttpResponseInterface::STATUS_CODE_KEY];
            $this->logger->critical(
                "\n Failed gateway command:" . $this->getGatewayName() . ". Response code: " . $response[HttpResponseInterface::STATUS_CODE_KEY]
            );
        }

        return $this->createResult(
            $isValid,
            $errorMessages,
            $errorCodes
        );
    }

    /**
     * @return string
     */
    abstract public function getGatewayName(): string;
}
