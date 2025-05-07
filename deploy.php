<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:emmalotta/hr.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('ta23rand.itmajakas.ee')
    ->set('remote_user', 'virt123070')
    ->set('http_user', 'virt123070')
    ->set('identify_file', '~/.ssh/id_rsa_zone')
    ->set('deploy_path', '~/domeenid/www.ta23rand.itmajakas.ee/hajus"');

// Hooks

after('deploy:failed', 'deploy:unlock');
