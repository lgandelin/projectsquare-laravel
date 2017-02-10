@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates occupation-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::occupation.occupation') }}</h1>
            </div>

            <div class="row">
                @foreach ($months as $i => $month)
                    <div class="month" @if ($i > 0)style="display:none"@endif>
                        <?php $firstMonth = $month->calendars[0]->getMonths()[0] ?>
                        <h2>{{ $month_labels[$firstMonth->getNumber()] }} {{ $firstMonth->getYear() }}</h2>
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
                                            <td style="text-align: center; font-size: 12px; border:1px solid #999; width: 35px; height: 35px; @if (sizeof($day->getEvents()) > 0)background: {{ $day->color }};@endif @if($day->getDateTime()->setTime(0, 0, 0) == $today)border: 2px solid #000; @endif">
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
        </div>
    </div>
@endsection