@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Counter</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Counter</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.social-link.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Icon</label>
                        <br>
                        <button class="btn btn-primary" name="icon" role="iconpicker"></button>
                    </div>

                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" name="name" id="">
                    </div>

                    <div class="form-group">
                        <label for="">Link</label>
                        <input type="text" class="form-control" name="link" id="">
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
