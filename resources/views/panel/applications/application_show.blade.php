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

                            <label>State : </label> {!! $get_application->state !!}


                        </div>
                        @if($can['change_state'])
                            <form action="{{ route('panel_applications_change_step') }}"
                                  data-redirect-url="{{ route('panel_applications_show', [$id]) }}" data-ajax="true"
                                  method="POST">
                                <div class="ajax-msg"></div>
                                <div class="form-group">

                                    <label>Change State : </label>
                                    <input type="hidden" value="{{$id}}" name="id">

                                    <select class="form-control" name="state">
                                        <option value="1"><b>State 1 : </b>Discussion between pilot and student</option>
                                        <option value="2"><b>State 2 : </b>An internship validation form has been issued
                                            by
                                            the company
                                        </option>
                                        <option value="3"><b>State 3 : </b>An internship validation form has been signed
                                            by
                                            the pilot
                                        </option>
                                        <option value="4"><b>State 4 : </b>The internship agreements have been issued to
                                            the
                                            company that an internship validation form has been signed by the pilot
                                        </option>
                                        <option value="5"><b>State 5 : </b>The internship agreements have been returned
                                            signed by the company
                                        </option>
                                    </select>


                                </div>
                                <button class="btn btn-sm btn-success" type="submit">Change State and inform user
                                </button>
                            </form>
                            <br>
                        @endif
                        <div class="form-group">
                            <label>CV</label><br>
                            <a class="btn btn-sm btn-secondary" target="_blank"
                               href="{{asset('storage/'.$get_application->cv_path)}}"> Click to See</a>
                        </div>

                        <div class="form-group">
                            <label>Cover Letter</label><br>
                            <a class="btn btn-sm btn-secondary" target="_blank"
                               href="{{asset('storage/'.$get_application->cover_letter_path)}}"> Click to See</a>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            @foreach($get_discussions as $v)

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header with-border">
                            <h3 class="card-title">Comment by {{$v->first_name}} {{$v->last_name}}</h3>
                        </div>
                        <div class="card-body">
                            @if(!empty($v->content))
                                <div class="form-group">
                                    <label>Content</label>
                                    <div class="jumbotron">
                                        {!! $v->content !!}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($v->file_path))
                                <div class="form-group">
                                    <label>File</label><br>

                                    <a class="btn btn-sm btn-secondary" target="_blank"
                                       href="{{asset('storage/'.$v->file_path)}}"> Click to See : {{$v->file_name}}</a>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            {{$v->created_at}}
                        </div>
                    </div>
                </div>
            @endforeach


            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header with-border">
                        <h3 class="card-title">Comment Form</h3>
                    </div>
                    <form action="{{ route('panel_applications_reply') }}"
                          data-redirect-url="{{ route('panel_applications_show', [$id]) }}" data-ajax="true"
                          method="POST"
                          data-upload-image="true">
                        <div class="card-body">
                            <div class="ajax-msg"></div>
                            <input type="hidden" name="id" value="{{$id}}">
                            <div class="form-group">
                                <label>Content (Optional)</label>
                                <textarea id="editor" name="content"></textarea>
                            </div>

                            <div class="form-group">
                                <label>File (Optional)</label>
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="cover_letter">
                                    <label class="custom-file-label" for="file">Select File</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>File Name (Optional if you haven't file)</label>
                                <input type="text" class="form-control" name="file_name">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
