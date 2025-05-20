<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Add product in to cart
     */

    public function addToCart(Request $request)
    {
        try {
            $product = Product::with(['productOptions', 'productSizes'])->findOrFail($request->product_id);
            $productSize = $product->productSizes->where('id', $request->product_size)->first();
            $prodcutOptions = $product->productOptions->whereIn('id', $request->product_option);

            //? create options value needed in Cart Fasade
            $options = [
                'product_size' => [
                    'id' => $productSize?->id,
                    'name' => $productSize?->name,
                    'price' => $productSize?->price
                ],
                'product_options' => [],
                'product_info' => [
                    'image' => $product->thumb_image,
                    'slug' => $product->slug,
                ],
            ];


            //? append product options to options array
            foreach ($prodcutOptions as $option) {
                $options['product_options'][] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'price' => $option->price,
                ];
            }

            //? add items to cart using Gloudemans shopping cart library
            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $request->quantity,
                'price' => $product->offer_price > 0 ? $product->offer_price : $product->price,
                'weight' => 0,
                'options' => $options,
            ]);


            return response()->json([
                'status' => 'success',
                'message' => 'Product added to cart',
            ]);
        } catch (\Exception $e) {
            logger("Unable to add product in cart: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ]);
        }

        dd($options);
    }
}