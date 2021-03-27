@extends('layouts.panel')
@section('content')
    <section class="content">
        <div class="row">
            @if($can["statistics_students"])
                <div class="col-md-4">
                    <div class="info-box bg-lightblue">
                    <span class="info-box-icon">
                    <i class="fa fa-user"></i>
                    </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Students Registered</span>
                            <span class="info-box-number">{{$statistics['students_number']}}</span>
                        </div>
                    </div>
                </div>
            @endif
            @if($can["statistics_societies"])
                <div class="col-md-4">
                    <div class="info-box bg-red">
                    <span class="info-box-icon">
                    <i class="fa fa-rss"></i>
                    </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Number of Societies</span>
                            <span class="info-box-number">{{$statistics['societies_number']}}</span>
                        </div>

                    </div>
                </div>
            @endif
            @if($can["statistics_internship_offers"])
                <div class="col-md-4">
                    <div class="info-box bg-green">
                <span class="info-box-icon">
                <i class="fa fa-shopping-cart"></i>
                </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Number of Internship Offers</span>
                            <span class="info-box-number">{{$statistics['internship_offers_number']}}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
