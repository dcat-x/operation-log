<?php

declare(strict_types=1);

namespace Dcat\Admin\OperationLog;

use Dcat\Admin\Extend\Setting as Form;
use Dcat\Admin\OperationLog\Models\OperationLog;
use Dcat\Admin\Support\Helper;

class Setting extends Form
{
    public function title(): string
    {
        return $this->trans('log.title');
    }

    protected function formatInput(array $input): array
    {
        $input['except'] = Helper::array($input['except'] ?? []);
        $input['allowed_methods'] = Helper::array($input['allowed_methods'] ?? []);

        return $input;
    }

    public function form(): void
    {
        $this->tags('except');
        $this->multipleSelect('allowed_methods')
            ->options(array_combine(OperationLog::$methods, OperationLog::$methods));
        $this->tags('secret_fields');
    }
}
