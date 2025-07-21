@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Terms And Condition</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Update Terms And Condition</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.terms-and-condition.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="summernote" style="resize: none" id="" cols="30" rows="30">{{ @$termsandcondition->content }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
