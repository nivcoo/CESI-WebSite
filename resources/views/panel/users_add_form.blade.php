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
                        <form action="{{ route('panel_users_add', [$type]) }}" data-redirect-url="{{ route('panel_users', [$type]) }}" data-ajax="true" method="POST">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input name="first_name" class="form-control" placeholder="First Name" type="text">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input name="last_name" class="form-control" placeholder="Last Name" type="text">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input name="email" class="form-control" placeholder="Email" type="email">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input name="password" class="form-control" placeholder="Password" type="password">
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <input disabled class="form-control" placeholder="{{$role->name}}" type="text">
                            </div>

                            <div class="form-group">
                                <label>Birth Date</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend" data-target="#datetimepicker"
                                         data-toggle="datetimepicker">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input value="<?= date('Y-m-d') ?>" data-target="#datetimepicker"
                                           data-toggle="datetimepicker" type="text" class="form-control"
                                           name="birth_date" id="datetimepicker"
                                           placeholder='Format : Year-Month-Day'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Center</label>
                                <select class="form-control" name="center_id">
                                    @foreach($centers as $value)
                                        <option value="{{$value->id}}">{{$value->address}}, {{$value->postal_code}} {{$value->city}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Promotion</label>
                                <select class="form-control" name="promotion_id">
                                    @foreach($promotions as $value)
                                        <option value="{{$value->id}}">{{$value->promotion}} - {{$value->speciality}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="float-right">
                                <a href="{{ route('panel_users', [$type]) }}" class="btn btn-default">Cancel</a>
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>

                        </form>

                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker').datetimepicker({
                                    locale: 'fr',
                                    format: 'YYYY-MM-DD'
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
