<?php

use Banking\Account\Driven\Persistence\AccountRepository as AccountRepositoryAdapter;
use Banking\Account\Driven\Persistence\MySqlAdapter;
use Banking\Account\Model\AccountRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },
    MySqlAdapter::class => function (ContainerInterface $container) {
        return new MySqlAdapter($container->get(Connection::class));
    },
    AccountRepository::class => function (ContainerInterface $container) {
        return new AccountRepositoryAdapter($container->get(MySqlAdapter::class));
    },
    Connection::class => function () {
        return DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'charset' => 'utf8',
            'host' => 'database.localhost',
            'port' => '3306',
            'dbname' => 'bank',
            'user' => 'dev',
            'password' => 'dev'
        ]);
    }
];
