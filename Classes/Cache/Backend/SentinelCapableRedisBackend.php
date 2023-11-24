<?php

/*
 * This file is part of the GSB 11 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Cache\Backend;

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
        try {
            $this->redis = new \Redis();
            try {
                if ($this->isSentinel) {
                    $this->redisSentinel = new \RedisSentinel([
                        'host' => $this->hostname,
                        'port' => $this->port,
                        'connectTimeout' => $this->connectionTimeout,
                        'persistent' => ($this->configuration['persistentConnection'] === true) ? 'cachebackend' : null,
                    ]);
                    $sentinelMaster = $this->redisSentinel->masters();
                    if ($sentinelMaster === false) {
                        throw new Exception('Could not get master from sentinel.', 1279765134);
                    }
                    if ($this->persistentConnection) {
                        $this->connected = $this->redis->pconnect((string)$sentinelMaster[0]['ip'], (int)$sentinelMaster[0]['port'], $this->connectionTimeout, (string)$this->database);
                    } else {
                        $this->connected = $this->redis->connect((string)$sentinelMaster[0]['ip'], (int)$sentinelMaster[0]['port'], $this->connectionTimeout);
                    }
                } else {
                    if ($this->persistentConnection) {
                        $this->connected = $this->redis->pconnect($this->hostname, $this->port, $this->connectionTimeout, (string)$this->database);
                    } else {
                        $this->connected = $this->redis->connect($this->hostname, $this->port, $this->connectionTimeout);
                    }
                }
            } catch (\Exception $e) {
                $this->logger->alert('Could not connect to redis server.', ['exception' => $e]);
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
        } catch (\Throwable $e) {
            $this->logger->critical('Could not initialize connectiion to redis server.', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
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

    /**
     * Loads data from the cache. fails silently on error.
     *
     * Scales O(1) with number of cache entries
     *
     * @param string $entryIdentifier An identifier which describes the cache entry to load
     * @return mixed The cache entry's content as a string or FALSE if the cache entry could not be loaded
     */
    public function get($entryIdentifier): mixed
    {
        if ($this->connected) {
            try {
                return parent::get($entryIdentifier);
            } catch (\Throwable $e) {
                $this->logger->critical('Error while getting Data from Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
        return false;
    }

    /**
     * Checks if a cache entry with the specified identifier exists. fails silently on error.
     *
     * Scales O(1) with number of cache entries
     *
     * @param string $entryIdentifier Identifier specifying the cache entry
     * @return bool TRUE if such an entry exists, FALSE if not
     */
    public function has($entryIdentifier): bool
    {
        if ($this->connected) {
            try {
                return parent::has($entryIdentifier);
            } catch (\Throwable $e) {
                $this->logger->critical('Error while checking if Redis Cache has Data', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
        return false;
    }

    /**
     * Finds and returns all cache entry identifiers which are tagged by the
     * specified tag, failing silently on error.
     *
     * Scales O(1) with number of cache entries
     * Scales O(n) with number of tag entries
     *
     * @param string $tag The tag to search for
     * @return array An array of entries with all matching entries. An empty array if no entries matched
     */
    public function findIdentifiersByTag($tag): array
    {
        if ($this->connected) {
            try {
                return parent::findIdentifiersByTag($tag);
            } catch (\Throwable $e) {
                $this->logger->critical('Error while fetching from Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
        return [];
    }

    /**
     * Save data in the cache, failing silently on error.
     *
     * Scales O(1) with number of cache entries
     * Scales O(n) with number of tags
     *
     * @param string $entryIdentifier Identifier for this specific cache entry
     * @param string $data Data to be stored
     * @param array $tags Tags to associate with this cache entry
     * @param int $lifetime Lifetime of this cache entry in seconds. If NULL is specified, default lifetime is used. "0" means unlimited lifetime.
     */
    public function set($entryIdentifier, $data, array $tags = [], $lifetime = null): void
    {
        if ($this->connected) {
            try {
                parent::set($entryIdentifier, $data, $tags, $lifetime);
            } catch (\Throwable $e) {
                $this->logger->critical('Error while setting data into Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
    }

    /**
     * Removes all cache entries matching the specified identifier, failing silently on error.
     *
     * Scales O(1) with number of cache entries
     * Scales O(n) with number of tags
     *
     * @param string $entryIdentifier Specifies the cache entry to remove
     * @return bool TRUE if (at least) an entry could be removed or FALSE if no entry was found
     */
    public function remove($entryIdentifier): bool
    {
        if ($this->connected) {
            try {
                return parent::remove($entryIdentifier);
            } catch (\Throwable $e) {
                $this->logger->critical('Error while removing data from Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
        return false;
    }

    /**
     * With the current internal structure, only the identifier to data entries
     * have a redis internal lifetime. If an entry expires, attached
     * identifier to tags and tag to identifiers entries will be left over.
     * This methods finds those entries and cleans them up.
     * Fails silently on error.
     *
     * Scales O(n*m) with number of cache entries (n) and number of tags (m)
     */
    public function collectGarbage(): void
    {
        if ($this->connected) {
            try {
                parent::collectGarbage();
            } catch (\Throwable $e) {
                $this->logger->critical('Error while collecting garbage in Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
    }

    /**
     * Removes all cache entries of this cache, failing silently on error.
     *
     * Scales O(1) with number of cache entries
     */
    public function flush(): void
    {
        if ($this->connected) {
            try {
                parent::flush();
            } catch (\Throwable $e) {
                $this->logger->critical('Error while flushing complete cache in Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
    }

    /**
     * Removes all cache entries of this cache which are tagged with the specified tag, failing silently on error.
     *
     * Scales O(1) with number of cache entries
     * Scales O(n^2) with number of tag entries
     *
     * @param string $tag Tag the entries must have
     */
    public function flushByTag($tag): void
    {
        if ($this->connected) {
            try {
                parent::flushByTag($tag);
            } catch (\Throwable $e) {
                $this->logger->critical('Error while flushing cache tag in Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
    }
}
