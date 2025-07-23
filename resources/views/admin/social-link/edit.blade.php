@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Social Link</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Social Link</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.social-link.update', $link->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Icon</label>
                        <br>
                        <button data-icon="{{ $link->icon }}" class="btn btn-primary" name="icon"
                            role="iconpicker"></button>
                    </div>

                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" value="{{ $link->name }}" class="form-control" name="name"
                            id="">
                    </div>

                    <div class="form-group">
                        <label for="">Link</label>
                        <input type="text" class="form-control" name="link" id=""
                            value="{{ $link->link }}">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option @selected($link->status) value="1">Active</option>
                            <option @selected(!$link->status) value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
