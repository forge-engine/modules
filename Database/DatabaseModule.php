<?php

namespace Forge\Modules\Database;

use Forge\Core\Helpers\App;
use Forge\Modules\Database\Adapters\InMemoryAdapter;
use Forge\Modules\Database\Adapters\MysqlAdapter;
use Forge\Modules\Database\Adapters\PostgresqlAdapter;
use Forge\Modules\Database\Adapters\RedisAdapter;
use Forge\Modules\Database\Adapters\SqliteAdapter;
use Forge\Core\Contracts\Modules\ModulesInterface;
use Forge\Core\DependencyInjection\Container;
use Forge\Modules\Database\Contracts\DatabaseInterface;

class DatabaseModule extends ModulesInterface
{
    public function register(Container $container): void
    {
        $config = App::config();
        $databaseParams = $config->get('database');
        $connection = $databaseParams['connections'][$databaseParams['default']];
        $connectionName = $connection['database'];

        $adapter = match ($connection['driver']) {
            'mysql' => new MysqlAdapter($container, $connectionName),
            'pgsql' => new PostgresqlAdapter($container, $connectionName),
            'sqlite' => new SqliteAdapter($container, $connectionName),
            'memory' => new InMemoryAdapter($container, $connectionName),
            'redis' => new RedisAdapter(),
            default => throw new \RuntimeException("Unsupported driver")
        };

        $adapter->connect($connection);
        $container->instance(DatabaseInterface::class, $adapter);
    }
}