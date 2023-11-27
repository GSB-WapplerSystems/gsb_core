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

use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Session\Backend\Exception\SessionNotCreatedException;
use TYPO3\CMS\Core\Session\Backend\Exception\SessionNotFoundException;
use TYPO3\CMS\Core\Session\Backend\Exception\SessionNotUpdatedException;
use TYPO3\CMS\Core\Session\Backend\HashableSessionBackendInterface;
use TYPO3\CMS\Core\Session\Backend\SessionBackendInterface;

/**
 * Class SentinelCapableRedisSessionBackend
 *
 * This session backend takes these optional configuration options: 'hostname' (default '127.0.0.1'),
 * 'database' (default 0), 'port' (default 3679) and 'password' (no default value), 'isSentinel' (default false),
 * .'persistentConnection' (default false), 'connectionTimeout' (default 0.0)
 *
 * Fails gracefully if redis is not available nad try to reconnect a few times before giving up.
 */
class SentinelCapableRedisSessionBackend implements SessionBackendInterface, HashableSessionBackendInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var \RedisSentinel
     */
    protected ?\RedisSentinel $redisSentinel = null;

    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * Indicates whether the server is connected
     *
     * @var bool
     */
    protected $connected = false;

    /**
     * Used as instance independent identifier
     * (e.g. if multiple installations write into the same database)
     *
     * @var string
     */
    protected $applicationIdentifier = '';

    /**
     * Instance of the PHP redis class
     *
     * @var \Redis
     */
    protected $redis;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * Initializes the session backend
     *
     * @param string $identifier Name of the session type, e.g. FE or BE
     * @internal To be used only by SessionManager
     */
    public function initialize(string $identifier, array $configuration)
    {
        $this->configuration = $configuration;
        $this->identifier = $identifier;
        $this->applicationIdentifier = 'typo3_ses_'
            . $identifier . '_'
            . sha1($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']) . '_';
    }

    /**
     * Checks if the configuration is valid
     *
     * @throws \InvalidArgumentException
     * @internal To be used only by SessionManager
     */
    public function validateConfiguration(): void
    {
        if (!extension_loaded('redis')) {
            throw new \RuntimeException(
                'The PHP extension "redis" must be installed and loaded in order to use the redis session backend.',
                1481269826
            );
        }

        if (isset($this->configuration['database'])) {
            if (!is_int($this->configuration['database'])) {
                throw new \InvalidArgumentException(
                    'The specified database number is of type "' . gettype($this->configuration['database']) .
                    '" but an integer is expected.',
                    1481270871
                );
            }

            if ($this->configuration['database'] < 0) {
                throw new \InvalidArgumentException(
                    'The specified database "' . $this->configuration['database'] . '" must be greater or equal than zero.',
                    1481270923
                );
            }
        }
    }

    public function hash(string $sessionId): string
    {
        // The sha1 hash ensures we have good length for the key.
        $key = sha1($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] . 'core-session-backend');
        return hash_hmac('sha256', $sessionId, $key);
    }

    /**
     * Read session data, fail gracefully if redis is not available
     *
     * @return array Returns the session data
     * @throws SessionNotFoundException
     */
    public function get(string $sessionId): array
    {
        try {
            $this->initializeRead();

            if ($this->connected === false) {
                throw new SessionNotFoundException('Redis is not connected', 1481885582);
            }

            $hashedSessionId = $this->hash($sessionId);
            $rawData = $this->redis->get($this->getSessionKeyName($hashedSessionId));
            if ($rawData !== false) {
                $decodedValue = json_decode($rawData, true);
                if (is_array($decodedValue)) {
                    return $decodedValue;
                }
            }
            throw new SessionNotFoundException('Session could not be fetched from redis', 1481885583);
        } catch (\Throwable $e) {
            $this->logger->critical('Could not fetch session from redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            $this->retryOperation(function () use ($sessionId) {
                $this->get($sessionId);
            });
        }
        return [];
    }

    /**
     * Delete a session record, fail gracefully if redis is not available
     */
    public function remove(string $sessionId): bool
    {
        try {
            $this->initializeWrite();

            if ($this->connected === false) {
                throw new SessionNotFoundException('Redis is not connected', 1481885582);
            }

            $deleteResult = $this->redis->del($this->getSessionKeyName($this->hash($sessionId)));

            // Redis delete result is either `int`, `false` or a `\Redis` multi mode object, where delete state cannot get
            // determined. Multi mode is not even supported by this session backend at all, therefore we handle this case as
            // "not successful".
            return is_int($deleteResult) && $deleteResult >= 1;
        } catch (\Throwable $e) {
            $this->logger->critical('Could not remove session from redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            $this->retryOperation(function () use ($sessionId) {
                $this->remove($sessionId);
            });
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
            $this->initializeWrite();

            if ($this->connected === false) {
                throw new SessionNotFoundException('Redis is not connected', 1481885582);
            }

            $hashedSessionId = $this->hash($sessionId);
            $sessionData['ses_id'] = $hashedSessionId;
            $sessionData['ses_tstamp'] = $GLOBALS['EXEC_TIME'] ?? time();

            // nx will not allow overwriting existing keys
            $jsonString = json_encode($sessionData);
            $wasSet = is_string($jsonString) && $this->redis->set(
                $this->getSessionKeyName($hashedSessionId),
                $jsonString,
                ['nx']
            );

            if (!$wasSet) {
                throw new SessionNotCreatedException('Session could not be written to Redis', 1481895647);
            }

            return $sessionData;
        } catch (\Throwable $e) {
            $this->logger->critical('Could not write session to redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            $this->retryOperation(function () use ($sessionId, $sessionData) {
                $this->set($sessionId, $sessionData);
            });
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
            $hashedSessionId = $this->hash($sessionId);
            try {
                $sessionData = array_merge($this->get($sessionId), $sessionData);
            } catch (SessionNotFoundException $e) {
                throw new SessionNotUpdatedException('Cannot update non-existing record', 1484389971, $e);
            }
            $sessionData['ses_id'] = $hashedSessionId;
            $sessionData['ses_tstamp'] = $GLOBALS['EXEC_TIME'] ?? time();

            $key = $this->getSessionKeyName($hashedSessionId);
            $jsonString = json_encode($sessionData);
            $wasSet = is_string($jsonString) && $this->redis->set($key, $jsonString);

            if (!$wasSet) {
                throw new SessionNotUpdatedException('Session could not be updated in Redis', 1481896383);
            }

            return $sessionData;
        } catch (\Throwable $e) {
            $this->logger->critical('Could not update session in redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            $this->retryOperation(function () use ($sessionId, $sessionData) {
                $this->update($sessionId, $sessionData);
            });
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
            foreach ($this->getAll() as $sessionRecord) {
                if (!($sessionRecord['ses_userid'] ?? false)) {
                    if ($maximumAnonymousLifetime > 0 && ($sessionRecord['ses_tstamp'] + $maximumAnonymousLifetime) < $GLOBALS['EXEC_TIME']) {
                        $this->redis->del($this->getSessionKeyName($sessionRecord['ses_id']));
                    }
                } else {
                    if (($sessionRecord['ses_tstamp'] + $maximumLifetime) < $GLOBALS['EXEC_TIME']) {
                        $this->redis->del($this->getSessionKeyName($sessionRecord['ses_id']));
                    }
                }
            }
        } catch (\Throwable $e) {
            $this->logger->critical('Could not collect garbage in redis', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
        }
    }

    private function initializeRead(): void
    {
        $this->retryOperation(function () {
            $this->initializeConnection(false);
        });
    }

    private function initializeWrite(): void
    {
        $this->retryOperation(function () {
            $this->initializeConnection(true);
        });
    }

    /**
     * Initializes the redis backend, fail gracefully if redis is not available
     *
     * @throws \RuntimeException if access to redis with password is denied or if database selection fails
     */
    protected function initializeConnection(bool $useWriteConnection): void
    {
        try {
            $this->redis = new \Redis();
            $host = $this->configuration['hostname'] ?? '127.0.01';
            $port = $this->configuration['port'] ?? 6379;

            if (array_key_exists('isSentinel', $this->configuration)  && $this->configuration['isSentinel'] && $useWriteConnection) {
                $this->redisSentinel = new \RedisSentinel([
                    'host' => $this->configuration['sentinelHostname'] ?? '127.0.0.1',
                    'port' => $this->configuration['sentinelPort'] ?? 26379,
                    'connectTimeout' => $this->configuration['connectionTimeout'] ?? 0.0,
                    'persistent' => ($this->configuration['persistentConnection'] === true) ? $this->identifier : null,
                    'auth' => $this->configuration['sentinelPassword'] ?? null,
                ]);
                $sentinelMaster = $this->redisSentinel->masters();
                if ($sentinelMaster === false) {
                    throw new Exception('Could not get master from sentinel.', 1279765134);
                }

                $host = $sentinelMaster[0]['ip'];
                $port = $sentinelMaster[0]['port'];
            }

            if ($this->configuration['persistentConnection']) {
                $this->connected = $this->redis->pconnect(
                    (string)$host,
                    (int)$port,
                    $this->configuration['connectionTimeout'] ?? 0.0,
                    $this->configuration['database'] ?? 0
                );
            } else {
                $this->connected = $this->redis->connect(
                    (string)$host,
                    (int)$port,
                    $this->configuration['connectionTimeout'] ?? 0.0
                );
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
        $this->initializeRead();

        if ($this->connected === false) {
            throw new SessionNotFoundException('Redis is not connected', 1481885582);
        }

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

    protected function getSessionKeyName(string $sessionId): string
    {
        return $this->applicationIdentifier . $sessionId;
    }

    protected function getSessionTimeout(): int
    {
        return (int)($GLOBALS['TYPO3_CONF_VARS'][$this->identifier]['sessionTimeout'] ?? 86400);
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
