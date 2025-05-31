<?php


if (!function_exists('generateUniqueSlug')) {
    /**
     * Create unique slug for a given model and name.
     *
     * @param string $model The model name (e.g., 'User')
     * @param string $name The string to generate the slug from
     * @return string The unique slug
     * @throws \InvalidArgumentException If the model class does not exist
     */
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



if (!function_exists('currencyPosition')) {
    /**
     * Format price with currency icon based on position setting.
     *
     * @param int|float $price The price to format
     * @return string The formatted price with currency icon
     */
    function currencyPosition(int|float|string $price): string
    {
        if (config('settings.site_currency_icon_position') === 'left') {
            return config('settings.site_currency_icon') . ' ' .  $price;
        } else {
            return $price . ' ' .  config('settings.site_currency_icon');
        }
    }
}



if (!function_exists('cartTotal')) {
    /**
     * Calculate the total price of all items in the cart.
     *
     * @return int|float The total cart value
     */
    function cartTotal(): int|float
    {
        $total = 0;

        // Loop through cart content and sum all prices
        foreach (Cart::content() as $item) {
            $productPrice = $item->price;
            $sizePrice = $item->options?->product_size['price'] ?? 0;
            $optionsPrice = 0;

            foreach ($item->options->product_options as $option) {
                $optionsPrice += $option['price'];
            }

            // $total += ($productPrice + $sizePrice + $optionsPrice) * $item->qty;

            $total += number_format(($productPrice + $sizePrice + $optionsPrice) * $item->qty, 2, '.', '');
        }

        return $total;
    }
}


if (!function_exists('productTotal')) {
    /**
     * Calculate the total price of a product in the cart.
     *
     * @return int|float The total product value
     */
    function productTotal(string $rowId): int|float
    {
        $total = 0;

        $product = Cart::get($rowId);
        $productPrice = $product->price;
        $sizePrice = $product->options?->product_size['price'] ?? 0;
        $optionsPrice = 0;

        //? loop through options and calculate prices of options
        foreach ($product->options->product_options as $option) {
            $optionsPrice += $option['price'];
        }

        // round(($productPrice + $sizePrice + $optionsPrice) * $product->qty, 2);

        $total += number_format(($productPrice + $sizePrice + $optionsPrice) * $product->qty, 2, '.', '');

        return $total;
    }
}


if (!function_exists('grandCartTotal')) {
    /**
     * Grand cart total
     *
     * @return int|float The total product value
     */
    function grandCartTotal(int|float $deliveryFee = 0): int|float
    {
        $catTotal = cartTotal();
        $total = 0;

        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            $discount = $coupon['discount'];

            $total = number_format(($catTotal + $deliveryFee) - $discount, 2, '.', '');

            return $total;
        } else {
            $total = $catTotal;
            return $total;
        }
    }
}


if (!function_exists('generateInvoiceId')) {
    /**
     * Generate invoice id
     *
     * @return int|float The total product value
     */
    function generateInvoiceId(): int|string
    {
        $randomNumber = rand(1, 9999);
        $currentDateTime = now();

        $invoiceId = $randomNumber . $currentDateTime->format('ymd') . $currentDateTime->format('s');

        return $invoiceId;
    }
}