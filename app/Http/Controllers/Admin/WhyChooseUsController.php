<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WhyChooseUsDataTable;
use App\Http\Controllers\Controller;
use App\Models\SectionTitle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(WhyChooseUsDataTable $dataTable)
    {
        $key = ['why_choose_us_top_title', 'why_choose_us_main_title', 'why_choose_us_sub_title'];
        $titles = SectionTitle::whereIn('key', $key)->pluck('value', 'key'); //? pluck('value', 'key') will return an associative array with key as the key and value as the value

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
        //
    }
}