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

namespace BelSmol\VivaWallet\Model\Http\Response;

use BelSmol\VivaWallet\Api\Data\AuthenticationResponseInterface;

/**
 * Class AuthenticationResponse
 * @package BelSmol\VivaWallet\Model\Http\Request
 */
class AuthenticationResponse extends AbstractResponse implements AuthenticationResponseInterface
{
    /**
     * @var string
     */
    protected $accessToken = '';

    /**
     * @var int
     */
    protected $expTime = 0;

    /**
     * @var string
     */
    protected $tokenType = '';

    /**
     * @param string $token
     * @return AuthenticationResponseInterface
     */
    public function setAccessToken(string $token): AuthenticationResponseInterface
    {
        $this->accessToken = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param int|null $expTime
     * @return AuthenticationResponseInterface
     */
    public function setExpiredTime(int $expTime = null): AuthenticationResponseInterface
    {
        $this->expTime = $expTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpiredTime(): ?int
    {
        return $this->expTime;
    }

    /**
     * @param string $tokenType
     * @return AuthenticationResponseInterface
     */
    public function setTokenType(string $tokenType): AuthenticationResponseInterface
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}
