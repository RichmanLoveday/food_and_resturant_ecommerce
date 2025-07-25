@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Custom Page Builder</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Custom Page Builder</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.custom-page-builder.update', $page->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Page Name</label>
                        <input type="text" name="name" value="{{ $page->name }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="form-control summernote" cols="30" rows="10" style="resize: none">{{ $page->content }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option @selected($page->status) value="1">Active</option>
                            <option @selected(!$page->status) value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
