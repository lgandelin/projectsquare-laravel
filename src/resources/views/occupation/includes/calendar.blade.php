<div class="loading"></div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        @foreach ($calendars as $i => $month)
            <div class="month" @if ($i > 0)style="display:none"@endif data-month="{{ $i }}">
                @if (isset($month->calendars[0]))
                    <div class="header">
                        <?php $firstMonth = $month->calendars[0]->getMonths()[0] ?>
                        <h2>
                            <button type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left previous"><span class="fc-icon fc-icon-left-single-arrow"></span></button>
                            {{ $month_labels[$firstMonth->getNumber()] }} {{ $firstMonth->getYear() }}
                            <button type="button" class="fc-next-button fc-button fc-state-default fc-corner-right next"><span class="fc-icon fc-icon-right-single-arrow"></span></button>
                        </h2>
                    </div>
                    <table>
                        <tr class="weeks-row">
                            <th style="border:none">&nbsp;</th>
                            @foreach ($month->weeks as $weekNumber)
                                <th colspan="5" class="week">Semaine {{ $weekNumber }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th style="border:none">&nbsp;</th>
                            <?php $index = 0; ?>
                            @foreach ($firstMonth->getDays() as $day)
                                @if ($day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SATURDAY && $day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SUNDAY)
                                    <?php $index++; ?>
                                    <th class="day @if($day->getDateTime()->setTime(0, 0, 0) == $today) today @endif @if($day->isDisabled()) disabled @endif" @if($index%5 == 0)style="border-right-width: 2px"@endif>{{ $day->getNumber() }}</th>
                                @endif
                            @endforeach
                        </tr>
                        @foreach ($month->calendars as $calendar)
                            <tr>
                                <td class="user">
                                    @include('projectsquare::includes.avatar', [
                                        'id' => $calendar->user->id,
                                        'name' => $calendar->user->complete_name
                                    ])
                                </td>
                                <?php $index = 0; ?>
                                @foreach ($calendar->getMonths()[0]->getDays() as $i => $day)
                                    @if ($day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SATURDAY && $day->getDayOfWeek() != Webaccess\ProjectSquareLaravel\Tools\Calendar\Day::SUNDAY)
                                        <?php $index++; ?>
                                        <td class="user-day @if($day->isDisabled()) disabled @endif" data-user="{{ $calendar->user->id }}" data-day="{{ $day->getDateTime()->format('Y-m-d') }}" @if ($day->isDisabled())class="disabled"@endif @if($index%5 == 0)style="border-right-width: 2px"@endif>
                                            <span class="work-hours" style="height:{{ $day->hours_scheduled*7-2 }}px;"></span>

                                            @if ($day->getEvents())
                                                <div class="events-detail">
                                                    @foreach ($day->getEvents() as $event)
                                                        <div class="event" style="background: {{ isset($event->color) ? $event->color : '#3a87ad' }}">
                                                            <span class="name">{{ $event->name }}</span>
                                                            @if ($event->endTime->diff($event->startTime)->d < 1 && $event->endTime->format('H:i') != '00:00')
                                                                <span class="time">{{ $event->startTime->format('H:i') }} - {{ $event->endTime->format('H:i') }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        @endforeach
    </div>

    <input type="hidden" id="months_index" value="0" />
    <input type="hidden" id="months_number" value="{{ sizeof($calendars) }}" />
</div>