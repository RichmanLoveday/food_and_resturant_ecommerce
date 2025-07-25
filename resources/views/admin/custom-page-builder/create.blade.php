@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Custom Page Builder</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Custom Page Builder</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.custom-page-builder.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Page Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="form-control summernote" cols="30" rows="10" style="resize: none"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </section>
@endsection
