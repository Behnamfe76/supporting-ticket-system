<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            $model->{$model->getSlugField()} = $model->generateUniqueSlug();
        });
    }

    /**
     * Define the field name used for the slug.
     */
    protected function getSlugField(): string
    {
        return property_exists($this, 'slugField') ? $this->slugField : 'slug';
    }

    /**
     * Define your signature configuration here or override in a model.
     */
    protected function getSlugSignature(): array
    {
        return property_exists($this, 'slugSignature') ? $this->slugSignature : [
            'length' => 8,
            'type'   => 'alphanumeric', // options: numeric, alpha, alphanumeric
            'prefix' => '',             // optional prefix
            'suffix' => '',             // optional suffix
        ];
    }

    /**
     * Generate a unique slug with a given pattern.
     */
    public function generateUniqueSlug(): string
    {
        $signature = $this->getSlugSignature();
        $field = $this->getSlugField();

        do {
            $slug = $signature['prefix'] ?? '';
            $slug .= match ($signature['type'] ?? 'alphanumeric') {
                'numeric' => str_pad(random_int(0, pow(10, $signature['length']) - 1), $signature['length'], '0', STR_PAD_LEFT),
                'alpha' => collect(range('A', 'Z'))->random($signature['length'])->implode(''),
                default => Str::upper(Str::random($signature['length'])),
            };
            $slug .= $signature['suffix'] ?? '';
        } while (
            static::where($field, $slug)->exists()
        );

        return $slug;
    }
}
