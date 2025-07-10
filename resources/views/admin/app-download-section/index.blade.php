@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Update Section</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update App Download Section</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.app-download.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Image</label>
                                <div class="col-sm-12 col-md-7">
                                    <div id="image-preview" class="image-preview image-preview-1">
                                        <label for="image-upload" id="image-label">Choose File</label>
                                        <input type="file" name="image" id="image-upload" />
                                        <input type="hidden" name="old_image" id=""
                                            value="{{ @$appDownloadSection->image }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Background</label>
                                <div class="col-sm-12 col-md-7">
                                    <div id="image-preview2" class="image-preview image-preview-2">
                                        <label for="image-upload" id="image-label">Choose File</label>
                                        <input type="file" name="background" id="image-upload2" />
                                        <input type="hidden" name="old_background" id=""
                                            value="{{ @$appDownloadSection->background }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="{{ @$appDownloadSection->title }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="short_description" class="form-control" id="" cols="30" rows="10">{{ @$appDownloadSection->short_description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Apple Store Link <code>(Leave empty for hide!)</code></label>
                                <input type="text" value="{{ @$appDownloadSection->apple_store_link }}"
                                    name="apple_store_link" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Play Store Link <code>(Leave empty for hide!)</code></label>
                                <input type="text" value="{{ @$appDownloadSection->play_store_link }}"
                                    name="play_store_link" class="form-control">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.uploadPreview({
                input_field: "#image-upload", // Default: .image-upload
                preview_box: "#image-preview", // Default: .image-preview
                label_field: "#image-label", // Default: .image-label
                label_default: "Choose File", // Default: Choose File
                label_selected: "Change File", // Default: Change File
                no_label: false, // Default: false
                success_callback: null // Default: null
            });

            $.uploadPreview({
                input_field: "#image-upload2",
                preview_box: "#image-preview2",
                label_field: "#image-label2",
                label_default: "Choose Background",
                label_selected: "Change Background",
                no_label: false,
                success_callback: null
            });


            $('.image-preview-1').css({
                'background-image': 'url({{ asset($appDownloadSection->image) }})',
                'background-size': 'cover',
                'background-position': 'center center'
            });

            $('.image-preview-2').css({
                'background-image': 'url({{ asset($appDownloadSection->background) }})',
                'background-size': 'cover',
                'background-position': 'center center'
            });
        });
    </script>
@endpush
