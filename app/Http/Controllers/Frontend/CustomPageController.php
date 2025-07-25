<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomPageBuilder;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $slug)
    {
        $page = CustomPageBuilder::where(['status' => true, 'slug' => $slug])
            ->firstOrFail();

        $breadCrumb = ['title' => $page->name, 'link' => '#'];
        return view('frontend.pages.custom-page', compact('page', 'breadCrumb'));
    }
}
