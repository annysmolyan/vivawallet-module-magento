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

namespace BelSmol\VivaWallet\Api;

use BelSmol\VivaWallet\Api\Data\HttpRequestInterface;
use BelSmol\VivaWallet\Api\Data\HttpResponseInterface;

/**
 * Interface HttpClientInterface
 * @package BelSmol\VivaWallet\Api
 */
interface HttpClientInterface
{
    /**
     * Call api
     * @param HttpRequestInterface $request
     * @return HttpResponseInterface
     */
    public function call(HttpRequestInterface $request): HttpResponseInterface;
}
