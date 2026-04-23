<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; background-color: #f8f9fe; padding: 20px; }
        .card { 
            max-width: 400px; 
            margin: auto; 
            background: white; 
            border-radius: 20px; 
            padding: 30px; 
            text-align: center; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        .header { color: #4f46e5; font-size: 22px; font-weight: 800; margin-bottom: 10px; }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
            margin: 15px 0;
        }
        .on-time { background-color: #ecfdf5; color: #059669; }
        .late { background-color: #fef2f2; color: #dc2626; }
        .time-text { color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="header">Attendance Confirmed</h1>
        
        <p style="color: #4b5563; font-size: 14px;">Great job! Your attendance has been successfully recorded.</p>

        <div class="status-badge {{ $status == 'Late' ? 'late' : 'on-time' }}">
            {{ strtoupper($status) }}
        </div>

        <div class="time-text">
            <strong>Check-in Time:</strong> {{ now()->format('h:i A') }}
        </div>

        <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">Thank you for being punctual!</p>
    </div>
</body>
</html>