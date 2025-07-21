@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Reservation Time</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Reservation Time</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.reservation-time.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Start Time</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <input type="text" name="start_time" class="form-control timepicker">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>End Time</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <input type="text" name="end_time" class="form-control timepicker">
                        </div>
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
