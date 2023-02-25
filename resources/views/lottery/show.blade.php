@extends('layouts.app')

@section('content')

    <section style="padding-bottom:40px" class="inner-page-banner has_bg_image"
             style="background-image: url('/assets/images/inner-page-bg.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-page-banner-area">
                        <h1 class="page-title">{{ strtoupper($lottery->country) }} {{ $states[strtoupper($lottery->state)] }} {{$lottery->name}} Lottery</h1>
                            <nav aria-label="breadcrumb" class="page-header-breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('lottery.index') }}">All Lotteries</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('lottery.state', ['state' => $lottery->state]) }}">{{ $states[strtoupper($lottery->state)] }}</a></li>
                                </ol>
                            </nav>
                    </div>
                </div>

                <div class="row col-12 mt-3">
                    <div class="col-md-4">
                        <label for="">Select Game</label>
                        <select class="form-control" name="lottery_type" id="lottery_type">
                            @foreach ($all_state_lotteries as $type)
                                <option
                                    {{ $lottery->id == $type->id ? 'selected' : '' }}
                                    value="{{$type->id}}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="year">Select Year</label>
                        <select class="form-control" name="year" id="year">
                            @foreach($yearsHistory as $year)
                            <option {{ request("year") == $year ? 'selected' : '' }} value="{{$year}}">{{$year}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4  pt-3">
                        <button class="btn btn-success mt-3" type="button" id="filter">Filter</button>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <section style="padding-top: 40px" class="lottery-result-section section-padding has_bg_image"
             data-background="/assets/images/bg-one.jpg">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="lottery-winning-num-part">
                        <div class="lottery-winning-num-table">
                            <div class="lottery-winning-table">
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="name">Game</th>
                                        <th class="date">Draw Date</th>
                                        <th class="date">Jackpot</th>
                                        <th class="numbers">Winning Numbers</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($results as $line)
                                        <tr>
                                            <td>
                                                <div class="winner-details">
                                                    <span class="winner-name">{{ $lottery->name}} </span>
                                                </div>
                                            </td>
                                            <td><span class="winning-date">{{ $line->draw_date }}</span></td>
                                            <td>
                                                {{ number_format($line->jackpot, 0) }} USD
                                            </td>
                                            <td>
                                                <ul class="number-list">

                                                    @php
                                                        $balls = json_decode($line->balls);
                                                        if ($balls === FALSE) {
                                                            $balls = $line->balls;
                                                        }
                                                    @endphp
                                                    @foreach($balls as $key => $ball)
                                                        <li>{{ $ball }}</li>
                                                    @endforeach
                                                    @if($line->ball_bonus)
                                                        <li class="active">{{ $line->ball_bonus }}</li>
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(function () {
            $('#filter').on('click', function () {
                let base_url = "/lottery/show";
                let lottery_type = $('#lottery_type').val();
                let year = $('#year').val();

                window.location.href = `${base_url}/${lottery_type}?year=${year}`;

            });
        });
    </script>
@endsection
