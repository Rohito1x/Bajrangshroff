<?php
/**
 * Database Connection & PDO Abstraction Layer
 * Supports MySQL / MariaDB (Active) and SQLite3 fallback via PDO
 * Profile: Bajrang Kumar Sarraf - Cybersecurity Specialist & Web Developer
 */

declare(strict_types=1);

class Database {
    private static ?PDO $instance = null;

    // Database Driver Config: 'mysql' (default) or 'sqlite'
    public const DB_DRIVER = 'mysql';

    // SQLite configuration fallback
    public const SQLITE_FILE = __DIR__ . '/../storage/portfolio.sqlite';

    // MySQL configuration (Supports Environment Variables for Azure / Docker / Cloud)
    public static function getHost(): string {
        return getenv('DB_HOST') ?: '127.0.0.1';
    }

    public static function getPort(): string {
        return getenv('DB_PORT') ?: '3306';
    }

    public static function getDatabaseName(): string {
        return getenv('DB_NAME') ?: 'bajrang_portfolio';
    }

    public static function getUsername(): string {
        return getenv('DB_USER') ?: 'root';
    }

    public static function getPassword(): string {
        $env = getenv('DB_PASS');
        return $env !== false ? $env : '';
    }

    public static function getDriver(): string {
        $envDriver = getenv('DB_DRIVER');
        return $envDriver ? strtolower($envDriver) : self::DB_DRIVER;
    }

    /**
     * Get or initialize the singleton PDO connection
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $driver = self::getDriver();

            try {
                if ($driver === 'sqlite') {
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

                    self::$instance->exec('PRAGMA foreign_keys = ON;');
                    self::$instance->exec('PRAGMA journal_mode = WAL;');
                } else {
                    $host = self::getHost();
                    $port = self::getPort();
                    $dbName = self::getDatabaseName();
                    $user = self::getUsername();
                    $pass = self::getPassword();

                    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $dbName);
                    
                    try {
                        self::$instance = new PDO($dsn, $user, $pass, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false,
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                        ]);
                    } catch (PDOException $pe) {
                        // If database does not exist (error 1049), connect without dbname and create it automatically
                        if ($pe->getCode() == 1049 || str_contains($pe->getMessage(), 'Unknown database')) {
                            $dsnNoDb = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $host, $port);
                            $initPdo = new PDO($dsnNoDb, $user, $pass, [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                            ]);
                            $initPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                            unset($initPdo);

                            // Re-connect to newly created database
                            self::$instance = new PDO($dsn, $user, $pass, [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::ATTR_EMULATE_PREPARES => false,
                                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                            ]);
                        } else {
                            throw $pe;
                        }
                    }
                }
            } catch (PDOException $e) {
                error_log('Database Connection Failure: ' . $e->getMessage());
                throw new RuntimeException('Database connection failed (' . $e->getMessage() . '). Please ensure MySQL service is running or run config/setup.php.');
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
            if (self::getDriver() === 'sqlite') {
                $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admins'");
            } else {
                $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
            }
            return (bool)$stmt->fetch();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Get display name of current database engine
     */
    public static function getDriverName(): string {
        $driver = self::getDriver();
        if ($driver === 'sqlite') {
            return 'SQLite';
        }
        try {
            $pdo = self::getConnection();
            $version = (string)$pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
            if (stripos($version, 'mariadb') !== false) {
                return 'MariaDB';
            }
            return 'MySQL';
        } catch (Throwable) {
            return 'MySQL';
        }
    }
}
