@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Product</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
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
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" id="" class="form-control select2">
                            <option value="">---select category---</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Offer Price</label>
                        <input type="number" name="offer_price" value="{{ old('offer_price') }}" class="form-control"
                            step="0.01">
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-control" style="resize: none" id="" cols="30"
                            rows="30">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Long Description</label>
                        <textarea name="long_description" class="form-control summernote" style="resize: none" id="" cols="30"
                            rows="30">{{ old('long_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Sku</label>
                        <input type="text" value="{{ old('sku') }}" name="sku" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Seo Title</label>
                        <input type="text" value="{{ old('seo_title') }}" name="seo_title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Seo Description</label>
                        <textarea name="seo_description" class="form-control" style="resize: none" id="" cols="30" rows="30">{{ old('seo_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Show at home</label>
                        <select name="show_at_home" id="" class="form-control">
                            <option value="1">Yes</option>
                            <option selected value="0">No</option>
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
