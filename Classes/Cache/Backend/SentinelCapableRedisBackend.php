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
 * is modified to work with Redis Sentinel by GSB11. Has a retry mechanism to
 * handle temporary connection problems. Use different connections for read and
 * write operations.
 * Take care that on permanent connection errors the cache is not working but not
 * throwing an exception, so the application is not crashing. This is a tradeoff
 * to have a more stable application. But you should monitor the redis server
 * and fix the problem as soon as possible. Because of the retry mechanism the
 * application should work again after the redis server is available again.
 *
 * TYPO3 will have a massive performance impact if the cache is not working!
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
    protected bool $isSentinel = false;

    protected string $sentinelHostname = '';

    protected int $sentinelPort = 26379;

    protected ?string $sentinelPassword = null;

    /**
     * Initializes nothing, since we use Read and Write Connections.
     */
    public function initializeObject(): void {}

    /**
     * @throws \RedisException
     */
    private function initializeRead(): void
    {
        $this->retryOperation(function () {
            $this->initializeConnection(false);
        });
    }

    /**
     * @throws \RedisException
     */
    private function initializeWrite(): void
    {
        $this->retryOperation(function () {
            $this->initializeConnection(true);
        });
    }

    /**
     * Initializes the redis backend for writing (use only master) and reading (use all)
     * @param bool $useWriteConnection
     */
    private function initializeConnection(bool $useWriteConnection): void
    {
        try {
            $this->redis = new \Redis();
            $host = $this->hostname;
            $port = $this->port;

            if (true && $this->isSentinel) {
                $redisSentinel = new \RedisSentinel([
                    'host' => $this->sentinelHostname,
                    'port' => $this->sentinelPort,
                    'connectTimeout' => $this->connectionTimeout,
                    'persistent' => ($this->persistentConnection === true) ? 'cachebackend' : null,
                    'auth' => $this->sentinelPassword,
                ]);
                $sentinelMaster = $redisSentinel->masters();
                if ($sentinelMaster === false) {
                    throw new Exception('Could not get master from sentinel.', 1279765134);
                }
                $host = $sentinelMaster[0]['ip'];
                $port = $sentinelMaster[0]['port'];
            }

            if ($this->persistentConnection) {
                $this->connected = $this->redis->pconnect($host, $port, $this->connectionTimeout, (string)$this->database);
            } else {
                $this->connected = $this->redis->connect($host, $port, $this->connectionTimeout);
            }

            if ($this->connected && $this->password !== '') {
                $success = $this->redis->auth($this->password);
                if (!$success) {
                    throw new Exception('The given password was not accepted by the redis server.', 1279765134);
                }
            }
            if ($this->connected && $this->database >= 0) {
                $success = $this->redis->select($this->database);
                if (!$success) {
                    throw new Exception('The given database "' . $this->database . '" could not be selected.', 1279765144);
                }
            }
        } catch (\Throwable $e) {
            $this->logger->critical('Could not initialize connection to redis server.', [
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
     * @param string $sentinelHostname
     */
    public function setSentinelHostname(string $sentinelHostname): void
    {
        $this->sentinelHostname = $sentinelHostname;
    }

    /**
     * @param int $sentinelPort
     */
    public function setSentinelPort(int $sentinelPort): void
    {
        $this->sentinelPort = $sentinelPort;
    }

    public function setSentinelPassword(?string $sentinelPassword): void
    {
        $this->sentinelPassword = $sentinelPassword;
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
        $this->initializeRead();
        if ($this->connected) {
            try {
                return $this->retryOperation(function () use ($entryIdentifier) {
                    return parent::get($entryIdentifier);
                });
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
        $this->initializeRead();
        if ($this->connected) {
            try {
                return $this->retryOperation(function () use ($entryIdentifier) {
                    return parent::has($entryIdentifier);
                });
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
        $this->initializeRead();
        if ($this->connected) {
            try {
                return $this->retryOperation(function () use ($tag) {
                    return parent::findIdentifiersByTag($tag);
                });
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
        $this->initializeWrite();
        if ($this->connected) {
            try {
                $this->retryOperation(function () use ($entryIdentifier, $data, $tags, $lifetime) {
                    parent::set($entryIdentifier, $data, $tags, $lifetime);
                });
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
        $this->initializeWrite();
        if ($this->connected) {
            try {
                $this->retryOperation(function () use ($entryIdentifier) {
                    parent::remove($entryIdentifier);
                });
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
     *
     * We not retry this operation because it is not critical and should not
     * cause any problems if it fails.
     */
    public function collectGarbage(): void
    {
        $this->initializeWrite();
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
        $this->initializeWrite();
        if ($this->connected) {
            try {
                $this->retryOperation(function () {
                    parent::flush();
                });
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
        $this->initializeWrite();
        if ($this->connected) {
            try {
                $this->retryOperation(function () use ($tag) {
                    parent::flushByTag($tag);
                });
            } catch (\Throwable $e) {
                $this->logger->critical('Error while flushing cache tag in Redis Cache', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }
    }

    /**
     * Retry the given operation a few times before giving up, maybe the redis server is failing over
     * or the connection is lost for a short time.
     * @param callable $operation
     * @param int $retryCount
     * @param int $delay
     * @return mixed
     * @throws \RedisException
     */
    private function retryOperation(callable $operation, int $retryCount = 3, int $delay = 100): mixed
    {
        for ($attempt = 0; $attempt < $retryCount; $attempt++) {
            try {
                return $operation();
            } catch (\RedisException $e) {
                if ($this->isPermanentException($e)) {
                    throw $e;
                }
                if ($attempt === $retryCount - 1) {
                    throw $e;
                }
                // Wait for a while before retrying
                usleep($delay * ($attempt + 1));
            }
        }
        return null;
    }

    /**
     * Check if the given exception is permanent or temporary
     * @param \RedisException $e
     * @return bool
     */
    private function isPermanentException(\RedisException $e): bool
    {
        // Check for authentification errors
        if (str_contains($e->getMessage(), 'AUTH')) {
            return true; // Authentification errors are permanent
        }

        // Check for configuration errors
        $configurationErrors = ['host', 'port', 'database'];
        foreach ($configurationErrors as $errorString) {
            if (str_contains($e->getMessage(), $errorString)) {
                return true; // Configuration errors are permanent
            }
        }

        return false;
    }
}
