<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SectionTitle;
use App\Models\Slider;
use App\Models\WhyChooseUs;
use App\Traits\SectionTitlesTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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
}