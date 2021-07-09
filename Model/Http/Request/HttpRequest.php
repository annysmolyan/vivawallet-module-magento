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

namespace Smolyan\VivaWallet\Model\Http\Request;

use Smolyan\VivaWallet\Api\Data\HttpRequestInterface;

/**
 * Class RequestObject
 * Object for the base VivaPayment auth

 * @package Smolyan\VivaWallet\Model\Http\Request
 */
class HttpRequest implements HttpRequestInterface
{
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $endpoint = '';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var bool
     */
    protected $useParamsAsJson = false;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var bool
     */
    protected $isPost = true;

    /**
     * @param array $headers
     * @return HttpRequestInterface
     */
    public function setHeaders(array $headers): HttpRequestInterface
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $endpoint
     * @return HttpRequestInterface
     */
    public function setEndpoint(string $endpoint): HttpRequestInterface
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @param array $params
     * @return HttpRequestInterface
     */
    public function setParams(array $params): HttpRequestInterface
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $type
     * @return HttpRequestInterface
     */
    public function setType(string $type): HttpRequestInterface
    {
        $this->type = $type;
        return $this;
    }

    /**
     * According to the type, appropriate response object will be created
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param bool $value
     * @return HttpRequestInterface
     */
    public function setUseParamsAsJson(bool $value): HttpRequestInterface
    {
        $this->useParamsAsJson = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUseParamsAsJson(): bool
    {
        return $this->useParamsAsJson;
    }

    /**
     * @param bool $isPost
     * @return HttpRequestInterface
     */
    public function setIsPost(bool $isPost = true): HttpRequestInterface
    {
        $this->isPost = $isPost;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPost(): bool
    {
        return $this->isPost;
    }
}
