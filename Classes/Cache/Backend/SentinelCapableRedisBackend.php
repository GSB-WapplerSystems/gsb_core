<?php

/*
 * This file is part of the GSB11 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Cache\Backend;

use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Cache\Backend\RedisBackend;
use TYPO3\CMS\Core\Cache\Exception;

/**
 * A caching backend which stores cache entries by using Redis with phpredis
 * PHP module. Redis is a noSQL database with very good scaling characteristics
 * in proportion to the amount of entries and data size.
 *
 * This backend is a fork of the TYPO3\CMS\Core\Cache\Backend\RedisBackend and
 * is modified to work with Redis Sentinel by GSB11.
 *
 * @see https://redis.io/
 * @see https://github.com/phpredis/phpredis
 */
class SentinelCapableRedisBackend extends RedisBackend
{
    /**
     * Instance of the PHP redis sentinel class
     *
     * @var \RedisSentinel
     */
    protected $redisSentinel;

    /**
     * Indicates whether the server is sentinel
     *
     * @var bool
     */
    protected $isSentinel = false;

    /**
     * Initializes the redis backend
     *
     * @throws Exception if access to redis with password is denied or if database selection fails
     */
    public function initializeObject(): void
    {
        $this->redis = new \Redis();
        try {
            if ($this->isSentinel) {
                $this->redisSentinel = new \RedisSentinel([
                    'host' => $this->hostname,
                    'port' => $this->port,
                    'connectTimeout' => $this->connectionTimeout,
                    ]);
                $sentinelMaster = $this->redisSentinel->masters();
                if ($sentinelMaster === false) {
                    throw new Exception('Could not get master from sentinel.', 1279765134);
                }
                $this->logger->log(LogLevel::ERROR, 'Resolved redis config', [$this->hostname, $this->port, $sentinelMaster[0]]);
                if ($this->persistentConnection) {
                    $this->connected = $this->redis->pconnect($sentinelMaster[0]['ip'], $sentinelMaster[0]['port'], $this->connectionTimeout, (string)$this->database);
                } else {
                    $this->connected = $this->redis->connect($sentinelMaster[0]['ip'], $sentinelMaster[0]['port'], $this->connectionTimeout);
                }
            } else {
                if ($this->persistentConnection) {
                    $this->connected = $this->redis->pconnect($this->hostname, $this->port, $this->connectionTimeout, (string)$this->database);
                } else {
                    $this->connected = $this->redis->connect($this->hostname, $this->port, $this->connectionTimeout);
                }
            }
        } catch (\Exception $e) {
            $this->logger->log(LogLevel::ALERT, 'Could not connect to redis server.', ['exception' => $e]);
        }
        if ($this->connected) {
            if ($this->password !== '') {
                $success = $this->redis->auth($this->password);
                if (!$success) {
                    throw new Exception('The given password was not accepted by the redis server.', 1279765134);
                }
            }
            if ($this->database >= 0) {
                $success = $this->redis->select($this->database);
                if (!$success) {
                    throw new Exception('The given database "' . $this->database . '" could not be selected.', 1279765144);
                }
            }
        }
    }

    /**
     * Set if the server is sentinel
     *
     * @param bool $isSentinel
     */
    public function setIsSentinel(bool $isSentinel): void
    {
        $this->isSentinel = $isSentinel;
    }
}
