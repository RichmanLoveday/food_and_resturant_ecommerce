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
                <form action="{{ route('admin.chef.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
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
                        <label>Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <br>
                    <h5>Social Links</h5>
                    <div class="form-group">
                        <label>Facebook <code>(Leave empty for hide)</code> </label>
                        <input type="text" name="fb" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>LinkedIn <code>(Leave empty for hide)</code></label>
                        <input type="text" name="in" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Web <code>(Leave empty for hide)</code></label>
                        <input type="text" name="web" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>X <code>(Leave empty for hide)</code></label>
                        <input type="text" name="x" class="form-control">
                    </div>


                    <div class="form-group">
                        <label>Show at home</label>
                        <select name="show_at_home" id="" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
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
