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

namespace Smolyan\VivaWallet\Model\Http;

use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Smolyan\VivaWallet\Api\Data\HttpRequestInterface;
use Smolyan\VivaWallet\Api\Data\HttpResponseInterface;
use Smolyan\VivaWallet\Api\HttpClientInterface;
use Smolyan\VivaWallet\Model\Http\Mapper\ResponseMapperFactory;
use Smolyan\VivaWallet\Model\Logger\Logger;

/**
 * Class HttpClient
 * Call api
 * see https://developer.vivawallet.com/api-reference-guide/native-checkout-v2-api/
 * @package Smolyan\VivaWallet\Model\Http
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * @var ResponseMapperFactory
     */
    protected $responseMapperFactory;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * @param CurlFactory $curlFactory
     * @param ResponseMapperFactory $responseMapperFactory
     * @param Logger $logger
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(
        CurlFactory $curlFactory,
        ResponseMapperFactory $responseMapperFactory,
        Logger $logger,
        JsonSerializer $jsonSerializer
    ) {
        $this->curlFactory = $curlFactory;
        $this->responseMapperFactory = $responseMapperFactory;
        $this->logger = $logger;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Call api
     * @param HttpRequestInterface $request
     * @return HttpResponseInterface
     * @throws \Exception
     */
    public function call(HttpRequestInterface $request): HttpResponseInterface
    {
        $curl = $this->curlFactory->create();
        $params = $request->getUseParamsAsJson()
            ? $this->jsonSerializer->serialize($request->getParams())
            : $request->getParams();

        foreach ($request->getHeaders() as $name => $value) {
            $curl->addHeader($name, $value);
        }

        $request->getIsPost() ? $curl->post($request->getEndpoint(), $params) : $curl->get($request->getEndpoint());


        $statusCode = (int)$curl->getStatus();
        $responseBody = $this->jsonSerializer->unserialize($curl->getBody());

        $this->logger->info(
            "Sending request. Request type: " . $request->getType()
            . "\n Response code: " . $statusCode
            . "\n Response: " . $curl->getBody()
        );

        $responseMapper = $this->responseMapperFactory->create($request->getType());
        $response = $responseMapper->toEntity($statusCode, (array)$responseBody);

        return $response;
    }
}
