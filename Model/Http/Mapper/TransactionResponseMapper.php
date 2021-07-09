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

use Smolyan\VivaWallet\Api\Data\HttpResponseInterface;
use Smolyan\VivaWallet\Api\Data\TransactionResponseInterface;
use Smolyan\VivaWallet\Api\Data\TransactionResponseInterfaceFactory;
use Smolyan\VivaWallet\Api\ResponseMapperInterface;

/**
 * Class TransactionResponseMapper
 * @package Smolyan\VivaWallet\Model\Http\Mapper
 */
class TransactionResponseMapper implements ResponseMapperInterface
{
    protected const TRANSACTION_ID_PARAM = 'transactionId';

    /**
     * @var TransactionResponseInterfaceFactory
     */
    protected $transactionResponseFactory;

    /**
     * TransactionResponseMapper constructor.
     * @param TransactionResponseInterfaceFactory $transactionResponseFactory
     */
    public function __construct(TransactionResponseInterfaceFactory $transactionResponseFactory)
    {
        $this->transactionResponseFactory = $transactionResponseFactory;
    }

    /**
     * Convert api response to object
     * @param int $statusCode
     * @param array $response
     * @return HttpResponseInterface
     */
    public function toEntity(int $statusCode, array $response): HttpResponseInterface
    {
        /** @var TransactionResponseInterface $chargeResponse */
        $entity = $this->transactionResponseFactory->create();

        $entity->setStatusCode($statusCode);
        $entity->setTransactionId($response[self::TRANSACTION_ID_PARAM] ?? '');

        return $entity;
    }
}
