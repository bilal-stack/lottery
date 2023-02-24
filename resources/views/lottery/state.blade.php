@extends('layouts.app')

@section('content')

<section class="inner-page-banner has_bg_image" data-background="assets/images/inner-page-bg.jpg" style="background-image: url('assets/images/inner-page-bg.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="inner-page-banner-area">
                    <h1 class="page-title">{{$state->name}} Lotteries</h1>
                    <nav aria-label="breadcrumb" class="page-header-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('lottery.index') }}">All Lotteries</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container mb-5 mt-5">

    <div class="row">

            <div class="col-md-4">
                <h3 class="mt-3" ><a href="{{route('lottery.state', ['state' => $state->code])}}">{{ $state->name }} ({{strtoupper($state->code)}})</a></h3>

                <ul class="mt-2 ml-1">
                    @foreach($lotteries as $lottery)
                    <li class="pl-2 pb-2"><a href="{{route('lottery.show', ['lottery' => $lottery->id])}}">{{$lottery->name}}</a></li>
                    @endforeach
                </ul>
            </div>

    </div>
</div>


@endsection
