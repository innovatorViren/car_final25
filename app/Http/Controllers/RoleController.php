<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Requests\RoleRequest;
use Clean\Services\Permission;
use App\DataTables\RoleDataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Users\IlluminateUserRepository;

class RoleController extends Controller
{
    /** @var Cartalyst\Sentinel\Users\IlluminateRoleRepository */
    protected $roleRepository, $data;

    public function __construct()
    {
        parent::__construct();
        // Middleware
        $this->middleware('sentinel.auth');
        $this->middleware('permission:roles.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:roles.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles.delete', ['only' => ['destroy']]);

        // Fetch the Role Repository from the IoC container
        $this->roleRepository = app()->make('sentinel.roles');
        // $current_user
    }

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RoleDatatable $dataTable)
    {
        return $dataTable->render('admin.roles.index');
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('Centaur::roles.create');
        view()->share('module_title', 'Add Roles');
        view()->share('module_action', array(
            "back" => array(
                "title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('roles.index'),
                "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn btn-back", 'title' => 'Back')
            ),
        ));
        return view('admin.roles.create', [
            'all_permission' => $this->getPermissionArrayToNameWise((new Permission)->getPermissions()),
        ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        // Validate the form data
        $result = $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:roles',
        ]);

        // Create the Role
        $role = Sentinel::getRoleRepository()->createModel()->create([
            'name' => trim($request->get('name')),
            'slug' => trim($request->get('slug')),
        ]);


        // Cast permissions values to boolean
        $permissions = [];
        foreach ($request->get('permissions', []) as $permission => $value) {
            $permissions[base64_decode($permission)] = ($value != 'false') ? (bool) $value : (bool) '';
        }

        // Set the role permissions
        $role->permissions = $permissions;
        $role->save();

        // All done
        if ($request->expectsJson()) {
            return response()->json(['role' => $role], 200);
        }

        session()->flash('success', "Role '{$role->name}' has been created.");
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified role.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // The roles detail page has not been included for the sake of brevity.
        // Change this to point to the appropriate view for your project.
        return redirect()->route('roles.index');
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Fetch the role object
        // $id = $this->decode($hash);
        $role = $this->roleRepository->findById($id);
        if ($role) {
            $slugName = $role->slug ?? '';
            $groupPermissions = $this->getPermissionJsonToArray($role->permissions);
            $all_permission = $this->getPermissionArrayToNameWise((new Permission)->getPermissions($slugName));
            return view('admin.roles.edit', compact('role', 'groupPermissions', 'all_permission'));
        }

        session()->flash('error', 'Invalid role.');
        return redirect()->back();
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $result = $request->validated();
        $role = $this->roleRepository->findById($id);
        if (!$role) {
            if ($request->expectsJson()) {
                return response()->json("Invalid role.", 422);
            }
            session()->flash('error', 'Invalid role.');
            return redirect()->back()->withInput();
        }
        // Update the role
        $role->name = $request->get('name');
        $role->slug = $request->get('slug');
        // Cast permissions values to boolean
        $permissions = [];
        foreach ($request->get('permissions', []) as $permission => $value) {
            $permissions[base64_decode($permission)] = ($value != 'false') ? (bool) $value : (bool) '';
        }

        // Set the role permissions
        $role->permissions = $permissions;
        $role->save();

        // All done
        if ($request->expectsJson()) {
            return response()->json(['role' => $role], 200);
        }
        session()->flash('success', "Role '{$role->name}' has been updated.");

        if ($request->has('saveBtn')) {
            return redirect()->route('roles.edit', [$id])->with('success', __('common.update_success'));
        } else if ($request->has('saveExitBtn')) {
            return redirect()->route('roles.index')->with('success', __('common.update_success'));
        } else {
            return redirect()->route('roles.index')->with('success', __('common.update_success'));
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Fetch the role object
        $roleRepository = $this->roleRepository->findById($id);
        $role = Role::with(['users'])->find($id);


        // Prevent the deletion of roles have the current user as a member
        if (Sentinel::inRole($roleRepository) || $role->users->count()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "You must leave this group before it can be removed.",
                ], 200);
                return response()->json("You must leave this group before it can be removed.", 422);
            }
            session()->flash('error', "You must leave this group before it can be removed.");
            return redirect()->back()->withInput();
        }

        // Remove the role
        $role->delete();

        // All done
        $message = "Role '{$role->name}' has been removed.";
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        }

        session()->flash('success', $message);
        return redirect()->route('admin.roles.index');
    }

    /**
     * Uses to set name wise JSON to permission base array
     * INPUT = [ 'users.create', 'users.update', 'users.view', 'users.destroy']
     * OUTPUT = 'users'=> [ 'create', 'update', 'view', 'destroy']
     * @param1 array
     * @return array
     * @uses PermissionController,EmployeeController
     */
    public function getPermissionJsonToArray($permission = [])
    {
        $data = [];
        $i = 0;
        foreach ($permission as $permission_key => $permission_value) {
            $permi = explode('.', $permission_key);
            $data[base64_encode($permission_key)] = $permission_value;
        }
        return $data;
    }

    /**
     * Uses to set name wise array to permission base array
     * INPUT = 'users'=> [ 'users.create', 'users.update', 'users.view', 'users.destroy']
     * OUTPUT = 'users'=> [ 'create', 'update', 'view', 'destroy']
     * @param1 array
     * @return array
     * @uses PermissionController,EmployeeController
     */
    public function getPermissionArrayToNameWise($permission = [])
    {
        $data = [];
        foreach ($permission as $permission_key => $permission_array) {
            foreach ($permission_array as $permission_name => $permission_value) {
                $permi = explode('.', $permission_value);
                $data[$permi[0]][$permission_name] = array(
                    'permission' => base64_encode($permission_value),
                    'label' => $permi[1],
                    'can_inherit' => -1,
                );
                //$data[$permi[0]][$permi[1]] = -1; //inherit
                //$data[$permi[0]][$permi[1]] = base64_encode($permi[1]); //inherit
            }
        }
        return $data;
    }

    public function getRolePermission(Request $request, $roleId = null)
    {
        $rId = (!empty($request->role_id)) ? $request->role_id : $roleId;
        $role = $this->roleRepository->findById($rId);
        $cardwisePerm = [
            'users' => 'Side Panel',
            'unit' => 'Master',
            'rm-category' => 'Purchase',
            'grinding' => 'Production',
            'category' => 'Sales',
            'employee' => 'HRM',
            'routes' => 'Shop',
            'transfer' => 'Transfer',
            'purchase' => 'Reports',
        ];
        $rolePermissionData = $groupPermissions = [];
        $index = $ind = $i = 1;
        if ($role) {
            $groupPermissions = $this->getPermissionJsonToArray($role->permissions);
            $allPermission = $this->getPermissionArrayToNameWise((new Permission)->getPermissions());
            if (count($allPermission) > 0) {
                $groupName = "";
                foreach ($allPermission as $group => $permission) {
                    $subItems = [];
                    if (count($permission) > 0) {
                        foreach ($permission as $per) {
                            $isSelected = false;
                            if (array_key_exists($per['permission'], $groupPermissions)) {
                                $isSelected = true;
                            }
                            $subItems[] = [
                                'id'                => $per['permission'],
                                'text'              => ucwords(str_replace(['_', '-'], [' ', ' '], $per['label'])),
                                'spriteCssClass'    => 'html',
                                'checked'           => $isSelected,
                            ];
                        }
                    }
                    $items = [
                        'id'                => $ind,
                        'text'              => ucwords(str_replace(['_', '-'], [' ', ' '], $group)),
                        'expanded'          => false,
                        'spriteCssClass'    => 'folder',
                        'items'             => $subItems,
                    ];
                    if (array_key_exists($group, $cardwisePerm)) {
                        $groupName = $group;
                        $rolePermissionData[$group] = [
                            'id'                => $index,
                            'text'              => $cardwisePerm[$group],
                            'expanded'          => false,
                            'spriteCssClass'    => 'rootfolder',
                            'items'             => [$items]
                        ];
                        $index++;
                    } else {
                        array_push($rolePermissionData[$groupName]['items'], $items);
                    }
                    $ind++;
                }
            }
        }

        if ($roleId) {
            $data = [
                'rolePermissionData' => array_values($rolePermissionData),
                'groupPermissions' => $groupPermissions
            ];
            return $data;
        } else {
            return response()->json([
                'status' => "success",
                'message' => '',
                'data' => [
                    'rolePermissionData' => array_values($rolePermissionData),
                    'groupPermissions' => $groupPermissions
                ],
            ]);
        }
    }

    public function getUsersList(Request $request)
    {
        $role_id = $request->role_id ?? '';
        $roleData = Role::with(['users'])->where('id', $role_id)->first();
        $this->data['roleData'] = $roleData;

        return $this->data;
    }

    public function viewPermission(Request $request)
    {
        return view('admin.roles.view_permission', [
            'all_permission' => $this->getPermissionArrayToNameWise((new Permission)->getPermissions()),
        ]);
    }
}
