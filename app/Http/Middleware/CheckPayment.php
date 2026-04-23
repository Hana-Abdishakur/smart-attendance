<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckPayment
{
    public function handle(Request $request, Closure $next)
{
    $user = auth()->user();

    // 1. Hubi inuu ardaygu bixiyey lacag (Completed ama Pending)
    $paymentExists = DB::table('payments')
        ->where('user_id', $user->id)
        ->whereIn('status', ['completed', 'pending']) // Halkan ku dar 'pending'
        ->exists();

    // 2. Haddii uusan xitaa soo gudbin (No record), u dir Checkout
    // Laakiin haddii uu 'pending' yahay, u oggolow inuu Dashboard-ka aado
    if (!$paymentExists && !$request->is('student/checkout*', 'student/pay')) {
        return redirect()->route('student.checkout');
    }

    return $next($request);
}
}