<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WhyChooseUsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhyChooseUsCreateRequest;
use App\Models\SectionTitle;
use App\Models\WhyChooseUs;
use App\Traits\SectionTitlesTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    use SectionTitlesTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(WhyChooseUsDataTable $dataTable)
    {
        $key = ['why_choose_us_top_title', 'why_choose_us_main_title', 'why_choose_us_sub_title'];
        $titles = $this->getSectionTitles($key);

        return $dataTable->render('admin.why-choose-us.index', compact('titles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.why-choose-us.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WhyChooseUsCreateRequest $request): RedirectResponse
    {
        // dd($request->validated());
        //? store data in whychoose us model
        WhyChooseUs::create($request->validated());

        //? flash message
        toastr()->success('Created Successfully');

        return redirect()->route('admin.why-choose-us.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $whyChooseUs = WhyChooseUs::findOrFail($id);
        return view('admin.why-choose-us.edit', compact('whyChooseUs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WhyChooseUsCreateRequest $request, string $id)
    {
        $whyChooseUs = WhyChooseUs::findOrFail($id);

        //? update why choose us data
        $whyChooseUs->update($request->validated());

        //? flash message
        toastr()->success('Updated Successfully');

        return redirect()->route('admin.why-choose-us.index');
    }


    public function updateTitle(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'why_choose_us_top_title' => 'max:255',
            'why_choose_us_main_title' => 'max:255',
            'why_choose_us_sub_title' => 'max:255',
        ]);

        //? update or create the section top title for why choose us
        SectionTitle::updateOrCreate(
            ['key' => 'why_choose_us_top_title'],
            ['value' => $request->why_choose_us_top_title]
        );

        //? update or create the section main title for why choose us
        SectionTitle::updateOrCreate(
            ['key' => 'why_choose_us_main_title'],
            ['value' => $request->why_choose_us_main_title]
        );

        //? update or create the section sub title for why choose us
        SectionTitle::updateOrCreate(
            ['key' => 'why_choose_us_sub_title'],
            ['value' => $request->why_choose_us_sub_title]
        );


        //? flash message
        toastr()->success('Updated successfully');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $whyChooseUs = WhyChooseUs::findOrFail($id);
            $whyChooseUs->delete($id);

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong'], 200);
        }
    }
}