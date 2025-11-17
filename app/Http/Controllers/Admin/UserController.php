<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->latest()->paginate(15);
        return view('pages.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'account_type' => ['required', 'in:master,user'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'account_type' => $request->account_type,
            'password' => Hash::make($request->password),
        ]);

        Log::create([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'action_detail' => "Admin created a new user: {$user->name} (ID: {$user->id})",
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $logs = Log::where('user_id', $user->id)->oldest()->get();
        $logContent = "";
        foreach ($logs as $log) {
            $logContent .= "[{$log->created_at->format('Y-m-d H:i:s')}] Action: {$log->action_type} | Details: {$log->action_detail}\n";
        }

        return view('pages.show', compact('user', 'logContent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Not implemented for this request
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Not implemented for this request
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Reassign logs or handle related data before deleting
        // For now, we will just delete the user.
        // Note: This will fail if there are foreign key constraints on the logs table.
        // A better approach would be to set the user_id on logs to null or a 'deleted user' id.
        
        $userName = $user->name;
        $userId = $user->id;

        try {
            $user->delete();

            Log::create([
                'user_id' => Auth::id(),
                'action_type' => 'delete',
                'action_detail' => "Admin deleted user: {$userName} (ID: {$userId})",
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // This will catch errors related to foreign key constraints
            return back()->with('error', 'Cannot delete this user. They have associated logs or other records.');
        }
    }
}
