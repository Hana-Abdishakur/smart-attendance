<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
        .details { margin-top: 30px; width: 100%; border-collapse: collapse; }
        .details td { padding: 10px; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #666; width: 200px; }
        .amount { font-size: 24px; font-weight: bold; color: #4f46e5; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SAPS PAYMENT ALERT</h1>
        <p>New Tuition Payment Recorded</p>
    </div>

    <table class="details">
        <tr>
            <td class="label">Student Name:</td>
            <td>{{ $student->name }}</td>
        </tr>
        <tr>
            <td class="label">Transaction ID:</td>
            <td style="font-family: monospace;">#{{ $paymentData->transaction_id }}</td>
        </tr>
        <tr>
            <td class="label">Amount Paid:</td>
            <td class="amount">${{ number_format($paymentData->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Date & Time:</td>
            <td>{{ $paymentData->created_at }}</td>
        </tr>
    </table>

    <div style="margin-top: 50px; text-align: center; color: #999; font-size: 12px;">
        This is an automated notification from SAPS Attendance & Payment System.
    </div>
</body>
</html>