<?php
/**
 * Simple file-based caching system
 * Helps reduce database load by caching query results
 */

// Cache directory
define('CACHE_DIR', __DIR__ . '/cache/');

/**
 * Get cached data
 * @param string $key Cache key
 * @return mixed|null Returns cached data or null if not found/expired
 */
function cache_get($key)
{
    $cache_file = CACHE_DIR . md5($key) . '.cache';

    if (!file_exists($cache_file)) {
        return null;
    }

    $cache_data = @file_get_contents($cache_file);
    if ($cache_data === false) {
        return null;
    }

    $cache_data = @unserialize($cache_data);
    if ($cache_data === false) {
        return null;
    }

    // Check if expired
    if (isset($cache_data['expires']) && $cache_data['expires'] < time()) {
        @unlink($cache_file);
        return null;
    }

    return isset($cache_data['data']) ? $cache_data['data'] : null;
}

/**
 * Set cache data
 * @param string $key Cache key
 * @param mixed $data Data to cache
 * @param int $ttl Time to live in seconds (default: 3600 = 1 hour)
 * @return bool Success status
 */
function cache_set($key, $data, $ttl = 3600)
{
    // Create cache directory if it doesn't exist
    if (!is_dir(CACHE_DIR)) {
        @mkdir(CACHE_DIR, 0755, true);
    }

    $cache_file = CACHE_DIR . md5($key) . '.cache';

    $cache_data = array(
        'expires' => time() + $ttl,
        'data' => $data
    );

    $result = @file_put_contents($cache_file, serialize($cache_data), LOCK_EX);
    return $result !== false;
}

/**
 * Delete cached data
 * @param string $key Cache key
 * @return bool Success status
 */
function cache_delete($key)
{
    $cache_file = CACHE_DIR . md5($key) . '.cache';

    if (file_exists($cache_file)) {
        return @unlink($cache_file);
    }

    return true;
}

/**
 * Clear all cache
 * @return bool Success status
 */
function cache_clear_all()
{
    if (!is_dir(CACHE_DIR)) {
        return true;
    }

    $files = glob(CACHE_DIR . '*.cache');
    foreach ($files as $file) {
        @unlink($file);
    }

    return true;
}

/**
 * Get or set cache with callback
 * @param string $key Cache key
 * @param callable $callback Function to generate data if cache miss
 * @param int $ttl Time to live in seconds
 * @return mixed Cached or generated data
 */
function cache_remember($key, $callback, $ttl = 3600)
{
    $data = cache_get($key);

    if ($data !== null) {
        return $data;
    }

    $data = $callback();
    cache_set($key, $data, $ttl);

    return $data;
}
?>