<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SectionTitle;
use App\Models\Slider;
use App\Models\WhyChooseUs;
use App\Traits\SectionTitlesTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    use SectionTitlesTrait;

    public function index(): View
    {
        $sliders = Slider::where('status', 1)->orderBy('id', 'desc')->get();
        $sectionTitles = $this->getSectionTitles($this->getSectionKeys());
        $whyChooseUs = WhyChooseUs::where('status', 1)->get();

        return view('frontend.home.index', compact('sliders', 'sectionTitles', 'whyChooseUs'));
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
}