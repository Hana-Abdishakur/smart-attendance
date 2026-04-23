<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>General Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1f2937; margin: 20px; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .stats-summary { background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4f46e5; color: white; padding: 10px; font-size: 11px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        .perf-badge { padding: 3px 6px; background: #e0e7ff; color: #4338ca; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #4f46e5; margin: 0;">SMARTATTEND GENERAL REPORT</h2>
        <p style="font-size: 10px; color: #6b7280;">Generated: {{ now()->format('d M, Y H:i') }}</p>
    </div>

    <div class="stats-summary">
        <strong>OVERALL STATS:</strong> &nbsp; 
        Present: {{ $totalPresent }} | Late: {{ $totalLate }} | Absent: {{ $totalAbsent }} | 
        <strong>Avg Attendance: {{ $avgRate }}%</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Present</th>
                <th>Late</th>
                <th>Absent</th>
                <th>Performance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
            <tr>
                <td>{{ $data['name'] }}</td>
                <td>{{ $data['email'] }}</td>
                <td>{{ $data['present'] }}</td>
                <td>{{ $data['late'] }}</td>
                <td>{{ $data['absent'] }}</td>
                <td>
                    @php 
                        $total = $data['present'] + $data['late'] + $data['absent'];
                        $perc = $total > 0 ? round((($data['present'] + $data['late']) / $total) * 100, 1) : 0;
                    @endphp
                    <span class="perf-badge">{{ $perc }}%</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>