<?php
/*
 * Created on Thu Dec 19 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\App\Http\Controllers\Admin\Core;

use OpenCore\App\Http\Controllers\Controller;
use RachidLaasri\LaravelInstaller\Helpers\RequirementsChecker;
use RachidLaasri\LaravelInstaller\Helpers\PermissionsChecker;

class RequirementsController extends Controller
{
    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * @var PermissionsChecker
     */
    protected $permissions;

    /**
     * @param RequirementsChecker $checker
     * @param PermissionsChecker $checker
     */
    public function __construct(RequirementsChecker $requirements, PermissionsChecker $permissions)
    {
        $this->requirements = $requirements;
        $this->permissions = $permissions;
    }
    /**
     * Display the requirements page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('installer.requirements')
        );

        $permissions = $this->permissions->check(
            config('installer.permissions')
        );

        $cronpath = realpath(basename(__DIR__ . '/../')) . '/core/artisan';

        return view('admin.core.requirements', compact('requirements', 'phpSupportInfo', 'permissions', 'cronpath'));
    }
}
