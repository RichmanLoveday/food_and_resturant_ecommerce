@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product Gallery</h1>
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
    </section>
@endsection

@push('scripts')
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
@endpush
