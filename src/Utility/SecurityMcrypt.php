<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Utility;

use Cake\Utility\Crypto\Mcrypt;
use Cake\Utility\Crypto\OpenSsl;
use InvalidArgumentException;
use Cake\Utility\Security;

/**
 * Security Library contains utility methods related to security
 */
class SecurityMcrypt extends Security 
{

    public static function encrypt($plain, $key, $hmacSalt = null) {
        self::_checkKey($key, 'encrypt()');

        if ($hmacSalt === null) {
            $hmacSalt = static::$_salt;
        }
        // Generate the encryption and hmac key.
        $key = mb_substr(hash('sha256', $key . $hmacSalt), 0, 32, '8bit');

        $crypto = static::engine(new Mcrypt());
        $ciphertext = $crypto->encrypt($plain, $key);
        $hmac = hash_hmac('sha256', $ciphertext, $key);

        return $hmac . $ciphertext;
    }

   
    /**
     * Decrypt a value using AES-256.
     *
     * @param string $cipher The ciphertext to decrypt.
     * @param string $key The 256 bit/32 byte key to use as a cipher key.
     * @param string|null $hmacSalt The salt to use for the HMAC process. Leave null to use Security.salt.
     * @return string|bool Decrypted data. Any trailing null bytes will be removed.
     * @throws \InvalidArgumentException On invalid data or key.
     */
    public static function decrypt($cipher, $key, $hmacSalt = null) {
        self::_checkKey($key, 'decrypt()');
        if (empty($cipher)) {
            throw new InvalidArgumentException('The data to decrypt cannot be empty.');
        }
        if ($hmacSalt === null) {
            $hmacSalt = static::$_salt;
        }

        // Generate the encryption and hmac key.
        $key = mb_substr(hash('sha256', $key . $hmacSalt), 0, 32, '8bit');

        // Split out hmac for comparison
        $macSize = 64;
        $hmac = mb_substr($cipher, 0, $macSize, '8bit');
        $cipher = mb_substr($cipher, $macSize, null, '8bit');

        $compareHmac = hash_hmac('sha256', $cipher, $key);
        if (!static::_constantEquals($hmac, $compareHmac)) {
            return false;
        }

        $crypto = static::engine(new Mcrypt());

        return $crypto->decrypt($cipher, $key);
    }

    /**
     * A timing attack resistant comparison that prefers native PHP implementations.
     *
     * @param string $hmac The hmac from the ciphertext being decrypted.
     * @param string $compare The comparison hmac.
     * @return bool
     * @see https://github.com/resonantcore/php-future/
     */
}
