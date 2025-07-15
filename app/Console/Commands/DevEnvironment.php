<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DevEnvironment extends Command
{
    protected $signature = 'dev:up';
    protected $description = 'Start full dev environment: build frontend, run reverb, and launch dev services';
    public function handle()
    {
        $this->info('🚀 Launching development environment...');
        $this->runProcess('composer run dev');

        return Command::SUCCESS;
    }

    protected function runProcess(string $command)
    {
        $this->info("▶ Running: $command");

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null); // ← disables timeout
        $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
