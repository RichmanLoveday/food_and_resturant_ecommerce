@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Blog</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Blog</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.blogs.update', $blog->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Image</label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="image" id="image-upload" />
                                <input type="hidden" name="old_image" value="{{ $blog->image }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $blog->title }}">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" id="" class="form-control select2">
                            <option value="">---select category---</option>
                            @foreach ($blogCategories as $category)
                                <option @selected($category->id === $blog->category->id) value="{{ $category->id }}">
                                    {{ $blog->category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Long Description</label>
                        <textarea name="description" class="form-control summernote" style="resize: none" id="" cols="30"
                            rows="30">{{ $blog->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Seo Title</label>
                        <input type="text" value="{{ $blog->seo_title }}" name="seo_title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Seo Description</label>
                        <textarea name="seo_description" class="form-control" style="resize: none" id="" cols="30" rows="30">{{ $blog->seo_description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option @selected($blog->status) value="1">Active</option>
                            <option @selected(!$blog->status) value="0">Inactive</option>
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
                'background-image': 'url({{ asset($blog->image) }})',
                'background-size': 'cover',
                'background-position': 'center center'
            });
        });
    </script>
@endpush
