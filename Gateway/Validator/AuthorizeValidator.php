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

/**
 * Class AuthorizeValidator
 * Validate response
 * @package Smolyan\VivaWallet\Gateway\Validator
 */
class AuthorizeValidator extends AbstractValidator
{
    const GATEWAY_NAME = 'Authorize';

    /**
     * @return string
     */
    public function getGatewayName(): string
    {
        return self::GATEWAY_NAME;
    }
}
