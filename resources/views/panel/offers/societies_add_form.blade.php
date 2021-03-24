@extends('layouts.panel')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">{{$title}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panel_societies_add') }}"
                              data-redirect-url="{{ route('panel_societies') }}" data-ajax="true" method="POST">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" class="form-control" placeholder="Name" type="text">
                            </div>
                            <div class="form-group">
                                <label>Activity Field</label>
                                <input name="activity_field" class="form-control" placeholder="Activity Field" type="text">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input name="address" class="form-control" placeholder="Address" type="text">
                            </div>
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input name="postal_code" class="form-control" placeholder="Postal Code" type="number">
                            </div>

                            <div class="form-group">
                                <label>City</label>
                                <input name="city" class="form-control" placeholder="City" type="text">
                            </div>

                            <div class="form-group">
                                <label>Cesi Interns</label>
                                <input name="cesi_interns" class="form-control" placeholder="Cesi Interns" type="number">
                            </div>

                            <div class="form-group">
                                <label>Evaluation</label>
                                <input name="evaluation" class="form-control" placeholder="Evaluation" type="number">
                            </div>


                            <div class="float-right">
                                <a href="{{ route('panel_societies') }}" class="btn btn-default">Cancel</a>
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
