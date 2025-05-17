@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product Gallery ({{ $product->name }})</h1>
        </div>

        <div class="py-3">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Go Back</a>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>All Images</h4>
            </div>
            <div class="card-body">
                {{-- {{ $dataTable->table() }} --}}
                <div class="col-md-8">
                    <form action="{{ route('admin.product-gallery.store') }}" method="POST" class="form-group"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="image" class="form-control">
                        </div>
                        <input type="hidden" name="product_id" value="{{ $productId }}">
                        <div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($images as $image)
                            <tr>
                                <td><img class="my-2" style="width: 100px; height: 100px; object-fit: cover;"
                                        src="{{ asset($image->image) }}" alt=""></td>
                                <td>
                                    <a href="{{ route('admin.product-gallery.destroy', $image->id) }}"
                                        class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class=" text-center">No data found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
@endpush
