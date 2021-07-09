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

namespace Smolyan\VivaWallet\Block\Customer;

use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Block\AbstractCardRenderer;
use Smolyan\VivaWallet\Api\Data\OrderPaymentInterface;
use Smolyan\VivaWallet\Model\UI\PaymentConfigProvider;

/**
 * Class CardRenderer
 * Render saved card list in /vault/cards/listaction/
 *
 * @package Smolyan\VivaWallet\Block\Customer
 */
class CardRenderer extends AbstractCardRenderer
{
    const ICON_TYPE_KEY = 'type';
    const ICON_WIDTH_KEY = 'width';
    const ICON_HEIGHT_KEY = 'height';
    const ICON_URL_KEY = 'url';

    /**
     * @return string
     * @since 100.1.0
     */
    public function getNumberLast4Digits(): string
    {
        return (string)$this->getTokenDetails()[OrderPaymentInterface::MASKED_CC];
    }

    /**
     * @return string
     * @since 100.1.0
     */
    public function getExpDate(): string
    {
        return (string)$this->getTokenDetails()[OrderPaymentInterface::EXPIRATION_DATE];
    }

    /**
     * Get url to icon
     * @return string
     */
    public function getIconUrl(): string
    {
        return (string)$this->getIconForType($this->getTokenDetails()[self::ICON_TYPE_KEY])[self::ICON_URL_KEY];
    }

    /**
     * Get width of icon
     * @return int
     */
    public function getIconHeight(): int
    {
        return (int)$this->getIconForType($this->getTokenDetails()[self::ICON_TYPE_KEY])[self::ICON_HEIGHT_KEY];
    }

    /**
     * Get height of icon
     * @return int
     */
    public function getIconWidth(): int
    {
        return (int)$this->getIconForType($this->getTokenDetails()[self::ICON_TYPE_KEY])[self::ICON_WIDTH_KEY];
    }

    /**
     * Can render specified token
     *
     * @param PaymentTokenInterface $token
     * @return bool
     * @since 100.1.0
     */
    public function canRender(PaymentTokenInterface $token): bool
    {
        return $token->getPaymentMethodCode() === PaymentConfigProvider::CODE;
    }
}
