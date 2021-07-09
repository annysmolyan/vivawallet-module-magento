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

use Smolyan\VivaWallet\Api\Data\AuthenticationResponseInterface;
use Smolyan\VivaWallet\Api\Data\AuthenticationResponseInterfaceFactory;
use Smolyan\VivaWallet\Api\Data\HttpResponseInterface;
use Smolyan\VivaWallet\Api\ResponseMapperInterface;

/**
 * Class AuthenticationResponseMapper
 * @package Smolyan\VivaWallet\Model\Http\Mapper
 */
class AuthenticationResponseMapper implements ResponseMapperInterface
{
    protected const ACCESS_TOKEN_PARAM = 'access_token';
    protected const EXPIRES_IN_PARAM = 'expires_in';
    protected const TOKEN_TYPE_PARAM = 'token_type';

    /**
     * @var AuthenticationResponseInterfaceFactory
     */
    protected $authenticationResponseFactory;

    /**
     * @param AuthenticationResponseInterfaceFactory $authenticationResponseFactory
     */
    public function __construct(AuthenticationResponseInterfaceFactory $authenticationResponseFactory)
    {
        $this->authenticationResponseFactory = $authenticationResponseFactory;
    }

    /**
     * Convert api response to object
     *
     * @param int $statusCode
     * @param array $response
     * @return HttpResponseInterface
     */
    public function toEntity(int $statusCode, array $response): HttpResponseInterface
    {
        /** @var AuthenticationResponseInterface $entity */
        $entity = $this->authenticationResponseFactory->create();
        $expInTime = isset($response[self::EXPIRES_IN_PARAM]) ? (int)$response[self::EXPIRES_IN_PARAM] : null;
        $expTime = $this->getExpiredTime($expInTime);

        $entity->setStatusCode($statusCode);
        $entity->setAccessToken($response[self::ACCESS_TOKEN_PARAM] ?? '');
        $entity->setExpiredTime($expTime);
        $entity->setTokenType($response[self::TOKEN_TYPE_PARAM] ?? '');

        return $entity;
    }

    /**
     * Return the time when auth token expired
     * @param int|null $expiredInTime
     * @return int|null
     */
    protected function getExpiredTime(?int $expiredInTime): ?int
    {
        $time = null;

        if (null != $expiredInTime) {
            $now = time(); // get current time
            $time = $now + ($expiredInTime - 300); // minus 5 minutes from expired time. get time whe we should refresh auth token
        }

        return $time;
    }
}
