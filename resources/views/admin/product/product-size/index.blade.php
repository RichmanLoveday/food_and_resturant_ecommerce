@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Products Variants ({{ $product->name }})</h1>
        </div>

        <div class="py-3">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Go Back</a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Create Size</h4>
                    </div>
                    <div class="card-body">
                        {{-- {{ $dataTable->table() }} --}}
                        <form action="{{ route('admin.product-size.store') }}" method="POST" class="form-group"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" name="price" class="form-control">
                                    </div>
                                </div>
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-primary">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($sizes as $size)
                                    <tr>
                                        <td>{{ ++$loop->index }}</td>
                                        <td>{{ $size->name }}</td>
                                        <td>{{ currencyPosition($size->price) }}</td>
                                        <td>
                                            <a href="{{ route('admin.product-size.destroy', $size->id) }}"
                                                class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class=" text-center">No data found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Create Product Options</h4>
                    </div>
                    <div class="card-body">
                        {{-- {{ $dataTable->table() }} --}}
                        <form action="{{ route('admin.product-option.store') }}" method="POST" class="form-group"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" name="price" class="form-control">
                                    </div>
                                </div>
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-primary">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($options as $option)
                                    <tr>
                                        <td>{{ ++$loop->index }}</td>
                                        <td>{{ $option->name }}</td>
                                        <td>{{ currencyPosition($option->price) }}</td>
                                        <td>
                                            <a href="{{ route('admin.product-option.destroy', $option->id) }}"
                                                class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class=" text-center">No data found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
@endpush
