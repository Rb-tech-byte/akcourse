<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function transaction(Request $request, $userId = null)
    {
        $pageTitle = 'Transaction Audit & Financial Logs';

        $remarks = Transaction::distinct('remark')->whereNotNull('remark')->orderBy('remark')->get('remark');

        // Aggregates for native cards
        $baseQuery = Transaction::query();
        if ($userId) {
            $baseQuery->where('user_id', $userId);
        }

        $totalCount       = (clone $baseQuery)->count();
        $totalCredit      = (clone $baseQuery)->where('trx_type', '+')->sum('amount');
        $totalDebit       = (clone $baseQuery)->where('trx_type', '-')->sum('amount');
        $uniqueUsersCount = (clone $baseQuery)->distinct('user_id')->count('user_id');
        $netFlow          = $totalCredit - $totalDebit;

        $transactions = Transaction::searchable(['trx', 'user:username', 'user:email'])
            ->filter(['trx_type', 'remark'])
            ->dateFilter()
            ->orderBy('id', 'desc')
            ->with('user');

        if ($userId) {
            $transactions = $transactions->where('user_id', $userId);
        }

        $transactions = $transactions->paginate(getPaginate());

        return view('admin.reports.transactions', compact(
            'pageTitle',
            'transactions',
            'remarks',
            'totalCount',
            'totalCredit',
            'totalDebit',
            'uniqueUsersCount',
            'netFlow',
            'userId'
        ));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Access & Login Audit Trail';

        // Aggregates for native cards
        $totalLogins = UserLogin::count();
        $uniqueUsers = UserLogin::distinct('user_id')->count('user_id');
        $uniqueIps   = UserLogin::distinct('user_ip')->count('user_ip');
        
        $topBrowser = UserLogin::select('browser', DB::raw('count(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->first();

        $topOs = UserLogin::select('os', DB::raw('count(*) as count'))
            ->whereNotNull('os')
            ->groupBy('os')
            ->orderByDesc('count')
            ->first();

        $loginLogs = UserLogin::orderBy('id', 'desc')
            ->searchable(['user:username', 'user:email', 'user_ip', 'city', 'country'])
            ->dateFilter()
            ->with('user')
            ->paginate(getPaginate());

        return view('admin.reports.logins', compact(
            'pageTitle',
            'loginLogs',
            'totalLogins',
            'uniqueUsers',
            'uniqueIps',
            'topBrowser',
            'topOs'
        ));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Access Logs for IP: ' . $ip;

        $totalLogins = UserLogin::where('user_ip', $ip)->count();
        $uniqueUsers = UserLogin::where('user_ip', $ip)->distinct('user_id')->count('user_id');
        $uniqueIps   = 1;
        $topBrowser  = UserLogin::where('user_ip', $ip)->select('browser', DB::raw('count(*) as count'))->groupBy('browser')->orderByDesc('count')->first();
        $topOs       = UserLogin::where('user_ip', $ip)->select('os', DB::raw('count(*) as count'))->groupBy('os')->orderByDesc('count')->first();

        $loginLogs = UserLogin::where('user_ip', $ip)
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate(getPaginate());

        return view('admin.reports.logins', compact(
            'pageTitle',
            'loginLogs',
            'ip',
            'totalLogins',
            'uniqueUsers',
            'uniqueIps',
            'topBrowser',
            'topOs'
        ));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'System Notification Dispatch History';

        // Real channel aggregates
        $totalNotifications = NotificationLog::count();
        $emailCount = NotificationLog::where('notification_type', 'email')->count();
        $smsCount   = NotificationLog::where('notification_type', 'sms')->count();
        $pushCount  = NotificationLog::where('notification_type', 'push')->count();
        $todayCount = NotificationLog::whereDate('created_at', today())->count();

        $logs = NotificationLog::orderBy('id', 'desc')
            ->searchable(['user:username', 'user:email', 'sender', 'subject', 'sent_to'])
            ->filter(['notification_type'])
            ->dateFilter()
            ->with('user')
            ->paginate(getPaginate());

        return view('admin.reports.notification_history', compact(
            'pageTitle',
            'logs',
            'totalNotifications',
            'emailCount',
            'smsCount',
            'pushCount',
            'todayCount'
        ));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Dispatch Preview';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }
}
