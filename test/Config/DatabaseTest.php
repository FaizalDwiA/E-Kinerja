<?php

namespace BerkahSoloWeb\EKinerja\Config;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testName() {
        $connection = Database::getConnection();
        self::assertNotNull($connection);
    }

    public function testGetConnectionSingleton() {
        $connection1 = Database::getConnection();
        $connection2 = Database::getConnection();
        self::assertSame($connection1, $connection2);
    }
}