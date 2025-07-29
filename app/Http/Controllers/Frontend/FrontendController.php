<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\About;
use App\Models\AppDownloadSection;
use App\Models\BannerSlider;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\Category;
use App\Models\Chefs;
use App\Models\Contact;
use App\Models\Counter;
use App\Models\Coupon;
use App\Models\DailyOffer;
use App\Models\PrivacyPolicy;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\Reservation;
use App\Models\SectionTitle;
use App\Models\Slider;
use App\Models\Subscriber;
use App\Models\TermsAndCondition;
use App\Models\Testimonial;
use App\Models\WhyChooseUs;
use App\Traits\SectionTitlesTrait;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Mail;

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

        $latestBlogs = Blog::withCount(['comments' => function ($query) {
            $query->where('status', true);
        }])
            ->with(['category', 'user',])
            ->where('status', 1)->latest()->take(3)->get();

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
            'counter',
            'latestBlogs'
        ));
    }


    public function showProduct(string|int $slug): View
    {
        $product = Product::with(['category', 'productImages', 'productSizes', 'productOptions'])
            ->where(['slug' => $slug, 'status' => true])
            ->firstOrFail();

        $reviews = ProductRating::where(['product_id' => $product->id, 'status' => 1])
            ->paginate(1);

        // dd($reviews);

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
            'relatedProducts',
            'reviews',
        ));
    }

    public function loadMoreReviews(string|int $productId): View|JsonResponse
    {
        try {
            // dd($productId);
            $reviews = ProductRating::where(['product_id' => $productId, 'status' => 1])
                ->paginate(1);

            // dd($reviews);
            return view('frontend.layout.ajax-files.reviews', compact('reviews'));
        } catch (\Exception $e) {
            logger('Error loading more comments: ' . $e->getMessage());
            return response()->json([
                'message' => 'Unable to load comments at this time.',
            ], 500);
        }
    }


    public function testimonial(): View
    {
        $testimonials = Testimonial::where(['status' => 1])->paginate(1);
        $breadCrumb = ['title' => 'our customers feedbacks', 'link' => '#'];

        return view('frontend.pages.testimonial', compact('testimonials', 'breadCrumb'));
    }


    public function about()
    {
        //? use this keys to search for sections in database
        $aboutUsSectionTitles = [
            'why_choose_us_top_title',
            'why_choose_us_main_title',
            'why_choose_us_sub_title',
            'chefs_top_title',
            'chefs_main_title',
            'chefs_sub_title',
            'testimonial_top_title',
            'testimonial_main_title',
            'testimonial_sub_title',
        ];

        $sectionTitles = $this->getSectionTitles($aboutUsSectionTitles);
        $breadCrumb = ['title' => 'about unifood', 'link' => '#'];
        $about = About::first();
        $whyChooseUs = WhyChooseUs::where('status', 1)->get();
        $chefs = Chefs::where(['show_at_home' => true, 'status' => true])->orderBy('id', 'desc')->get();
        $testimonials = Testimonial::where(['show_at_home' => true, 'status' => true])
            ->orderBy('id', 'desc')->get();
        $counter = Counter::first();

        return view('frontend.pages.about', compact(
            'breadCrumb',
            'sectionTitles',
            'about',
            'whyChooseUs',
            'chefs',
            'testimonials',
            'counter'
        ));
    }

    public function privacyPolicy(): View
    {
        $breadCrumb = ['title' => 'privacy policy', 'link' => '#'];
        $privacyPolicy = PrivacyPolicy::first();

        return view('frontend.pages.privacy-policy', compact('breadCrumb', 'privacyPolicy'));
    }


    public function termsAndCondition(): View
    {
        $breadCrumb = ['title' => 'terms and condition', 'link' => '#'];
        $termsAndCondition = TermsAndCondition::first();

        return view('frontend.pages.terms-and-condition', compact('breadCrumb', 'termsAndCondition'));
    }

    public function contact(): View
    {
        $breadCrumb = ['title' => 'contact with us', 'link' => '#'];
        $contact = Contact::first();

        return view(
            'frontend.pages.contact',
            compact('breadCrumb', 'contact')
        );
    }

    public function sendContactMessage(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'max:255'],
            'message' => ['required', 'max:1000'],
        ]);

        Mail::send(new ContactMail(
            $request->name,
            $request->subject,
            $request->message,
            $request->email,
        ));

        return response()->json([
            'status' => 'success',
            'message' => 'Email Sent Successfully!'
        ]);
    }


    public function reservation(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => ['required', 'max:255'],
            'phone' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'persons' => ['required', 'numeric'],
        ]);

        //? check if user is logged in to access this method
        if (!Auth::check()) {
            throw ValidationException::withMessages(['Please Login to Request for Reservation']);
        }

        $reservation = new Reservation();
        $reservation->user_id = Auth::user()->id;
        $reservation->reservation_id = rand(0, 5000000);
        $reservation->name = $request->name;
        $reservation->phone = $request->name;
        $reservation->date = $request->date;
        $reservation->time = $request->time;
        $reservation->persons = $request->persons;
        $reservation->status = 'pending';
        $reservation->save();

        return response([
            'status' => 'success',
            'message' => 'Request sent successfully'
        ]);
    }


    public function subscribeNewsLetter(Request $request): Response|JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:subscribers,email'],
        ], ['email.unique' => "Email is already subscribed!"]);

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        return response()->json([
            'status' => 'success',
            'message' => "Subscribed Successfully!",
        ]);
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


    public function blog(Request $request): View
    {
        $breadCrumb = ['title' => 'our latest food blogs', 'link' => '#'];
        $blogs = Blog::withCount(['comments' => function ($query) {
            $query->where('status', true);
        }])->with(['category', 'user'])->where(['status' => true]);

        //? handle parameter based on search result
        if ($request->has('search') && $request->filled('search')) {
            $blogs->where(function ($query) use ($request) {
                $query->where('title', 'like', "% {$request->search} %")
                    ->orWhere('description', 'like', "% {$request->search} %");
            });
        }

        //? handle parameter based on category
        if ($request->has('category') && $request->filled('category')) {
            $blogs->where(function ($query) use ($request) {
                $query->where('title', 'like', "% {$request->search} %")
                    ->orWhereHas('category', function ($query) use ($request) {
                        $query->where('slug', $request->category);
                    });
            });
        }

        //? commplete query
        $blogs = $blogs->latest()->paginate(1)->withQueryString();
        // dd($blogs->toArray());

        $categories = BlogCategory::where('status', true)->get();

        return view('frontend.pages.blogs', compact('blogs', 'breadCrumb', 'categories'));
    }


    public function blogDetails(Request $request, string $slug): View
    {
        $breadCrumb = ['title' => 'blog details', 'link' => '#'];
        $categories = BlogCategory::withCount(['blogs' => function ($query) {
            $query->where('status', true);
        }])
            ->where('status', true)
            ->get();

        // dd($categories->toArray());

        $blog = Blog::with(['user', 'category'])
            ->where(['slug' => $slug, 'status' => true])
            ->firstOrFail();

        $comments = $blog->comments()->with(['user'])
            ->where('status', true)
            ->orderBy('id', 'DESC')
            ->paginate(1);

        // dd($comments);

        $latestBlogs = Blog::select('id', 'title', 'slug', 'image', 'created_at')
            ->where('status', true)
            ->where('id', '!=', $blog->id)
            ->take(5)
            ->get();

        $nextBlog = Blog::select('slug', 'image', 'title')
            ->where('id', '>', $blog->id)
            ->where('status', true)
            ->orderBy('id', 'ASC')
            ->first();

        $prevBlog = Blog::select('slug', 'image', 'title')
            ->where('id', '<', $blog->id)
            ->where('status', true)
            ->orderBy('id', 'DESC')
            ->first();



        return view('frontend.pages.blog-details', compact(
            'blog',
            'latestBlogs',
            'categories',
            'breadCrumb',
            'nextBlog',
            'prevBlog',
            'comments',
        ));
    }


    public function loadMoreComments(string|int $blogId): View|JsonResponse
    {
        try {
            // dd($blogId);
            $comments = BlogComment::with(['user', 'blog'])
                ->where(['blog_id' => $blogId, 'status' => true])
                ->orderBy('id', 'DESC')
                ->paginate(1);

            return view('frontend.layout.ajax-files.comments', compact('comments'));
        } catch (\Exception $e) {
            logger('Error loading more comments: ' . $e->getMessage());
            return response()->json([
                'message' => 'Unable to load comments at this time.',
            ], 500);
        }
    }


    public function blogCommentStore(Request $request, string|int $blogId): RedirectResponse
    {
        $request->validate([
            'comment' => ['required', 'max:500'],
        ]);

        //? check if blog is exist
        Blog::findOrFail($blogId);

        $comment = new BlogComment();
        $comment->blog_id = $blogId;
        $comment->user_id = Auth::user()->id;
        $comment->comment = $request->comment;
        $comment->save();

        toastr()->success('Comment submitted successfully and waiting to be approved.');

        return redirect()->back();
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


    public function productReviewStore(Request $request)
    {
        $request->validate([
            'rating' => ['required', 'min:1', 'max:5', 'integer'],
            'review' => ['required', 'max:500'],
            'product_id' => ['required', 'integer'],
        ]);


        //? check if user is logedin
        if (!Auth::check()) {
            throw ValidationException::withMessages([
                'Please login first to add a review'
            ]);
        }

        //? check if user has purchased product
        $user = Auth::user();
        $hasPurchased = $user->orders()
            ->where('order_status', 'delivered')
            ->whereHas(
                "orderItems",
                function ($query) use ($request) {
                    $query->where('product_id', $request->product_id);
                }
            )
            ->exists();

        //? throw validation exception if purched of product is not found
        if (!$hasPurchased) {
            throw ValidationException::withMessages([
                'Please buy the product before submitting a review'
            ]);
        }

        //? check if product is already reviewed
        $alreadyReviewed = ProductRating::where([
            'user_id' => $user->id,
            'product_id' => $request->product_id
        ])
            ->exists();

        if ($alreadyReviewed) {
            throw ValidationException::withMessages([
                'You already reviewed this product'
            ]);
        }

        //? add a new review
        $review = new ProductRating();
        $review->user_id = $user->id;
        $review->product_id = $request->product_id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->status = 0;
        $review->save();

        toastr()->success('Review added successfully and waiting to be approved');

        return redirect()->back();
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