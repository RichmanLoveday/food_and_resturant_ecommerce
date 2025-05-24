<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{

    public function index(): View
    {
        $breadCrumb = ['title' => 'cart view', 'link' => '#'];
        return view('frontend.pages.cart-view', compact('breadCrumb'));
    }
    /**
     * Add product in to cart
     */

    public function addToCart(Request $request): Response|JsonResponse
    {
        // dd($request->all());
        try {

            $product = Product::with(['productOptions', 'productSizes'])->findOrFail($request->product_id);
            $productSize = $product->productSizes->where('id', $request->product_size)->first();
            $prodcutOptions = $product->productOptions->whereIn('id', $request->product_option);
            // dd($prodcutOptions);

            //? check if product quantiy is less than the requested quantity
            if ($product->quantity < $request->quantity) {
                // throw ValidationException::withMessages(['Quantity is not available!']);
                logger("Unable to add product in cart: " . 'Quantity is not available!');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quantity is not available!',
                ], 500);
            }

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


    /**
     * Update the quantity of a cart item.
     *
     * Handles the request to update the quantity of a specific item in the shopping cart.
     * Returns a JSON response indicating success or failure.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing 'rowId' and 'qty'.
     * @return \Illuminate\Http\JsonResponse       JSON response with status and message.
     */
    public function cartQtyUpdate(Request $request): JsonResponse
    {
        // dd($request->all());
        try {
            $cartItem = Cart::get($request->rowId);
            $product = Product::findOrFail($cartItem->id);      //? find product based on cart session

            // dd($product);

            //? check if product quantiy is less than the requested quantity
            if ($product->quantity < (int) $request->qty) {
                // throw ValidationException::withMessages(['Quantity is not available!']);
                logger("Unable to add product in cart: " . 'Quantity is not available!');
                return response()->json([
                    'status' => 'error',
                    'qty' => $cartItem->qty,
                    'message' => 'Quantity is not available!',
                ]);
            }

            //? update quantity in cart session
            $cart = Cart::update($request->rowId, $request->qty);
            return response()->json([
                'status' => 'success',
                'product_total' => productTotal($request->rowId),
                'qty' => $cart->qty,
                'message' => 'Updated Cart Successfully!'
            ], 200);
        } catch (\Exception $e) {
            logger("Unable to update quantity: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong, please reload the page!'
            ], 500);
        }
    }


    /**
     * Destroy all items in the cart.
     *
     * Clears the entire shopping cart and redirects back with a success message.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cartDestroy(): RedirectResponse
    {
        Cart::destroy();

        toastr()->success('Cart cleared successfully!');
        return redirect()->back();
    }
}