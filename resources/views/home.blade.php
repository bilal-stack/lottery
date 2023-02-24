@extends('layouts.app')

@section('content')


    <!-- banner-section start -->
    <section class="banner-section">
      <div class="banner-elements-part has_bg_image" data-background="assets/images/elements/vector-bg.jpg">
          <div class="element-one"><img src="assets/images/elements/box.png" alt="vector-image"></div>

          <div class="element-two"><img src="assets/images/elements/car.png" alt="vector-image"></div>

          <div class="element-three"><img src="assets/images/elements/chart.png" alt="vector-image"></div>

          <div class="element-four"><img src="assets/images/elements/dollars.png" alt="vector-image"></div>

          <div class="element-five"><img src="assets/images/elements/laptop.png" alt="vector-image"></div>

          <div class="element-six"><img src="assets/images/elements/money-2.png" alt="vector-image"></div>

          <div class="element-seven"><img src="assets/images/elements/person.png" alt="vector-image"></div>

          <div class="element-eight"><img src="assets/images/elements/person-2.png" alt="vector-image"></div>

          <div class="element-nine"><img src="assets/images/elements/power.png" alt="vector-image"></div>
      </div>
      <div class="banner-content-area">
          <div class="container">
              <div class="row">
                  <div class="col-md-6">
                      <div class="banner-content">
                          <h1 class="title">Take the chance to change your life</h1>
                          <p>Sorteo is online lottery platform inspired by few sorteo lover's fantasy of the ultimate lottery platfrom.</p>
                          <a href="#0" class="cmn-btn">Get Register Now !</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </section>
    <!-- banner-section end -->


    <!-- jackpot-section start -->
    <section class="jackpot-section section-padding">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5">
            <div class="section-header text-center">
              <h2 class="section-title">Lottery Jackpots</h2>
              <p>Choose from the Powerball, Mega Millions, Lotto or Lucky Day Lotto and try for a chance to win a big cash prize</p>
            </div>
          </div>
        </div>
        <div class="row">
          @foreach($lotteries as $lottery)
          <div class="mb-4 col-lg-4 col-md-6">
            <div class="jackpot-item text-center">
              <img style="width:50px" src="{{$lottery->image}}" alt="image">
              <span class="amount">{{ ucwords($lottery->country) }} - {{ ucwords($lottery->state) }}</span>
              <h5 class="title">{{ $lottery->name }}</h5>
              <a href="{{ route('lottery.show', ['lottery' => $lottery->id]) }}" class="cmn-btn">View!</a>
            </div>
          </div>
          <!-- jackpot-item end -->
          @endforeach
          <div class="col-lg-12 text-center">
            <a href="{{ route('lottery.index') }}" class="text-btn">Show all lotteries</a>
          </div>
        </div>
      </div>
    </section>
    <!-- jackpot-section start -->

    <!-- lottery-result-section start -->
    <section class="lottery-result-section section-padding has_bg_image" data-background="assets/images/bg-one.jpg">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="section-header text-center">
              <h2 class="section-title">Latest Lottery Results</h2>
              <p>Check your lotto results online, find all the lotto winning numbers and see if you won the latest lotto jackpots! </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-8">
            <div class="lottery-winning-num-part">
              <div class="lottery-winning-num-table">
                <h3 class="block-title">lottery winning numbers</h3>
                <div class="lottery-winning-table">
                  <table>
                    <thead>
                      <tr>
                        <th class="name">lottery</th>
                        <th class="date">draw date</th>
                        <th class="numbers">winning numbers</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div class="winner-details"><img src="assets/images/flag/1.jpg" alt="flag"><span class="winner-name">cancer charity</span></div></td>
                        <td><span class="winning-date">30/05/2018</span></td>
                        <td>
                          <ul class="number-list">
                            <li>19</li>
                            <li>31</li>
                            <li>21</li>
                            <li class="active">69</li>
                            <li>99</li>
                            <li>77</li>
                          </ul>
                        </td>
                      </tr>
                      <tr>
                        <td><div class="winner-details"><img src="assets/images/flag/2.jpg" alt="flag"><span class="winner-name">US Powerball</span></div></td>
                        <td><span class="winning-date">30/05/2018</span></td>
                        <td>
                          <ul class="number-list">
                            <li>19</li>
                            <li>31</li>
                            <li>21</li>
                            <li class="active">69</li>
                            <li>99</li>
                            <li class="active">77</li>
                            <li>65</li>
                          </ul>
                        </td>
                      </tr>
                      <tr>
                        <td><div class="winner-details"><img src="assets/images/flag/3.jpg" alt="flag"><span class="winner-name">Mega Millions</span></div></td>
                        <td><span class="winning-date">30/05/2018</span></td>
                        <td>
                          <ul class="number-list">
                            <li>19</li>
                            <li>31</li>
                            <li class="active">21</li>
                            <li class="active">69</li>
                            <li>99</li>
                            <li class="active">77</li>
                            <li>66</li>
                          </ul>
                        </td>
                      </tr>
                      <tr>
                        <td><div class="winner-details"><img src="assets/images/flag/1.jpg" alt="flag"><span class="winner-name">UK Lotto</span></div></td>
                        <td><span class="winning-date">30/05/2018</span></td>
                        <td>
                          <ul class="number-list">
                            <li>19</li>
                            <li>31</li>
                            <li>21</li>
                            <li class="active">69</li>
                            <li>99</li>
                            <li>77</li>
                          </ul>
                        </td>
                      </tr>
                      <tr>
                        <td><div class="winner-details"><img src="assets/images/flag/3.jpg" alt="flag"><span class="winner-name">Mega Millions</span></div></td>
                        <td><span class="winning-date">30/05/2018</span></td>
                        <td>
                          <ul class="number-list">
                            <li>19</li>
                            <li>31</li>
                            <li class="active">21</li>
                            <li class="active">69</li>
                            <li>99</li>
                            <li class="active">77</li>
                            <li>66</li>
                          </ul>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="winner-part">
              <h3 class="block-title">our winners</h3>
              <div class="winner-list">
                <div class="winner-single">
                  <div class="winner-header"><img src="assets/images/flag/1.jpg" alt="flag"><span class="name">vola pitmar</span></div>
                  <p><span class="lottery-name">Cancer Charity</span><span class="date">30/05/2018</span></p>
                  <h5 class="prize-amount">€500.00</h5>
                </div>
                <div class="winner-single">
                  <div class="winner-header"><img src="assets/images/flag/4.jpg" alt="flag"><span class="name">cay colon</span></div>
                  <p><span class="lottery-name">Powerball</span><span class="date">30/05/2018</span></p>
                  <h5 class="prize-amount">€340.00</h5>
                </div>
                <div class="winner-single">
                  <div class="winner-header"><img src="assets/images/flag/5.jpg" alt="flag"><span class="name">irez newtkon</span></div>
                  <p><span class="lottery-name">Powerball</span><span class="date">30/05/2018</span></p>
                  <h5 class="prize-amount">€130.00</h5>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 text-center">
            <a href="#" class="text-btn">see all result</a>
          </div>
        </div>
      </div>
    </section>
    <!-- lottery-result-section end -->


@endsection
