<?php

namespace App\Observers;

use Studio\Totem\Task;
use Illuminate\Support\Facades\Artisan;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $this->clearCache();
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        $this->clearCache();
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $this->clearCache();
    }

    private function clearCache()
    {
        Artisan::call('cache:clear');
    }
}
