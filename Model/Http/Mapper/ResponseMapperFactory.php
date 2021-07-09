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

namespace Smolyan\VivaWallet\Model\Http\Mapper;

use Magento\Framework\ObjectManagerInterface;
use Smolyan\VivaWallet\Api\Data\HttpRequestInterface;
use Smolyan\VivaWallet\Api\ResponseMapperInterface;

/**
 * Class ResponseMapperFactory
 * @package Smolyan\VivaWallet\Model\Http\Mapper
 */
class ResponseMapperFactory
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create api response mapper model
     * @param string $type
     * @return ResponseMapperInterface
     * @throws \Exception
     */
    public function create(string $type): ResponseMapperInterface
    {
        switch ($type) {
            case HttpRequestInterface::AUTH_RESPONSE_TYPE:
                $object = $this->_objectManager->create(AuthenticationResponseMapper::class);
                break;
            case HttpRequestInterface::CHARGE_RESPONSE_TYPE:
                $object = $this->_objectManager->create(ChargeResponseMapper::class);
                break;
            case HttpRequestInterface::TRANSACTION_RESPONSE_TYPE:
                $object = $this->_objectManager->create(TransactionResponseMapper::class);
                break;
            case HttpRequestInterface::CARD_SAVE_RESPONSE_TYPE:
                $object = $this->_objectManager->create(CardTokenResponseMapper::class);
                break;
            case HttpRequestInterface::CHARGE_BY_CARD_RESPONSE_TYPE:
                $object = $this->_objectManager->create(ChargeByCardResponseMapper::class);
                break;
            default:
                throw new \Exception('Wrong VivaWallet HttpRequest object type');
        }
        return $object;
    }
}
