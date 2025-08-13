<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CountDailyVisitors
{
    public function handle(Request $request, Closure $next)
    {
        // where do Carbon base its time its not matching my browsers time
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentHour = $now->format('H:i'); // 24-hour format, e.g., "14:35"

        $ip = $request->ip();
        $userId = $request?->user()?->id;

        // Cache check
        $cacheKey = "visit_{$userId}_{$ip}_{$today}";
        if (Cache::has($cacheKey)) {
            $this->updateLastVisitTime($ip, $userId, $currentHour);
            return $next($request);
        }

        DB::transaction(function () use ($ip, $userId, $currentHour, $today) {
            $settings = DB::table('settings')
                ->where('key', 'daily_visitors')
                ->lockForUpdate()
                ->first();

            // Initialize default structure
            $visitorData = [
                'unique_visitors' => 0,
                'visits_by_hour' => [], // Now storing hours instead of full timestamps
                'total_visits' => 0,
            ];

            // Safely merge with existing data
            if ($settings && !empty($settings->value)) {
                $decoded = json_decode($settings->value, true);
                if (is_array($decoded)) {
                    $visitorData = array_merge($visitorData, $decoded);
                }
            }

            // Ensure visits_by_hour is always an array
            if (!isset($visitorData['visits_by_hour']) || !is_array($visitorData['visits_by_hour'])) {
                $visitorData['visits_by_hour'] = [];
            }

            // Check if this hour already has a visit record
            $hourExists = in_array($currentHour, $visitorData['visits_by_hour']);

            if (!$hourExists) {
                $visitorData['unique_visitors']++;
                $visitorData['visits_by_hour'][] = $currentHour;
            }

            $visitorData['total_visits']++;

            DB::table('settings')
                ->where('key', 'daily_visitors')
                ->update(['value' => json_encode($visitorData)]);
        });

        Cache::put($cacheKey, true, now()->endOfDay());
        return $next($request);
    }

    private function updateLastVisitTime($ip, $userId, $hour)
    {
        // No need to update for hourly tracking since we're just tracking hours
        // This method is kept for interface consistency but does nothing
        return;
    }
}
