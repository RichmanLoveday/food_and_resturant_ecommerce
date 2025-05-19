<?php

/**
 * Create unique slug for a given model and name.
 *
 * @param string $model The model name (e.g., 'User')
 * @param string $name The string to generate the slug from
 * @return string The unique slug
 * @throws \InvalidArgumentException If the model class does not exist
 */
if (!function_exists('generateUniqueSlug')) {
    function generateUniqueSlug($model, $name): string
    {
        $modelClass = "App\\Models\\$model";

        // Check if model does not exist and throw an exception
        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model $model not found.");
        }

        $slug = \Str::slug($name);
        $count = 2;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = \Str::slug($name) . '-' . $count;
            $count++;
        }

        return $slug;
    }
}


/**
 * Format price with currency icon based on position setting.
 *
 * @param int|float $price The price to format
 * @return string The formatted price with currency icon
 */
if (!function_exists('currencyPosition')) {
    function currencyPosition(int|float $price): string
    {
        if (config('settings.site_currency_icon_position') === 'left') {
            return config('settings.site_currency_icon') .  $price;
        } else {
            return $price . config('settings.site_currency_icon');
        }
    }
}