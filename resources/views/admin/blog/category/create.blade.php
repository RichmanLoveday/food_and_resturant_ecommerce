@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Blog Category</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Blog Category</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.blog-category.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control">
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
