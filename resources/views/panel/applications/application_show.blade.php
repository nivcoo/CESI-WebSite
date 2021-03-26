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

                        <div class="form-group">
                            <label>Content</label>
                            <div class="jumbotron">
                                {!! $get_application->content !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>CV</label><br>
                            <a target="_blank" href="{{asset('storage/'.$get_application->cv_path)}}"> Click to See</a>
                        </div>

                        <div class="form-group">
                            <label>Cover Letter</label><br>
                            <a target="_blank" href="{{asset('storage/'.$get_application->cover_letter_path)}}"> Click to See</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
