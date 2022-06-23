<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@gitlab-test.avangard-mb.ru:avangard/spareparts-avangard.ru.git');

add('shared_files', []);

add('shared_dirs', []);

add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/app/private',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts
host(env('URL_HOST'))
    ->set('remote_user', 'avangard')
    ->set('identityFile', '~/.ssh/kubepv')
    ->set('deploy_path', '/var/www/projects/spareparts-api.avangard-mb.ru');

// Tasks
task('build', function () {
    cd('/var/www/projects/spareparts-api.avangard-mb.ru/');
    run('docker-compose down');
    run('cp /var/www/projects/spareparts-api.avangard-mb.ru/current/docker-compose.production.yml /var/www/projects/spareparts-api.avangard-mb.ru/docker-compose.yml');
    run('rm /var/www/projects/spareparts-api.avangard-mb.ru/current/.env');
    run('cp /var/www/projects/spareparts-api.avangard-mb.ru/current/.env.example /var/www/projects/spareparts-api.avangard-mb.ru/current/.env');
    run('docker-compose build');
    run('docker-compose up -d');
    run('docker exec -t php_spareparts composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts');
    run('docker exec -t php_spareparts php artisan key:generate');
    run('docker exec -t php_spareparts php artisan rabbitMqConsumer:createCart');
    run('docker exec -t php_spareparts php artisan rabbitMqConsumer:createOrder');
});

task('deploy', [
    'deploy:prepare',
    'deploy:publish',
]);

after('deploy:failed', 'deploy:unlock');
