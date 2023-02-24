@extends('layouts.app')

@section('content')

<section class="inner-page-banner has_bg_image" data-background="assets/images/inner-page-bg.jpg" style="background-image: url('assets/images/inner-page-bg.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title">All USA Lotteries</h1>
                <h3 class="mt-5">UsLottery.com has everything you need to stay up to date on your favorite state lottery games!</h3>
                <p>In certain states, you can even play Powerball and Mega Millions right from your phone. Check the latest state lottery results for your state below:</p>
            </div>
        </div>
    </div>
</section>

<div class="container mb-5 mt-5">
    <div class="row">
        @foreach($lotteries as $key => $lotteries)
            <div class="col-md-4">
                <h3 class="mt-3" ><a href="{{route('lottery.state', ['state' => $key])}}">{{ $states[strtoupper($key)]  }} ({{strtoupper($key)}})</a></h3>

                <ul class="mt-2 ml-1">
                    @foreach($lotteries as $lottery)
                    <li class="pl-2 pb-2"><a href="{{route('lottery.show', ['lottery' => $lottery->id])}}">{{$lottery->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>


@endsection
