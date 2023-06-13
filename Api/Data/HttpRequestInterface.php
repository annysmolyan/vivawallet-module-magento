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

namespace BelSmol\VivaWallet\Api\Data;

/**
 * @api
 * Interface HttptRequestInterface
 * Data storage for particular API endpoint
 *
 * @package BelSmol\VivaWallet\Api\Data
 */
interface HttpRequestInterface
{
    const AUTH_RESPONSE_TYPE = 'auth';
    const CHARGE_RESPONSE_TYPE = 'charge';
    const CHARGE_BY_CARD_RESPONSE_TYPE = 'charge_by_card';
    const TRANSACTION_RESPONSE_TYPE = 'transaction';
    const CARD_SAVE_RESPONSE_TYPE = 'card_save';

    /**
     * @param array $headers
     * @return HttpRequestInterface
     */
    public function setHeaders(array $headers): self;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param string $endpoint
     * @return HttpRequestInterface
     */
    public function setEndpoint(string $endpoint): self;

    /**
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * @param array $params
     * @return HttpRequestInterface
     */
    public function setParams(array $params): self;

    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * According to the type, appropriate response object will be created
     * @return string
     */
    public function getType():string;

    /**
     * @param string $type
     * @return HttpRequestInterface
     */
    public function setType(string $type): self;

    /**
     * @param bool $value
     * @return HttpRequestInterface
     */
    public function setUseParamsAsJson(bool $value): self;

    /**
     * @return bool
     */
    public function getUseParamsAsJson(): bool;

    /**
     * @param bool $isPost
     * @return HttpRequestInterface
     */
    public function setIsPost(bool $isPost = true): self;

    /**
     * @return bool
     */
    public function getIsPost(): bool;
}
