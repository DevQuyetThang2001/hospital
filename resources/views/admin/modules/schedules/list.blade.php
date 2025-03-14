@extends('admin.layouts.app')

@section('content')

    {{--
    @foreach ($weeklySchedules as $day => $doctors)
    <h3>{{ $day }}</h3>
    <ul>
        @foreach ($doctors as $doctor)
        @php
        $doctorSchedules = collect($doctor->schedule)->unique(function ($item) {
        return $item->start_time . '-' . $item->end_time;
        });
        @endphp

        @foreach ($doctorSchedules as $schedule)
        <li>
            <strong>{{ $schedule->start_time }} - {{ $schedule->end_time }}:</strong>
            {{ $doctor->user->name }}
        </li>
        @endforeach
        @endforeach
    </ul>
    @endforeach --}}
    <div class="w-95 w-md-75 w-lg-60 w-xl-55 mx-auto mb-6 text-center">
        <div class="subtitle alt-font">
            <span class="text-primary"></span><span class="title">Hệ thống bệnh viện</span>
        </div>
        <h2 class="display-18 display-md-16 display-lg-14 mb-0">Lịch làm việc</h2>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="schedule-table">
                <table class="table bg-white">
                    <thead>
                        <tr>
                            <th>Giờ khám</th>
                            @foreach ($timeSlots as $time)
                                <th>{{ $time }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($weeklySchedules as $day => $scheduleByTime)
                                            <tr>
                                                <td class="day">
                                                    <?php
                            if ($day === 'Monday') {
                                echo 'Thứ 2';
                            }
                            if ($day === 'Tuesday') {
                                echo "Thứ 3";
                            }
                            if ($day === 'Wednesday') {
                                echo "Thứ 4";
                            }
                            if ($day === 'Thursday') {
                                echo "Thứ 5";
                            }
                            if ($day === 'Friday') {
                                echo "Thứ 6";
                            }

                                                                ?>

                                                </td>
                                                @foreach ($timeSlots as $time)
                                                    @if (isset($scheduleByTime[$time]) && count($scheduleByTime[$time]) > 0)
                                                        <td class="active">
                                                            @foreach ($scheduleByTime[$time] as $doctor)
                                                                <h4>{{ $doctor->user->name }}</h4>
                                                                <p>{{ $doctor->schedule->first()->start_time }} - {{ $doctor->schedule->first()->end_time }}
                                                                </p>
                                                                <div class="hover">
                                                                    @foreach ($scheduleByTime[$time] as $doctor)
                                                                        <h4>{{ $doctor->user->name }}</h4>
                                                                        <span>{{ $doctor->specialization ?? 'Bác sĩ' }}</span>
                                                                        <p>{{$doctor->department->name}}</p>
                                                                    @endforeach
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @endforeach
                                     </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection