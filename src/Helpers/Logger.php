<?php
/**
 * Logger Helper
 * 
 * Provides logging functionality for errors and application events.
 * Supports multiple log levels and structured logging.
 * 
 * PHP Version: 8.0+
 */

namespace App\Helpers;

class Logger
{
    /**
     * Log levels
     */
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_INFO = 'INFO';
    const LEVEL_DEBUG = 'DEBUG';

    /**
     * Log file path
     * @var string
     */
    private static string $logFile;

    /**
     * Initialize logger
     * 
     * @return void
     */
    private static function init(): void
    {
        if (!isset(self::$logFile)) {
            $logDir = dirname(__DIR__, 2) . '/logs';
            
            // Create logs directory if not exists
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            self::$logFile = $logDir . '/app-' . date('Y-m-d') . '.log';
        }
    }

    /**
     * Log error message
     * 
     * @param string $message Error message
     * @param array $context Additional context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        self::log(self::LEVEL_ERROR, $message, $context);
    }

    /**
     * Log warning message
     * 
     * @param string $message Warning message
     * @param array $context Additional context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log(self::LEVEL_WARNING, $message, $context);
    }

    /**
     * Log info message
     * 
     * @param string $message Info message
     * @param array $context Additional context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        self::log(self::LEVEL_INFO, $message, $context);
    }

    /**
     * Log debug message
     * 
     * @param string $message Debug message
     * @param array $context Additional context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        self::log(self::LEVEL_DEBUG, $message, $context);
    }

    /**
     * Core logging function
     * 
     * @param string $level Log level
     * @param string $message Log message
     * @param array $context Additional context
     * @return void
     */
    private static function log(string $level, string $message, array $context = []): void
    {
        self::init();

        $timestamp = date('Y-m-d H:i:s');
        $contextJson = !empty($context) ? ' | ' . json_encode($context) : '';

        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextJson}" . PHP_EOL;

        // Write to log file
        error_log($logEntry, 3, self::$logFile);
    }
}