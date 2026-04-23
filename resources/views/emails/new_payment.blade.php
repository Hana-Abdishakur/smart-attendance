<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #4f46e5;">New Payment Alert! 💰</h2>
        <p>Salaan mudane Admin,</p>
        <p>Waxaad heshay lacag cusub oo ka timid mid ka mid ah ardayda. Halkan waa faahfaahinta kooban:</p>
        
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Transaction ID:</strong> #{{ $payment->transaction_id }}</p>
            <p><strong>Amount:</strong> <span style="color: #10b981; font-weight: bold;">${{ number_format($payment->amount, 2) }}</span></p>
            <p><strong>Status:</strong> <span style="color: #f59e0b;">Pending Verification</span></p>
        </div>

        <p>Fadlan fur lifaaqa (PDF) ee Email-kan la socda si aad u arkaatid rasiidka rasmiga ah. Ka dibna gal Dashboard-ka si aad u <strong>Approve</strong>-gareeyso.</p>
        
        <a href="{{ route('admin.payments') }}" 
           style="display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold;">
           Go to Payment Dashboard
        </a>

        <p style="font-size: 12px; color: #999; margin-top: 30px;">
            SAPS Smart Attendance & Payment System.
        </p>
    </div>
</body>
</html>