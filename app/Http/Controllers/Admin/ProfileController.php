<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the admin's profile page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        $logContent = $this->getAdminLogs($admin->id);

        return view('admin.profile', compact('admin', 'logContent'));
    }

    /**
     * Get formatted logs for a specific admin user.
     *
     * @param int $userId
     * @return string
     */
    private function getAdminLogs(int $userId): string
    {
        $logs = Log::where('user_id', $userId)->oldest()->get();
        $logContent = "";
        foreach ($logs as $log) {
            $logContent .= "[{$log->created_at->format('Y-m-d H:i:s')}] Action: {$log->action_type} | Details: {$log->action_detail}\n";
        }
        return $logContent;
    }
}
