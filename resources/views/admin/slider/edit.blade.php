@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Slider</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Slider</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.slider.update', $slider->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Image</label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="image" id="image-upload" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Offer</label>
                        <input type="text" value="{{ $slider->offer }}" name="offer" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" value="{{ $slider->title }}" name="title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Sub Title</label>
                        <input type="text" name="sub_title" value="{{ $slider->sub_title }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-control" style="resize: none" id="" cols="30"
                            rows="30">{{ $slider->short_description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Button Link</label>
                        <input type="url" name="button_link" value="{{ $slider->button_link }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option @selected($slider->status === 1) value="1">Active</option>
                            <option @selected($slider->status === 0) value="0">Inactive</option>
                        </select>
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
                'background-image': 'url({{ asset($slider->image) }})',
                'background-size': 'cover',
                'background-position': 'center center'
            });
        });
    </script>
@endpush
