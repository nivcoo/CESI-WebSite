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
                        <form action="{{ route('panel_applications_participate', [$id]) }}"
                              data-redirect-url="{{ route('panel_applications') }}" data-ajax="true" method="POST"
                              data-upload-image="true">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label>Content</label>
                                <div class="jumbotron">
                                    {!! $get_internship_offer->content !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>CV</label>
                                <div class="custom-file">
                                    <input type="file" name="cv" class="custom-file-input" id="cv">
                                    <label class="custom-file-label" for="cv">Select CV</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Cover Letter</label>
                                <div class="custom-file">
                                    <input type="file" name="cover_letter" class="custom-file-input" id="cover_letter">
                                    <label class="custom-file-label" for="cover_letter">Select Cover Letter</label>
                                </div>
                            </div>

                            <div class="float-right">
                                <a href="{{ route('panel_offers') }}" class="btn btn-default">Cancel</a>
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
