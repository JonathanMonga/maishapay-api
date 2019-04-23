<?php
namespace Maishapay\AppTest;

require __DIR__ . '/../../vendor/autoload.php';

use Slim\Container;

class Bootstrap
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    /**
     * Fetch the configured container
     *
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        if (null === self::$container) {
            // Use an in-memory database when testing
            putenv('DB_DSN = mysql:host=localhost;dbname=maishapay-api-test');

            $settings = include __DIR__ . '../../src/settings.php';

            $container = new Container(['settings' => $settings]);
            include __dir__ . '../../src/dependencies.php';

            self::$container = $container;
        }

        return self::$container;
    }
}
