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
                        <form action="{{ route('panel_offers_edit', [$id]) }}"
                              data-redirect-url="{{ route('panel_offers') }}" data-ajax="true" method="POST">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label>Society</label>
                                <select class="form-control" name="society_id">
                                    @foreach($get_societies as $value)
                                        <option {{($value->id == $internship_offer->society_id) ? "selected" : ""}} value="{{$value->id}}">{{$value->name}}
                                            | {{$value->address}}
                                            , {{$value->postal_code}} {{$value->city}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Content</label>
                                <textarea id="editor" name="content"> {{ $internship_offer->content }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Start Date</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend" data-target="#datetimepicker-start"
                                         data-toggle="datetimepicker">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input value="{{ $internship_offer->offer_start }}" data-target="#datetimepicker-start"
                                           data-toggle="datetimepicker" type="text" class="form-control"
                                           name="offer_start" id="datetimepicker-start"
                                           placeholder='Format : Year-Month-Day'>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>End Date</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend" data-target="#datetimepicker-end"
                                         data-toggle="datetimepicker">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input value="{{ $internship_offer->offer_end }}" data-target="#datetimepicker-end"
                                           data-toggle="datetimepicker" type="text" class="form-control"
                                           name="offer_end" id="datetimepicker-end"
                                           placeholder='Format : Year-Month-Day'>
                                </div>
                            </div>


                            <div class="form-group">
                                <label>Archived Date</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend" data-target="#datetimepicker-archived"
                                         data-toggle="datetimepicker">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input value="{{ $internship_offer->end_date }}" data-target="#datetimepicker-archived"
                                           data-toggle="datetimepicker" type="text" class="form-control"
                                           name="end_date" id="datetimepicker-archived"
                                           placeholder='Format : Year-Month-Day'>
                                </div>
                            </div>


                            <div class="float-right">
                                <a href="{{ route('panel_offers') }}" class="btn btn-default">Cancel</a>
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>


                        </form>

                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker-start').datetimepicker({
                                    locale: 'fr',
                                    format: 'YYYY-MM-DD'
                                });
                                $('#datetimepicker-end').datetimepicker({
                                    locale: 'fr',
                                    format: 'YYYY-MM-DD'
                                });
                                $('#datetimepicker-archived').datetimepicker({
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
