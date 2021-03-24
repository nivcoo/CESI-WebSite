@extends('layouts.panel')
@section('content')
    thrt
    <pre>
    {!! var_dump(\Permission::can("ACCESS_PANEL")) !!}
    </pre>

@endsection
