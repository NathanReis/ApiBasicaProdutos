<?php

namespace Source\DAOs;

use PDO;
use Source\Utils\Path;

class Connection
{
    private const FILE_NAME = "db.sqlite";
    private const OPTIONS = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    private const PASSWORD = "";
    private const USER = "";

    private static $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $path = Path::resolve(__DIR__, "..", "database");

            self::$instance = new PDO(
                "sqlite:" . Path::resolve($path, self::FILE_NAME),
                self::USER,
                self::PASSWORD,
                self::OPTIONS
            );
        }

        return self::$instance;
    }
}