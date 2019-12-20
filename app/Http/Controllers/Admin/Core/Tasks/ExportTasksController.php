<?php

namespace App\Http\Controllers\Admin\Core\Tasks;

use File;
use function storage_path;
use Studio\Totem\Contracts\TaskInterface;
use App\Http\Controllers\Controller;

class ExportTasksController extends Controller
{
    /**
     * @var TaskInterface
     */
    private $tasks;

    /**
     * ExportTasksController constructor.
     * @param TaskInterface $tasks
     */
    public function __construct(TaskInterface $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Export all tasks to a json file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index()
    {
        File::put(storage_path('tasks.json'), $this->tasks->findAll()->toJson());

        return response()
            ->download(storage_path('tasks.json'), 'tasks.json')
            ->deleteFileAfterSend(true);
    }
}
