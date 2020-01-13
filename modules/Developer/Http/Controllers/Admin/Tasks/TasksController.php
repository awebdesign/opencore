<?php

namespace Modules\Developer\Http\Controllers\Admin\Tasks;

use Studio\Totem\Task;
use Studio\Totem\Totem;
use Illuminate\Database\Eloquent\Builder;
use Studio\Totem\Contracts\TaskInterface;
use Studio\Totem\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class TasksController extends Controller
{
    /**
     * @var TaskInterface
     */
    private $tasks;

    /**
     * TasksController constructor.
     *
     * @param TaskInterface $tasks
     */
    public function __construct(TaskInterface $tasks)
    {

        $this->tasks = $tasks;
    }

    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $fullurl = Request::fullUrl();

        return view('developer::admin.tasks.index', [
            'fullurl' => $fullurl,
            'tasks' => $this->tasks
                ->builder()
                ->sortableBy([
                    'description',
                    'last_ran_at',
                    'average_runtime',
                ], ['description'=>'asc'])
                ->when(request('q'), function (Builder $query) {
                    $query->where('description', 'LIKE', '%'.request('q').'%');
                })
                ->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $commands = Totem::getCommands()->map(function ($command) {
            return ['name' => $command->getName(), 'description' => $command->getDescription()];
        });

        return view('developer::admin.tasks.form', [
            'task'          => new Task,
            'commands'      => $commands,
            'timezones'     => timezone_identifiers_list(),
            'frequencies'   => Totem::frequencies(),
        ]);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param TaskRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TaskRequest $request)
    {
        $this->tasks->store($request->all());

        return redirect()
            ->route('developer::admin.tasks.dashboard')
            ->with('success', trans('totem::messages.success.create'));
    }

    /**
     * Display the specified task.
     *
     * @param $task
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Task $task)
    {
        return view('developer::admin.tasks.view', [
            'task'  => $task,
        ]);
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param $task
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Task $task)
    {
        $commands = Totem::getCommands()->map(function ($command) {
            return ['name' => $command->getName(), 'description' => $command->getDescription()];
        });

        return view('developer::admin.tasks.form', [
            'task'          => $task,
            'commands'      => $commands,
            'timezones'     => timezone_identifiers_list(),
            'frequencies'   => Totem::frequencies(),
        ]);
    }

    /**
     * Update the specified task in storage.
     *
     * @param TaskRequest $request
     * @param $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task = $this->tasks->update($request->all(), $task);

        return redirect()->route('developer::admin.tasks.view', $task)
            ->with('task', $task)
            ->with('success', trans('totem::messages.success.update'));
    }

    /**
     * Remove the specified task from storage.
     *
     * @param $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        $this->tasks->destroy($task);

        return redirect()
            ->route('developer::admin.tasks.dashboard')
            ->with('success', trans('totem::messages.success.delete'));
    }
}
