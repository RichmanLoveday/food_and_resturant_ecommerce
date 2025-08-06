@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>About</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create About</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.about.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Image</label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="image" id="image-upload" />
                                <input type="hidden" name="old_image" value="{{ @$about->image }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="{{ @$about->title }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Main Title</label>
                        <input type="text" name="main_title" value="{{ @$about->main_title }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="summernote" style="resize: none" id="" cols="30" rows="30">{{ @$about->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Youtube Video Link</label>
                        <input type="url" name="video_link" value="{{ @$about->video_link }}" class="form-control">
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
            $('.image-preview').css({
                'background-image': 'url({{ asset(@$about->image) }})',
                'background-size': 'cover',
                'background-position': 'center center'
            });
        });
    </script>
@endpush
