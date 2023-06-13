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

namespace BelSmol\VivaWallet\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Payment\Model\CcConfig as MagentoCcConfig;
use Magento\Payment\Model\Config;
use Psr\Log\LoggerInterface;
use BelSmol\VivaWallet\Helper\ConfigHelper;

/**
 * Credit card configuration model
 * @OVERRIDE
 * Added getCcAllowedTypes() method
 * Class CcConfig
 * @package BelSmol\VivaWallet\Model
 */
class CcConfig extends MagentoCcConfig
{
    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @param Config $paymentConfig
     * @param Repository $assetRepo
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param LoggerInterface $logger
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Config $paymentConfig,
        Repository $assetRepo,
        RequestInterface $request,
        UrlInterface $urlBuilder,
        LoggerInterface $logger,
        ConfigHelper $configHelper
    ) {
        parent::__construct(
            $paymentConfig,
            $assetRepo,
            $request,
            $urlBuilder,
            $logger
        );
        $this->configHelper = $configHelper;
    }

    /**
     * Return allowed cc types which were selected in admin panel
     * @return array
     */
    public function getCcAllowedTypes(): array
    {
        $types = $this->getCcAvailableTypes();
        $allowedTypes = $this->configHelper->getAllowedCcTypes();

        foreach ($types as $code => $label) {
            if (!in_array($code, $allowedTypes)) {
                unset($types[$code]);
            }
        }

        return (array)$types;
    }
}
