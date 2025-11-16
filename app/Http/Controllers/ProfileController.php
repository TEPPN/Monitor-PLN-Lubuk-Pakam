<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        $logContent = $this->getUserLogs($user->id);

        return view('pages.profile', compact('user', 'logContent'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = \App\Models\User::findOrFail($userId);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
        ]);

        $user->update($validatedData);

        Log::create([
            'user_id' => $userId,
            'action_type' => 'update',
            'action_detail' => "User updated their name to: {$user->name}"
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    private function getUserLogs(int $userId): string
    {
        $logs = Log::where('user_id', $userId)->oldest()->get();
        $logContent = "";
        foreach ($logs as $log) {
            $logContent .= "[{$log->created_at->format('Y-m-d H:i:s')}] Action: {$log->action_type} | Details: {$log->action_detail}\n";
        }
        return $logContent;
    }
}
