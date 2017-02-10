@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates occupation-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::occupation.occupation') }}</h1>
            </div>

            <div class="row">
                @foreach ($months as $i => $month)
                    <div class="month" @if ($i > 0)style="display:none"@endif data-month="{{ $i }}">
                        <?php $firstMonth = $month->calendars[0]->getMonths()[0] ?>
                        <h2><span class="previous" style="display: none"><<</span> {{ $month_labels[$firstMonth->getNumber()] }} {{ $firstMonth->getYear() }} <span class="next">>></span></h2>

                        <table>
                            <tr>
                                <th>Equipe</th>
                                @foreach ($firstMonth->getDays() as $day)
                                    @if ($day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SATURDAY && $day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SUNDAY)
                                        <th>{{ $day->getNumber() }}</th>
                                    @endif
                                @endforeach
                            </tr>
                            @foreach ($month->calendars as $calendar)
                                <tr>
                                    <td style="border:1px solid #999;">{{ $calendar->user->firstName }} {{ substr($calendar->user->lastName, 0, 1) }}.</td>
                                    @foreach ($calendar->getMonths()[0]->getDays() as $day)
                                        @if ($day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SATURDAY && $day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SUNDAY)
                                            <td style="text-align: center; font-size: 12px; border:1px solid #999; width: 50px; height: 50px; @if (sizeof($day->getEvents()) > 0)background: {{ $day->color }};@endif @if($day->getDateTime()->setTime(0, 0, 0) == $today)border: 2px solid #000; @endif">
                                                <span @if ($day->isDisabled())style="color:#ccc"@endif></span>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endforeach
            </div>

            <input type="hidden" id="months_index" value="0" />
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function displayMonth(index) {
            $('.month').hide();
            $('.month[data-month=' + index + ']').show();
            $('#months_index').val(index);

            $('.previous').show();
            $('.next').show();

            if (index == 0) $('.previous').hide();
            if (index == 5) $('.next').hide();
        }

        $(document).ready(function() {
            displayMonth(0);


            $('.previous').click(function() {
                var index = $('#months_index').val();
                if (index > 0) {
                    index--;
                    displayMonth(index);
                }
            });

            $('.next').click(function() {
                var index = $('#months_index').val();
                if (index < 5) {
                    index++;
                    displayMonth(index);
                }
            })
        });
    </script>
@endsection