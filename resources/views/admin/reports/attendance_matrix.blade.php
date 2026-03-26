<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; margin: 0; padding: 0; }
        table { border-collapse: collapse; width: 100%; border: 1px solid #000; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; white-space: nowrap; }
        
        .bg-sunday { background-color: #ff0000; color: #fff; font-weight: bold; }
        .bg-holiday { background-color: #ff0000; color: #fff; font-weight: bold; }
        .bg-leave { background-color: #8B0000; color: #fff; font-weight: bold; }
        .bg-halfday { background-color: #ffff00; font-weight: bold; color: #000; }
        
        .header-date { background-color: #fce4d6; font-weight: bold; text-align: left; }
        .header-user-even { background-color: #e2efda; font-weight: bold; }
        .header-user-odd { background-color: #d9e1f2; font-weight: bold; }
        .header-login { background-color: #fff2cc; font-weight: bold; }
        .header-logout { background-color: #fff2cc; font-weight: bold; }
        
        .date-cell { text-align: left; background-color: #fce4d6; font-weight: bold; }
        .time-cell { font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="header-date">Date</th>
                @foreach($users as $index => $user)
                    <th colspan="2" class="{{ $index % 2 == 0 ? 'header-user-even' : 'header-user-odd' }}">
                        {{ $user->name }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach($users as $user)
                    <th class="header-login">Login</th>
                    <th class="header-logout">Logout</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($dates as $dateStr => $dateObj)
                @php
                    $isSunday = $dateObj->isSunday();
                    $holiday = $holidays->firstWhere('date', $dateStr);
                @endphp
                <tr>
                    <td class="date-cell">{{ $dateObj->format('j M') }}</td>
                    
                    @if($isSunday)
                        @foreach($users as $user)
                            <td colspan="2" class="bg-sunday">Sunday</td>
                        @endforeach
                    @elseif($holiday)
                        @foreach($users as $user)
                            <td colspan="2" class="bg-holiday">{{ $holiday->title ?? 'Holiday' }}</td>
                        @endforeach
                    @else
                        @foreach($users as $user)
                            @php
                                $att = $attendances[$user->id][$dateStr] ?? null;
                                $leave = $leaves[$user->id][$dateStr] ?? null;
                                
                                $isLeave = ($leave && $leave->status == 'approved');
                                $loginTime = '';
                                $logoutTime = '';
                                $isHalfDay = false;

                                if ($att) {
                                    $loginTime = $att->login_at ? \Carbon\Carbon::parse($att->login_at)->setTimezone('Asia/Kolkata')->format('g:i') : '';
                                    $logoutTime = $att->logout_at ? \Carbon\Carbon::parse($att->logout_at)->setTimezone('Asia/Kolkata')->format('g:i') : '';
                                    // Let's use 420 mins parameter for half day
                                    $durationMins = $att->work_duration_minutes ?? 0;
                                    if ($durationMins > 0 && $durationMins < 420) {
                                        $isHalfDay = true;
                                        $loginTime = 'Half day'; // Using cell 1 for the tag
                                    }
                                }
                            @endphp

                            @if($isLeave)
                                <td colspan="2" class="bg-leave">Leave</td>
                            @else
                                <td class="{{ $isHalfDay ? 'bg-halfday' : 'time-cell' }}">{{ $loginTime }}</td>
                                <td class="time-cell">{{ $logoutTime }}</td>
                            @endif
                        @endforeach
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>