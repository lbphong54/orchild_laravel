<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait EnumParser
{
    public static function collect(): Collection
    {
        return collect(self::cases());
    }

    public static function keys(): array
    {
        return self::collect()
            ->pluck('name')
            ->all();
    }

    public static function values(): array
    {
        return self::collect()
            ->pluck('value')
            ->all();
    }

    public static function options($only = []): array
    {
        return self::collect()
            ->when(!empty($only), function ($q) use ($only) {
                return $q->whereIn('value', $only);
            })
            ->mapWithKeys(fn($enum, $index) => [$enum->value => $enum->label()])
            ->all();
    }

    public function label(array $attributes = [], string $key = null): string
    {
        return $this->getLabelByValue($this->value, $attributes, $key);
    }

    private function getLabelByValue(string $value, array $attributes = [], string $key = null): string
    {
        return trans(($key ?: ('enums.' . Str::snake(class_basename(self::class)))) . '.' . $value, $attributes);
    }
}
