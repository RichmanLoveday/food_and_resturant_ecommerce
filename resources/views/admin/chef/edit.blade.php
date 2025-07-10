@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Chef</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Chef</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.chef.update', $chef->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Image</label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="image" id="image-upload" />
                                <input type="hidden" name="old_image" id="" value="{{ $chef->image }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" value="{{ $chef->name }}" name="name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" value="{{ $chef->title }}" name="title" class="form-control">
                    </div>

                    <br>
                    <h5>Social Links</h5>
                    <div class="form-group">
                        <label>Facebook <code>(Leave empty for hide)</code> </label>
                        <input type="text" value="{{ $chef->fb }}" name="fb" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>LinkedIn <code>(Leave empty for hide)</code></label>
                        <input type="text" value="{{ $chef->in }}" name="in" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Web <code>(Leave empty for hide)</code></label>
                        <input type="text" value="{{ $chef->web }}" name="web" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>X <code>(Leave empty for hide)</code></label>
                        <input type="text" value="{{ $chef->x }}" name="x" class="form-control">
                    </div>


                    <div class="form-group">
                        <label>Show at home</label>
                        <select name="show_at_home" id="" class="form-control">
                            <option @selected(!$chef->show_at_home) value="0">No</option>
                            <option @selected($chef->show_at_home) value="1">Yes</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option @selected($chef->status) value="1">Active</option>
                            <option @selected(!$chef->status) value="0">Inactive</option>
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
                'background-image': 'url({{ asset($chef->image) }})',
                'background-size': 'cover',
                'background-position': 'center center'
            });
        });
    </script>
@endpush
