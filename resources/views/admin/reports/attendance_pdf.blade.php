<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f5;
            font-weight: bold;
        }

        h2 {
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .text-center {
            text-align: center;
        }

        .badge-full {
            color: #065f46;
            font-weight: bold;
        }

        .badge-half {
            color: #92400e;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h2>Attendance & System Access Report</h2>
        <p>Generated on: {{ \Carbon\Carbon::now()->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Date</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Total Time (Minutes)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $row)
                @php 
                                    $minutes = $row->work_duration_minutes ?? 0;
                    if ($minutes == 0 && $row->loginSessions && $row->loginSessions->count() > 0) {
                        $totalSec = 0;
                        $dayStart = \Carbon\Carbon::parse($row->date)->startOfDay();
                        $dayEnd = \Carbon\Carbon::parse($row->date)->endOfDay();
                        foreach ($row->loginSessions as $s) {
                            $st = $s->login_at->copy()->setTimezone('Asia/Kolkata');
                            $st = $st->gt($dayStart) ? $st : $dayStart;
                            $ed = ($s->logout_at ?? now())->copy()->setTimezone('Asia/Kolkata');
                            $ed = $ed->lt($dayEnd) ? $ed : $dayEnd;
                            if ($ed->gt($st))
                                $totalSec += $ed->diffInSeconds($st);
                        }
                        $minutes = floor($totalSec / 60);
                    }
                    $status = ($minutes >= 420) ? 'Full Day' : 'Half Day';
                @endphp
                    <tr>
                        <td>{{ $row->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->date)->format('Y-m-d') }}</td>
                        <td>{{ $row->login_at ? $row->login_at->setTimezone('Asia/Kolkata')->format('h:i A') : '-' }}</td>
                        <td>{{ $row->logout_at ? $row->logout_at->setTimezone('Asia/Kolkata')->format('h:i A') : '-' }}</td>
                        <td>{{ $minutes }} mins</td>
                        <td class="{{ $status == 'Full Day' ? 'badge-full' : 'badge-half' }}">{{ $status }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
