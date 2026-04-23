<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SmartAttend Official General Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1f2937; margin: 0; padding: 40px; }
        .header { border-bottom: 4px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .logo-box { float: left; width: 60px; height: 60px; color: white; text-align: center; line-height: 60px; font-weight: bold; border-radius: 12px; font-size: 24px; background: #4f46e5; }
        .university-info { float: left; margin-left: 20px; }
        .clear { clear: both; }
        
        .stats-table { width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 10px 0; }
        .stats-card { background: #f9fafb; border: 1px solid #e5e7eb; padding: 15px; text-align: center; border-radius: 15px; }
        .stats-val { font-size: 22px; font-weight: bold; display: block; margin-bottom: 5px; }
        .stats-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }

        table.main-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #4f46e5; color: white; padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) { background-color: #fcfcfc; }

        .footer { margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px; font-size: 10px; color: #6b7280; }
        .signature-section { margin-top: 40px; }
        .sig-box { float: right; width: 200px; text-align: center; border-top: 2px solid #1f2937; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-box">SA</div>
        <div class="university-info">
            <h1 style="margin: 0; color: #4f46e5; font-size: 22px;">SMARTATTEND PORTAL</h1>
            <p style="margin: 5px 0; font-size: 12px;">Monthly General Attendance Summary</p>
            <p style="margin: 0; font-size: 10px; color: #9ca3af;">Generated: {{ now()->format('d M, Y H:i') }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <table class="stats-table">
        <tr>
            <td class="stats-card">
                <span class="stats-val" style="color: #10b981;">{{ $totalPresent }}</span>
                <span class="stats-label">Total Present</span>
            </td>
            <td class="stats-card">
                <span class="stats-val" style="color: #f59e0b;">{{ $totalLate }}</span>
                <span class="stats-label">Total Late</span>
            </td>
            <td class="stats-card">
                <span class="stats-val" style="color: #ef4444;">{{ $totalAbsent }}</span>
                <span class="stats-label">Total Absent</span>
            </td>
            <td class="stats-card" style="background: #4f46e5; color: white;">
                <span class="stats-val">{{ $avgRate }}%</span>
                <span class="stats-label" style="color: #c7d2fe;">Global Rate</span>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email Address</th>
                <th style="text-align: center;">P</th>
                <th style="text-align: center;">L</th>
                <th style="text-align: center;">A</th>
                <th style="text-align: right;">Status</th>
            </tr>
        </thead>
        <tbody>
    @foreach($reportData as $data)
        @php 
            $p = (int)$data['present'];
            $l = (int)$data['late'];
            $a = (int)$data['absent'];
            $total = $p + $l + $a;
            $rate = $total > 0 ? round((($p + $l) / $total) * 100) : 0;
            
            // Halkan si toos ah u dhiib midabka (Hex Code)
            if ($rate < 75) {
                $fColor = '#dc2626'; // Red
                $fBg    = '#fef2f2'; // Light Red
            } else {
                $fColor = '#059669'; // Green
                $fBg    = '#ecfdf5'; // Light Green
            }
        @endphp
        <tr>
            <td><strong>{{ $data['name'] }}</strong></td>
            <td>{{ $data['email'] }}</td>
            <td style="text-align: center;">{{ $p }}</td>
            <td style="text-align: center;">{{ $l }}</td>
            <td style="text-align: center;">{{ $a }}</td>
            <td style="text-align: right;">
                <span style="padding: 4px 10px; border-radius: 6px; font-weight: bold; color: {{ $fColor }}; background-color: {{ $fBg }}; border: 1px solid {{ $fColor }};">
                    {{ $rate }}%
                </span>
            </td>
        </tr>
    @endforeach
</tbody>
    </table>

    <div class="footer">
        <div style="float: left; width: 60%;">
            <p><strong>Note:</strong> Students below 75% are highlighted in red for review.</p>
        </div>
        <div class="signature-section">
            <div class="sig-box">
                <p style="margin: 0; font-weight: bold;">University Registrar</p>
                <p style="margin: 0; font-size: 8px;">Authorized Electronic Signature</p>
            </div>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>