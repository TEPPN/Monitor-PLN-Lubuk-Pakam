<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logContent = $this->getFormattedLogs();
        return view('pages.log', compact('logContent'));
    }

    public function download()
    {
        $logContent = $this->getFormattedLogs();

        $fileName = 'activity_log_' . now()->format('Y-m-d_H-i-s') . '.log';

        return response($logContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Fetches and formats all log entries.
     *
     * @return string
     */
    private function getFormattedLogs(): string
    {
        $logs = Log::with('user')->oldest()->get();
        $logContent = "";

        /** @var \App\Models\Log $log */
        foreach ($logs as $log) {
            $timestamp = $log->created_at->format('Y-m-d H:i:s');
            $userName = $log->user->name ?? 'System';
            $logContent .= "[{$timestamp}] User: {$userName} | Action: {$log->action_type} | Details: {$log->action_detail}\n";
        }
        return $logContent;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Log $log)
    {
        //
    }
}
