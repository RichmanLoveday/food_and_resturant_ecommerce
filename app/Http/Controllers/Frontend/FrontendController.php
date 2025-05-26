<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SectionTitle;
use App\Models\Slider;
use App\Models\WhyChooseUs;
use App\Traits\SectionTitlesTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;


class FrontendController extends Controller
{
    use SectionTitlesTrait;

    public function index(): View
    {
        $sliders = Slider::where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        $sectionTitles = $this->getSectionTitles($this->getSectionKeys());
        $whyChooseUs = WhyChooseUs::where('status', 1)
            ->get();

        $categories = Category::where(['show_at_home' => true, 'status' => true])
            ->orderBy('id', 'desc')
            ->get();

        //? get menu items
        $menuItems = $this->menuItems($categories);
        // dd($menuItems);


        return view('frontend.home.index', compact(
            'sliders',
            'sectionTitles',
            'whyChooseUs',
            'categories',
            'menuItems',

        ));
    }



    public function showProduct(string|int $slug): View
    {
        $product = Product::with(['category', 'productImages', 'productSizes', 'productOptions'])
            ->where(['slug' => $slug, 'status' => true])
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'productImages', 'productSizes', 'productOptions'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(8)
            ->latest()
            ->get();

        // dd($relatedProducts);
        $breadCrumb = ['title' => 'menu details', 'link' => '#'];

        // dd($product);
        return view('frontend.pages.product-view', compact(
            'breadCrumb',
            'product',
            'relatedProducts'
        ));
    }


    /**
     * Retrieve the keys for the "Why Choose Us" section.
     *
     * @return array An array of section key strings used for the "Why Choose Us" content.
     */
    protected function getSectionKeys(): array
    {
        return [
            'why_choose_us_top_title',
            'why_choose_us_main_title',
            'why_choose_us_sub_title'
        ];
    }


    /**
     * Retrieve menu items grouped by category slug.
     *
     * Loops through the given categories, fetches up to 8 products for each category
     * that are active and set to show at home, and groups them by the category slug.
     *
     * @param array|Collection $categories
     * @return Collection|array
     */
    protected function menuItems(array|Collection $categories): Collection|array
    {
        $menuItems = [];
        //? loop through categories
        foreach ($categories as $category) {
            //? find products related to specific category
            $product = Product::with(['category'])
                ->where([
                    'category_id' => $category->id,
                    'status' => true,
                    'show_at_home' => true
                ])->orderBy('id', 'desc')
                ->take(8)
                ->get();

            //? store in array or collection for menu items
            $menuItems[] = $product;
        }

        //? return a collecition of menuitems
        return collect($menuItems);
    }


    /**
     * Ajax method to load a specific product and returns the product detail to a specific view
     * 
     * @param string|int $productId
     * @return view
     */

    public function loadProductModal(string|int $productId)
    {
        $product = Product::with(['category', 'productSizes', 'productOptions'])
            ->findOrFail($productId);

        return view('frontend.layout.ajax-files.product-popup-modal', compact('product'));
    }


    public function applyCoupon(Request $request): Response|JsonResponse
    {
        //? validate the request
        $request->validate([
            'code' => 'required|string|max:255',
            'subTotal' => 'required|numeric|min:0',
        ]);

        $code = $request->code;
        $subTotal = $request->subTotal;

        //? check if coupon code is valid
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return  response()->json([
                'status' => 'error',
                'message' => 'Invalid coupon code!',
            ], 422);
        }

        //? check if coupon quantity is greater than 0
        if ($coupon->quantity <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon has been fully redeemed!',
            ], 422);
        }

        //? check if coupon is expired
        if ($coupon->expire_date < now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon has expired!',
            ], 422);
        }

        if ($coupon->discount_type === 'percent') {
            //? calculate discont in percentage
            $discount = $subTotal * ($coupon->discount / 100);
        } elseif ($coupon->discount_type === 'amount') {
            //? calculate discont in amount
            $discount = $coupon->discount;
        }

        //? get the final total after applying the discount
        $finalTotal = $subTotal - $discount;

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon Applied Successfully!',
            'discount' => (int) $discount,
            'finalTotal' => $finalTotal,
        ], 200);
    }
}
