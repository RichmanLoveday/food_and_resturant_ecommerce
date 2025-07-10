@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Banner Slider</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Banner Slider</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banner-slider.store') }}" method="post" enctype="multipart/form-data">
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
                        <label>Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Sub Title</label>
                        <input type="text" name="sub_title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Url Link</label>
                        <input type="text" name="url" class="form-control">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#product_search').select2({
                placeholder: 'Search Product',
                ajax: {
                    url: "{{ route('admin.daily-offer.search-product') }}",
                    data: function(params) {
                        var query = {
                            search: params.term,
                            type: 'public'
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(product) {
                                return {
                                    id: product.id,
                                    text: product.name,
                                    image_url: product.thumb_image ?
                                        "{{ asset(':thumb_image') }}".replace(':thumb_image',
                                            product.thumb_image) : null,
                                };
                            })
                        };
                    },
                },
                minimumInputLength: 1,
                templateResult: formatProduct,
                escapeMarkupL: function(m) {
                    return m
                }
            });


            function formatProduct(product) {
                if (!product.id) {
                    return product.text; // Display the placeholder text
                }

                let $result = $(
                    `<div class="d-flex align-items-center">
                        <img src="${product.image_url}" class="img-fluid rounded-circle mr-2" style="width: 40px; height: 40px;">
                        <span>${product.text}</span>
                    </div>`
                );

                return $result;
            }
        });
    </script>
@endpush
