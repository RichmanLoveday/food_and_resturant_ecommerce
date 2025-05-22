<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Add product in to cart
     */

    public function addToCart(Request $request): Response|JsonResponse
    {
        // dd($request->product_option);
        try {

            $product = Product::with(['productOptions', 'productSizes'])->findOrFail($request->product_id);
            $productSize = $product->productSizes->where('id', $request->product_size)->first();
            $prodcutOptions = $product->productOptions->whereIn('id', $request->product_option);
            // dd($prodcutOptions);
            //? create options value needed in Cart Fasade
            $options = [
                'product_size' => [],
                'product_options' => [],
                'product_info' => [
                    'image' => $product->thumb_image,
                    'slug' => $product->slug,
                ],
            ];


            //? check if productsize exist
            if (!is_null($productSize)) {
                $options['product_size'] = [
                    'id' => $productSize?->id,
                    'name' => $productSize?->name,
                    'price' => $productSize?->price
                ];
            }

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
            ], 200);
        } catch (\Exception $e) {
            logger("Unable to add product in cart: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }


    public function getCartProducts()
    {
        $product = Cart::content();
        return view('frontend.layout.ajax-files.sidebar-cart-item')->render();
    }

    /**
     * Remove cart product from session
     *
     * @param string $rowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartProductRemove(string $rowId): JsonResponse
    {
        // dd($rowId);
        try {
            Cart::remove($rowId);

            return response()->json([
                'status' => 'success',
                'message' => 'Item removed from cart successfully!'
            ], 200);
        } catch (\Exception $e) {
            logger('Unable to remove item from cart: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry something went wrong!'
            ], 500);
        }
    }
}