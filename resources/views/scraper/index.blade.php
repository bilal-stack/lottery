@extends('layouts.app')

@section('content')


<section class="lottery-result-section section-padding has_bg_image" data-background="assets/images/bg-one.jpg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header text-center">
                    <h2 class="section-title">Lottery Scraper</h2>
                    <p><a href="{{route('scraper.scrape.usa.lotteries')}}" class="btn btn-primary">Scrape USA Lotteries</a></p>
                    <p><a href="{{route('scraper.scrape.usa.lottery.numbers')}}" class="btn btn-info">Scrape USA Lottery Numbers</a></p>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
