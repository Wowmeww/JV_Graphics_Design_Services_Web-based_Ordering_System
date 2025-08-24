<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemSetting;

class ResetDailyVisitorCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-daily-visitor-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the daily visitor count';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $setting = SystemSetting::where('key', 'daily_visitors')->first();

        if ($setting) {
            $setting->value = '';
            $setting->save();
            $this->info('Daily visitor count reset.');
        } else {
            $this->info('No daily_visitor setting found.');
        }
    }
}
