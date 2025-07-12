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
                <form action="{{ route('admin.counter.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Background</label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="background" id="image-upload" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Counter icon one</label>
                        <br>
                        <button class="btn btn-primary" name="counter_icon_one" value="" role="iconpicker"></button>
                    </div>

                    <div class="form-group">
                        <label for="">Counter count one</label>
                        <input type="text" class="form-control" value="" name="counter_count_one" id="">
                    </div>

                    <div class="form-group">
                        <label for="">Counter one name</label>
                        <input type="text" class="form-control" value="" name="counter_name_one" id="">
                    </div>

                    <div class="form-group">
                        <label>Counter icon two</label>
                        <br>
                        <button class="btn btn-primary" name="counter_icon_two" value="" role="iconpicker"></button>
                    </div>

                    <div class="form-group">
                        <label for="">Counter count two</label>
                        <input type="text" class="form-control" value="" name="counter_count_two" id="">
                    </div>

                    <div class="form-group">
                        <label for="">Counter name two</label>
                        <input type="text" class="form-control" value="" name="counter_name_two" id="">
                    </div>

                    <div class="form-group">
                        <label>Counter icon three</label>
                        <br>
                        <button class="btn btn-primary" name="counter_icon_three" value="" role="iconpicker"></button>
                    </div>

                    <div class="form-group">
                        <label for="">Counter count three</label>
                        <input type="text" class="form-control" value="" name="counter_count_three" id="">
                    </div>

                    <div class="form-group">
                        <label for="">Counter name three</label>
                        <input type="text" class="form-control" value="" name="counter_name_three" id="">
                    </div>

                    <div class="form-group">
                        <label>Counter icon four</label>
                        <br>
                        <button class="btn btn-primary" name="counter_icon_four" value="" role="iconpicker"></button>
                    </div>

                    <div class="form-group">
                        <label for="">Counter count four</label>
                        <input type="text" class="form-control" value="" name="counter_count_four" id="">
                    </div>

                    <div class="form-group">
                        <label for="">Counter name four</label>
                        <input type="text" class="form-control" value="" name="counter_name_four" id="">
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </section>
@endsection
