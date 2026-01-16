<?php

declare(strict_types=1);

arch('source files use strict types')
    ->expect('Dcat\Admin\OperationLog')
    ->toUseStrictTypes();

arch('no debugging statements')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();
