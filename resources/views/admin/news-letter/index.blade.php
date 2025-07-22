@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>News Letters</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="accordion">
                    <div class="accordion">
                        <div class="accordion-header collapsed bg-primary text-white p-3" role="button" data-toggle="collapse"
                            data-target="#panel-body-1" aria-expanded="true">
                            <h4>Send News Letter</h4>
                        </div>
                        <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion" style="">
                            <form action="{{ route('admin.news-letter.send') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="">Subject</label>
                                    <input type="text" class="form-control" value="{{ @$titles['chefs_top_title'] }}"
                                        name="subject" id="">
                                </div>

                                <div class="form-group">
                                    <label for="">Sub Title</label>
                                    <textarea name="message" class="summernote"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        {{-- <div class="section-header">
            <h1>Chefs</h1>
        </div> --}}

        <div class="card card-primary">
            <div class="card-header">
                <h4>All Chefs</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.chef.create') }}" class="btn btn-primary">
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
