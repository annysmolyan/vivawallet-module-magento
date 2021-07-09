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

namespace Smolyan\VivaWallet\Api;

use Smolyan\VivaWallet\Api\Data\HttpResponseInterface;

/**
 * Interface ResponseMapperInterface
 * @package Smolyan\VivaWallet\Api
 */
interface ResponseMapperInterface
{
    /**
     * Convert api response to object
     * @param int $statusCode
     * @param array $response
     * @return HttpResponseInterface
     */
    public function toEntity(int $statusCode, array $response): HttpResponseInterface;
}
