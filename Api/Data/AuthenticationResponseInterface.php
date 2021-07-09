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
 * Interface AuthenticationResponseInterface
 * @package Smolyan\VivaWallet\Api\Data
 */
interface AuthenticationResponseInterface
{
    const ACCESS_TOKEN_KEY = 'access_token';
    const EXPIRES_IN_KEY = 'expires_in';
    const TOKEN_TYPE_KEY = 'token_type';

    /**
     * @param string $token
     * @return AuthenticationResponseInterface
     */
    public function setAccessToken(string $token): self;

    /**
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * @param int|null $expTime
     * @return AuthenticationResponseInterface
     */
    public function setExpiredTime(int $expTime = null): self;

    /**
     * @return int|null
     */
    public function getExpiredTime(): ?int;

    /**
     * @param string $tokenType
     * @return AuthenticationResponseInterface
     */
    public function setTokenType(string $tokenType): self;

    /**
     * @return string
     */
    public function getTokenType(): string;
}
