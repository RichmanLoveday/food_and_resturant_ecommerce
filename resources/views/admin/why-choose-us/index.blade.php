@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Why Choose Us</h1>
        </div>

        <div class="card">
            {{-- <div class="card-header">
                <h4>All Items</h4>
                <div class="card-header-action">

                </div>
            </div> --}}
            <div class="card-body">
                <div id="accordion">
                    <div class="accordion">
                        <div class="accordion-header collapsed bg-primary text-white p-3" role="button" data-toggle="collapse"
                            data-target="#panel-body-1" aria-expanded="true">
                            <h4>Why Choose Us Section Titles</h4>
                        </div>
                        <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion" style="">
                            <form action="{{ route('admin.why-choose-us.title.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="">Top Title</label>
                                    <input type="text" class="form-control"
                                        value="{{ @$titles['why_choose_us_top_title'] }}" name="why_choose_us_top_title"
                                        id="">
                                </div>

                                <div class="form-group">
                                    <label for="">Main Title</label>
                                    <input type="text" class="form-control"
                                        value="{{ @$titles['why_choose_us_main_title'] }}" name="why_choose_us_main_title"
                                        id="">
                                </div>

                                <div class="form-group">
                                    <label for="">Sub Title</label>
                                    <input type="text" class="form-control"
                                        value="{{ @$titles['why_choose_us_sub_title'] }}" name="why_choose_us_sub_title"
                                        id="">
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">

        <div class="card card-primary">
            <div class="card-header">
                <h4>All Items</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.why-choose-us.create') }}" class="btn btn-primary">
                        Create new
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
