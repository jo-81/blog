<?php

declare(strict_types=1);

use Tests\AbstractTestCase;

$_ENV['APP_ENV'] = 'testing';
$_SERVER['APP_ENV'] = 'testing';
putenv('APP_ENV=testing');

// On indique à Pest de lier tous les tests du dossier Feature à notre AbstractTestCase
uses(AbstractTestCase::class)->in('Feature');
