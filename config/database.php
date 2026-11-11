<?php
/**
 * Database Connection & PDO Abstraction Layer
 * Supports SQLite3 (default zero-config) and MySQL via PDO
 * Profile: Bajrang Kumar Sarraf - Cybersecurity Specialist & Web Developer
 */

declare(strict_types=1);

class Database {
    private static ?PDO $instance = null;

    // Database Driver Config: 'sqlite' or 'mysql'
    public const DB_DRIVER = 'sqlite';

    // SQLite configuration
    public const SQLITE_FILE = __DIR__ . '/../storage/portfolio.sqlite';

    // MySQL configuration (if switched to MySQL)
    public const MYSQL_HOST = '127.0.0.1';
    public const MYSQL_PORT = '3306';
    public const MYSQL_DATABASE = 'bajrang_portfolio';
    public const MYSQL_USERNAME = 'root';
    public const MYSQL_PASSWORD = '';
    public const MYSQL_CHARSET = 'utf8mb4';

    /**
     * Get or initialize the singleton PDO connection
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                if (self::DB_DRIVER === 'sqlite') {
                    $dir = dirname(self::SQLITE_FILE);
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0755, true);
                    }

                    $dsn = 'sqlite:' . self::SQLITE_FILE;
                    self::$instance = new PDO($dsn, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_TIMEOUT => 5
                    ]);

                    // Enable foreign keys and Write-Ahead Logging (WAL) for speed & concurrency
                    self::$instance->exec('PRAGMA foreign_keys = ON;');
                    self::$instance->exec('PRAGMA journal_mode = WAL;');
                } else {
                    $dsn = sprintf(
                        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                        self::MYSQL_HOST,
                        self::MYSQL_PORT,
                        self::MYSQL_DATABASE,
                        self::MYSQL_CHARSET
                    );

                    self::$instance = new PDO($dsn, self::MYSQL_USERNAME, self::MYSQL_PASSWORD, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                    ]);
                }
            } catch (PDOException $e) {
                error_log('Database Connection Failure: ' . $e->getMessage());
                throw new RuntimeException('Database connection failed. Please ensure database is configured or run config/setup.php.');
            }
        }

        return self::$instance;
    }

    /**
     * Helper to test if database connection is alive and initialized
     */
    public static function isInitialized(): bool {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admins'");
            return (bool)$stmt->fetch();
        } catch (Throwable) {
            return false;
        }
    }
}
