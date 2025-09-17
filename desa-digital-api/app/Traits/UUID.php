<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            if ($model->getKey() === null) {
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
