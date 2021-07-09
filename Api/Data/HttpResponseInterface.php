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

namespace Smolyan\VivaWallet\Api\Data;

/**
 * @api
 * Interface HttpResponseInterface
 * Common interface for the api response object
 * @package Smolyan\VivaWallet\Api\Data
 */
interface HttpResponseInterface
{
    const STATUS_CODE_KEY = 'statusCode';

    /**
     * @param int $statusCode
     * @return HttpResponseInterface
     */
    public function setStatusCode(int $statusCode): self;

    /**
     * @return int
     */
    public function getStatusCode(): int;
}
