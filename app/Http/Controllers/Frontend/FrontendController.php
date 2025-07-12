<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AppDownloadSection;
use App\Models\BannerSlider;
use App\Models\Category;
use App\Models\Chefs;
use App\Models\Counter;
use App\Models\Coupon;
use App\Models\DailyOffer;
use App\Models\Product;
use App\Models\SectionTitle;
use App\Models\Slider;
use App\Models\Testimonial;
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

        $dailyOffers = DailyOffer::with('product')
            ->where('status', true)
            ->take(10)
            ->get();

        $bannerSlider = BannerSlider::where('status', true)
            ->latest()
            ->take(10)
            ->get();

        $chefs = Chefs::where(['show_at_home' => true, 'status' => true])
            ->orderBy('id', 'desc')
            ->get();

        $testimonials = Testimonial::where(['show_at_home' => true, 'status' => true])
            ->orderBy('id', 'desc')
            ->get();

        $counter = Counter::first();

        $appSection = AppDownloadSection::first();

        //? get menu items
        $menuItems = $this->menuItems($categories);
        // dd($menuItems);


        return view('frontend.home.index', compact(
            'sliders',
            'sectionTitles',
            'whyChooseUs',
            'categories',
            'menuItems',
            'dailyOffers',
            'bannerSlider',
            'chefs',
            'appSection',
            'testimonials',
            'counter'
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


    public function testimonial(): View
    {
        $testimonials = Testimonial::where(['status' => 1])->paginate(1);
        $breadCrumb = ['title' => 'our customers feedbacks', 'link' => '#'];


        return view('frontend.pages.testimonial', compact('testimonials', 'breadCrumb'));
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
            'why_choose_us_sub_title',
            'daily_offer_top_title',
            'daily_offer_main_title',
            'daily_offer_sub_title',
            'chefs_top_title',
            'chefs_main_title',
            'chefs_sub_title',
            'testimonial_top_title',
            'testimonial_main_title',
            'testimonial_sub_title',
        ];
    }


    public function chef(): View
    {
        $breadCrumb = ['title' => 'meet our expert chefs', 'link' => '#'];
        $chefs = Chefs::where(['status' => true])
            ->paginate(8);

        return view('frontend.pages.chefs', compact(
            'breadCrumb',
            'chefs'
        ));
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
        // dd($request->all());
        //? validate the request
        $request->validate([
            'code' => 'required|string|max:255',
            'subTotal' => 'required|numeric|min:0',
        ]);

        //? check if coupon exist in session
        if (session()->has('coupon')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon already applied!',
            ], 422);
        }

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
            //? calculate discount in percentage and round to 2 decimal places
            $discount = number_format(($subTotal * $coupon->discount) / 100, 2, '.', '');
        } elseif ($coupon->discount_type === 'amount') {
            //? calculate discont in amount
            $discount = number_format($coupon->discount, 2, '.', '');
        }

        //? get the final total after applying the discount
        $finalTotal = $subTotal - $discount;

        //? store discount and code in session
        session()->put('coupon', [
            'code' => $code,
            'discount_type' => $coupon->discount_type,
            'discount' => $discount,
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Coupon Applied Successfully!',
            'coupon_code' => $code,
            'discount' => $discount,
            'finalTotal' => $finalTotal,
        ], 200);
    }


    public function destroyCoupon(): JsonResponse
    {
        try {
            //? check if coupon exist in session
            if (session()->has('coupon')) {
                //? remove coupon from session
                session()->forget('coupon');

                return response()->json([
                    'status' => 'success',
                    'discount' => 0,
                    'grandTotal' => grandCartTotal(),
                    'subTotal' => cartTotal(),
                    'message' => 'Coupon removed successfully!',
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'No coupon applied!',
            ], 422);
        } catch (\Exception $e) {
            logger('Unable to remove coupon: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while removing the coupon.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}