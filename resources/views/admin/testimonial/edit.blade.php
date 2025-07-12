@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Testimonials</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Testimonials</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.testimonial.update', $testimonial->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Image</label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="image" id="image-upload" />
                                <input type="hidden" name="old_path" value="{{ $testimonial->image }}" id="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ $testimonial->name }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" value="{{ $testimonial->title }}" name="title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Rating</label>
                        <select name="rating" id="" class="form-control">
                            <option @selected($testimonial->rating === 1) value="1">1</option>
                            <option @selected($testimonial->rating === 2) value="2">2</option>
                            <option @selected($testimonial->rating === 3) value="3">3</option>
                            <option @selected($testimonial->rating === 4) value="4">4</option>
                            <option @selected($testimonial->rating === 5) value="5">5</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Review</label>
                        <textarea name="review" class="form-control" id="" cols="30" rows="10">{{ $testimonial->review }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Show at home</label>
                        <select name="show_at_home" id="" class="form-control">
                            <option @selected(!$testimonial->show_at_home) value="0">No</option>
                            <option @selected($testimonial->show_at_home) value="1">Yes</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option @selected($testimonial->status) value="1">Active</option>
                            <option @selected(!$testimonial->status) value="0">Inactive</option>
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
                'background-image': 'url({{ asset($testimonial->image) }})',
                'background-size': 'cover',
                'background-position': 'center center'
            });
        });
    </script>
@endpush
