<?php

namespace OpenCore\App\Http\Controllers\Admin\Core\Tasks;

use OpenCore\App\Http\Controllers\Controller;
use Studio\Totem\Contracts\TaskInterface;
use Studio\Totem\Http\Requests\ImportRequest;

class ImportTasksController extends Controller
{
    /**
     * @var TaskInterface
     */
    private $tasks;

    /**
     * ImportTasksController constructor.
     * @param TaskInterface $tasks
     */
    public function __construct(TaskInterface $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Import tasks from a json file.
     * @param \Studio\Totem\Http\Requests\ImportRequest $request
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(ImportRequest $request)
    {
        $this->tasks->import($request->validated());
    }
}
