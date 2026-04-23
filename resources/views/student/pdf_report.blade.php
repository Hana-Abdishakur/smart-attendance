<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report - {{ auth()->user()->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        
        /* Header & CSS Logo Section */
        .header-table { width: 100%; border-bottom: 2px solid #4f46e5; padding-bottom: 15px; margin-bottom: 20px; }
        
        /* Modern CSS Logo */
        .logo-container { vertical-align: middle; }
        .logo-icon { 
            background: #4f46e5; 
            color: white; 
            width: 45px; 
            height: 45px; 
            line-height: 45px; 
            text-align: center; 
            border-radius: 12px; 
            font-size: 22px; 
            font-weight: bold;
            display: inline-block;
        }
        .logo-text-wrapper { display: inline-block; vertical-align: middle; margin-left: 12px; }
        .logo-main-text { font-size: 24px; font-weight: bold; color: #4f46e5; letter-spacing: -1px; }
        .logo-sub-text { font-size: 9px; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-top: -2px; }

        .verify-qr { text-align: right; vertical-align: top; }
        
        /* Stats Styling */
        .stats-container { margin-bottom: 25px; width: 100%; }
        .stats-box { 
            width: 30.5%; display: inline-block; background: #f8faff; 
            padding: 15px 0; border-radius: 12px; text-align: center; border: 1px solid #eef2ff;
        }
        .stats-label { display: block; color: #8892b0; font-size: 8px; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .stats-value { display: block; font-size: 18px; font-weight: bold; color: #1e293b; }

        /* Table Styling */
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background-color: #f1f5f9; color: #475569; padding: 12px 10px; text-align: left; font-size: 10px; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        table.data-table td { border-bottom: 1px solid #f1f5f9; padding: 12px 10px; color: #334155; }
        
        .status-badge { 
            color: #10b981; 
            background: #ecfdf5; 
            padding: 4px 8px; 
            border-radius: 6px; 
            font-weight: bold; 
            font-size: 9px; 
        }

        .footer-section { margin-top: 60px; }
        .signature-box { width: 200px; float: right; text-align: center; }
        .line { border-top: 1.5px solid #1e293b; margin-bottom: 8px; }
        .signature-text { font-size: 10px; font-weight: bold; color: #1e293b; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="border:none; width: 70%;" class="logo-container">
                <div class="logo-icon">S</div>
                <div class="logo-text-wrapper">
                    <div class="logo-main-text">SmartAttend</div>
                    <div class="logo-sub-text">Digital University Record</div>
                </div>
            </td>
            <td class="verify-qr" style="border:none;">
                @php
                    $verifyData = "Verified Student: " . auth()->user()->name . " | ID: " . auth()->user()->id . " | Total Attendance: " . $attendances->count();
                @endphp
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(70)->generate($verifyData)) }}">
                <p style="font-size: 7px; color: #94a3b8; margin-top: 4px; font-weight: bold;">SECURE VERIFICATION QR</p>
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 25px; background: #fff; border-left: 4px solid #4f46e5; padding-left: 15px;">
        <p style="margin: 0; font-size: 14px;"><strong>{{ auth()->user()->name }}</strong></p>
        <p style="margin: 3px 0; color: #64748b;">Student ID: #{{ auth()->user()->id }} | {{ auth()->user()->email }}</p>
        <p style="margin: 0; color: #64748b; font-size: 10px;">Period: {{ request('start_date') ?? 'All Time' }} — {{ request('end_date') ?? date('Y-m-d') }}</p>
    </div>

    <div class="stats-container">
        <div class="stats-box">
            <span class="stats-label">Total Sessions</span>
            <span class="stats-value">{{ $attendances->count() }}</span>
        </div>
        <div class="stats-box" style="margin: 0 1.5%;">
            <span class="stats-label">Present</span>
            <span class="stats-value">{{ $attendances->where('status', 'Present')->count() }}</span>
        </div>
        <div class="stats-box">
            <span class="stats-label">Attendance Rate</span>
            <span class="stats-value">
                {{ $attendances->count() > 0 ? round(($attendances->where('status', 'Present')->count() / $attendances->count()) * 100) : 0 }}%
            </span>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Course Name</th>
                <th>Check-in Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $row)
            <tr>
                <td style="font-weight: bold;">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                <td>{{ $row->course->name ?? 'N/A' }}</td>
                <td style="color: #64748b;">{{ $row->check_in ?? '--:--' }}</td>
                <td><span class="status-badge">{{ $row->status }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-section">
        <div class="signature-box">
            <div class="line"></div>
            <p class="signature-text">Registrar / Academic Dean</p>
            <p style="font-size: 8px; color: #94a3b8;">Issued on: {{ date('M d, Y @ H:i') }}</p>
        </div>
    </div>

</body>
</html>