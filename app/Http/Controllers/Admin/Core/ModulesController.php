<?php
/*
 * Created on Wed Jan 22 2020 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace App\Http\Controllers\Admin\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use OpenCore\Support\Modules\ModuleManager;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Module;
use Symfony\Component\Console\Output\BufferedOutput;
use OpenCore\Support\Opencart\Startup;

class ModulesController extends Controller
{
    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var RepositoryInterface
     */
    private $modules;

    public function __construct(ModuleManager $moduleManager, RepositoryInterface $modules)
    {
        $this->moduleManager = $moduleManager;
        $this->modules = $modules;
    }

    /**
     * Display a list of all modules
     * @return View
     */
    public function index()
    {
        $modules = $this->modules->all();

        return view('admin.core.modules.index', compact('modules'));
    }

    /**
     * Display module info
     * @param Module $module
     * @return View
     */
    public function show(Module $module)
    {
        $changelog = $this->moduleManager->changelogFor($module);

        return view('admin.core.modules.show', compact('module', 'changelog'));
    }

    /**
     * Disable the given module
     * @param Module $module
     * @return mixed
     */
    public function disable(Module $module)
    {
        if ($this->isCoreModule($module)) {
            return redirect()->route('admin::core.modules.show', [$module->getLowerName()])
                ->with('error', trans('modules.disabled_error'));
        }

        $module->disable();

        /**
         * Add permission to this module
         */
        $load = Startup::getRegistry('load');
        $load->model('user/user_group');

        $userGroupId = Startup::getRegistry('user')->getGroupId();

        $path = strtolower($module->getName()) . '/*';
        $model_user_user_group = Startup::getRegistry('model_user_user_group');
        $model_user_user_group->removePermission($userGroupId, 'access', $path);
        $model_user_user_group->removePermission($userGroupId, 'modify', $path);

        Artisan::call('cache:clear');

        return redirect()->route('admin::core.modules.show', [$module->getLowerName()])
            ->with('success', trans('general.status.disabled'));
    }

    /**
     * Enable the given module
     * @param Module $module
     * @return mixed
     */
    public function enable(Module $module)
    {
        $module->enable();

        /**
         * Add permission to this module
         */
        $load = Startup::getRegistry('load');
        $load->model('user/user_group');

        $userGroupId = Startup::getRegistry('user')->getGroupId();

        $path = strtolower($module->getName()) . '/*';
        $model_user_user_group = Startup::getRegistry('model_user_user_group');
        $model_user_user_group->addPermission($userGroupId, 'access', $path);
        $model_user_user_group->addPermission($userGroupId, 'modify', $path);

        Artisan::call('cache:clear');

        return redirect()->route('admin::core.modules.show', [$module->getLowerName()])->with(
            'success',
            trans('general.status.enabled')
        );
    }

    /**
     * Update a given module
     * @param Request $request
     * @return Response json
     */
    public function update(Module $module)
    {
        $output = new BufferedOutput();
        Artisan::call('module:update', ['module' => $module->getName()], $output);

        return Response::json(['updated' => true, 'message' => $output->fetch()]);
    }

	public function publishAssets(Module $module)
    {
        try {
            Artisan::call('module:publish', ['module' => $module->getName()]);
            //pre(Artisan::output(),1);
        } catch (InvalidArgumentException $e) {
        }
    }

    /**
     * Check if the given module is a core module that should be be disabled
     * @param Module $module
     * @return bool
     */
    private function isCoreModule(Module $module)
    {
        $coreModules = array_flip(config('opencore.coreModules'));

        return isset($coreModules[$module->getLowerName()]);
    }
}
