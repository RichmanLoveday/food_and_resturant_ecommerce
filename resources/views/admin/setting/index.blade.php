@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Setting</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>All Settings</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-2">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#general-setting"
                                    role="tab" aria-controls="home" aria-selected="true">General Settings</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="home-tab4" data-toggle="tab" href="#pusher-setting" role="tab"
                                    aria-controls="home" aria-selected="true">Pusher Settings</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="home-tab4" data-toggle="tab" href="#mail-setting" role="tab"
                                    aria-controls="home" aria-selected="true">Mail Settings</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-10">
                        <div class="tab-content no-padding" id="myTab2Content">
                            @include('admin.setting.section.general-setting')
                            @include('admin.setting.section.pusher-setting')
                            @include('admin.setting.section.mail-setting')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
@endpush
