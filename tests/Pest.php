<?php

declare(strict_types=1);

use Tests\AbstractTestCase;

// On indique à Pest de lier tous les tests du dossier Feature à notre AbstractTestCase
uses(AbstractTestCase::class)->in('Feature');
