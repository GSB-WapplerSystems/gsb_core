<?php

declare(strict_types=1);

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

namespace ITZBund\GsbCore\Session\Backend;

use TYPO3\CMS\Core\Session\Backend\Exception\SessionNotCreatedException;
use TYPO3\CMS\Core\Session\Backend\Exception\SessionNotFoundException;
use TYPO3\CMS\Core\Session\Backend\Exception\SessionNotUpdatedException;
use TYPO3\CMS\Core\Session\Backend\RedisSessionBackend;

/**
 * Class SentinelCapableRedisSessionBackend
 *
 * This session backend takes these optional configuration options: 'hostname' (default '127.0.0.1'),
 * 'database' (default 0), 'port' (default 3679) and 'password' (no default value), 'isSentinel' (default false),
 * .'persistentConnection' (default false), 'connectionTimeout' (default 0.0)
 *
 * Fails gracefully if redis is not available.
 */
class SentinelCapableRedisSessionBackend extends RedisSessionBackend
{
    /**
     * @var \RedisSentinel
     */
    protected ?\RedisSentinel $redisSentinel = null;

    /**
     * Read session data, fail gracefully if redis is not available
     *
     * @return array Returns the session data
     * @throws SessionNotFoundException
     */
    public function get(string $sessionId): array
    {
        try {
            return parent::get($sessionId);
        } catch (\Throwable $e) {
            $this->logger->critical('Could not fetch session from redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
        }
        return [];
    }

    /**
     * Delete a session record, fail gracefully if redis is not available
     */
    public function remove(string $sessionId): bool
    {
        try {
            return parent::remove($sessionId);
        } catch (\Throwable $e) {
            $this->logger->critical('Could not remove session from redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
        }
        return false;
    }

    /**
     * Write session data. This method prevents overriding existing session data.
     * ses_id will always be set to $sessionId and overwritten if existing in $sessionData
     * This method updates ses_tstamp automatically
     * This method will fail gracefully if redis is not available
     *
     * @return array The newly created session record.
     * @throws SessionNotCreatedException
     */
    public function set(string $sessionId, array $sessionData): array
    {
        try {
            return parent::set($sessionId, $sessionData);
        } catch (\Throwable $e) {
            $this->logger->critical('Could not write session to redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
        }
        return [];
    }

    /**
     * Updates the session data.
     * ses_id will always be set to $sessionId and overwritten if existing in $sessionData
     * This method updates ses_tstamp automatically
     * This method will fail gracefully if redis is not available
     *
     * @param array $sessionData The session data to update. Data may be partial.
     * @return array $sessionData The newly updated session record.
     * @throws SessionNotUpdatedException
     */
    public function update(string $sessionId, array $sessionData): array
    {
        try {
            return parent::update($sessionId, $sessionData);
        } catch (\Throwable $e) {
            $this->logger->critical('Could not update session in redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
        }
        return [];
    }

    /**
     * Garbage Collection, fail gracefully if redis is not available
     *
     * @param int $maximumLifetime maximum lifetime of authenticated user sessions, in seconds.
     * @param int $maximumAnonymousLifetime maximum lifetime of non-authenticated user sessions, in seconds. If set to 0, nothing is collected.
     */
    public function collectGarbage(int $maximumLifetime, int $maximumAnonymousLifetime = 0): void
    {
        try {
            parent::collectGarbage($maximumLifetime, $maximumAnonymousLifetime);
        } catch (\Throwable $e) {
            $this->logger->critical('Could not collect garbage in redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
        }
    }

    /**
     * Initializes the redis backend, fail gracefully if redis is not available
     *
     * @throws \RuntimeException if access to redis with password is denied or if database selection fails
     */
    protected function initializeConnection(): void
    {
        if ($this->connected) {
            return;
        }

        try {
            if ($this->configuration['isSentinel']) {
                $this->redisSentinel = new \RedisSentinel([
                    'host' => $this->configuration['hostname'] ?? '127.0.0.1',
                    'port' => $this->configuration['port'] ?? 26379,
                    'connectTimeout' => $this->configuration['connectionTimeout'] ?? 0.0,
                    'persistent' => ($this->configuration['persistentConnection'] === true) ? $this->identifier : null,
                ]);
                $sentinelMaster = $this->redisSentinel->masters();
                if ($sentinelMaster === false) {
                    throw new Exception('Could not get master from sentinel.', 1279765134);
                }
                if ($this->configuration['persistentConnection']) {
                    $this->connected = $this->redis->pconnect(
                        (string)$sentinelMaster[0]['ip'],
                        (int)$sentinelMaster[0]['port'],
                        $this->configuration['connectionTimeout'] ?? 0.0,
                        $this->configuration['database'] ?? 0
                    );
                } else {
                    $this->connected = $this->redis->connect(
                        (string)$sentinelMaster[0]['ip'],
                        (int)$sentinelMaster[0]['port'],
                        $this->configuration['connectionTimeout'] ?? 0.0
                    );
                }
            } else {
                if ($this->configuration['persistentConnection']) {
                    $this->connected = $this->redis->pconnect(
                        $this->configuration['hostname'] ?? '127.0.0.1',
                        $this->configuration['port'] ?? 6379,
                        $this->configuration['connectionTimeout'] ?? 0.0,
                        $this->configuration['database'] ?? 0
                    );
                } else {
                    $this->connected = $this->redis->connect(
                        $this->configuration['hostname'] ?? '127.0.0.1',
                        $this->configuration['port'] ?? 6379,
                        $this->configuration['connectionTimeout'] ?? 0.0,
                    );
                }
            }
        } catch (\RedisException $e) {
            $this->logger->alert('Could not connect to redis server.', ['exception' => $e]);
        }

        if (!$this->connected) {
            throw new \RuntimeException(
                'Could not connect to redis server at ' . $this->configuration['hostname'] . ':' . $this->configuration['port'],
                1482242961
            );
        }

        if (isset($this->configuration['password'])
            && $this->configuration['password'] !== ''
            && !$this->redis->auth($this->configuration['password'])
        ) {
            throw new \RuntimeException(
                'The given password was not accepted by the redis server.',
                1481270961
            );
        }

        if (isset($this->configuration['database'])
            && $this->configuration['database'] > 0
            && !$this->redis->select($this->configuration['database'])
        ) {
            throw new \RuntimeException(
                'The given database "' . $this->configuration['database'] . '" could not be selected.',
                1481270987
            );
        }
    }

    /**
     * List all sessions
     *
     * @return array Return a list of all user sessions. The list may be empty.
     */
    public function getAll(): array
    {
        $this->initializeConnection();

        $keys = [];
        // Initialize our iterator to null, needed by redis->scan
        $iterator = null;
        $this->redis->setOption(\Redis::OPT_SCAN, (string)\Redis::SCAN_RETRY);
        $pattern = $this->getSessionKeyName('*');
        // retry when we get no keys back, redis->scan returns a chunk (array) of keys per iteration
        while (($keyChunk = $this->redis->scan($iterator, $pattern)) !== false) {
            foreach ($keyChunk as $key) {
                $keys[] = $key;
            }
        }

        $encodedSessions = $this->redis->mGet($keys);
        if (!is_array($encodedSessions)) {
            return [];
        }

        $sessions = [];
        foreach ($encodedSessions as $session) {
            if (is_string($session)) {
                $decodedSession = json_decode($session, true);
                if ($decodedSession) {
                    $sessions[] = $decodedSession;
                }
            }
        }

        return $sessions;
    }
}
