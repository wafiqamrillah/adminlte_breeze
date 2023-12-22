<?php

namespace App\Listeners\System\Auth;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\{ Login, Attempting, Logout };

class LoginLogsListener
{
    /**
     * Handle the event.
     */
    public function handle(
        Login|Attempting|Logout $event
    ): void
    {
        $logMessage = null;
        $params = [
            'user_agent' => request()->header('User-Agent'),
            'remember' => $event->remember ?? false
        ];
        
        // Set log message
        switch (get_class($event)) {
            case Login::class:
                $logMessage = 'User logged in';
                $params['user'] = $event->user;
                break;
            case Attempting::class:
                $logMessage = 'User attempting to login';
                $params['credentials'] = $event->credentials;
                break;
            case Logout::class:
                $logMessage = 'User logged out';
                $params['user'] = $event->user;
                break;
        }

        if (!$logMessage) return;

        Log::channel('login')->info($logMessage . ' : ', $params);
    }
}
