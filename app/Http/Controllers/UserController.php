<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\{Customer, User, Role, Employee, UserIp};
use App\Http\Requests\UserRequest;
use Centaur\AuthManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Clean\Services\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $userRepository;
    protected $authManager, $common, $data, $is_public = true, $path;


    public function __construct(AuthManager $authManager)
    {
        // Middleware
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:users.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:users.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.delete', ['only' => ['destroy']]);

        // Dependency Injection
        $this->userRepository = app()->make('sentinel.users');
        $this->authManager = $authManager;

        $this->common = new CommonController();
    }

    public function index(UserDataTable $dataTable)
    {
        $this->data['user_type'] = Config('project.user_type');
        $this->data['roles'] = Role::pluck('name', 'id')->toArray();
        return $dataTable->render('admin.users.index',$this->data);
    }

    public function create()
    {
        $roles = Role::pluck('name', 'id')->toArray();
        $this->data['roles'] = $roles;
        $this->data['employees'] = $this->common->getEmployee();
        $this->data['customers'] = $this->common->getCustomer();
        return view('admin.users.create', $this->data);
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        // Assemble registration credentials and attributes
        $credentials = [
            'first_name' => $request->get('first_name', null),
            'last_name' => $request->get('last_name', null),
            'email' => trim($request->get('email')),
            'password' => $request->get('password'),
        ];
        $loginUser = Sentinel::getUser();
        $user_id = $loginUser ? $loginUser->id : 0;
        $allow_access_from_other_network = $request->allow_access_from_other_network ?? 'No';

        $userData = [
            'emp_type' => $request->get('emp_type'),
            'emp_id' => $request->get('emp_id', null),
            'middle_name' => $request->get('middle_name', null),
            'location_id' => $request->get('location_id', null),
            'mobile' => $request->get('mobile', null),
            'roles_id' => $request->get('roles_id', null),
            'is_ip_base' => $request->get('is_ip_base', 'No'),
            'allow_multi_login' => $request->get('allow_multi_login', null),
            'ip' => request()->ip(),
            'created_by' => $user_id,
            'is_active' => 'Yes',
            'allow_access_from_other_network' => $allow_access_from_other_network,
        ];

        $permissions = [];
        $rolePermission = explode(',', $request->get('user_permission'));

        foreach ($rolePermission as $permission => $value) {
            if (strlen($value) > 3) {
                $permissions[base64_decode($value)] = true;
            }
        }
        //dd($permissions);
        if (count($permissions) > 0) {
            $userData['permissions'] = json_encode($permissions);
        }

        if (isset($request['make_super_admin'])) {
            $permissions['users.superadmin'] = true;
            $userData['permissions'] = json_encode($permissions);
        } else {
            $userData['permissions'] = null;
        }


        $file['image'] = '';

        DB::beginTransaction();
        try {
            $activate = true;
            $result = $this->authManager->register($credentials, $activate);
            if ($result->isFailure()) {
                return $result->dispatch;
            }
            $user_id = $result->user->id;

            if ($request->hasFile('image')) {

                $storepath = '/uploads/users/' . $user_id . '/';
                if (!file_exists($storepath)) {
                    mkdir($storepath, 0777, true);
                }
                $file['image'] = self::getUniqueFilename($request->file('image'), self::getImagePath($storepath));
                $request->file('image')->move(self::getImagePath($storepath), $file['image']);
                $userData['image'] = $file['image'];
                $userData['image_path'] = $storepath . $file['image'];
            }
            $user = User::findOrFail($user_id);
            $user->update($userData);

            if (!$activate) {
                $data['code'] = $result->activation->getCode();
                $data['email'] = $result->user->email;
                try {
                } catch (Exception $ex) {
                    Log::error($ex);
                }
            }

            $result->user->roles()->sync(array($request->get('roles_id')));

            if (!is_null($request->get('loginips')) && $request->get('is_ip_base')) {
                $loginips_array = $request->get('loginips');
                UserIp::where('user_id', $user_id)->delete();
                foreach ($loginips_array as $key => $value) {
                    $ipdata['user_id'] = $user_id;
                    $ipdata['login_ip'] = $value['login_ip'];
                    UserIp::create($ipdata);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
        return redirect()->route('users.index')->with('success', __('common.create_success'));
    }

    public function show($id)
    {
        $users = User::with([
            'employee',
            'customer'
        ])->findOrFail($id);
        $this->data['users'] = $users;
        $table_name =  $users->getTable();
        $this->data['table_name'] = $table_name;
        return view('admin.users.show', $this->data);
    }

    public function edit($id)
    {
        $users = User::with([
            'employee',
            'customer',
            // 'usersRole',
            // 'role'
        ])->findOrFail($id);
        // dd($users->toArray());
        $roles = Role::pluck('name', 'id')->toArray();
        $users->is_ip_base = ($users->is_ip_base == 'No') ? 0 : 1;
        $userPermissions = $this->getUserPermission($id);
        $role = $users->role;
        $groupPermissions = (array)$this->getPermissionJsonToArray($role->permissions);
        $groupPermissions = array_keys($groupPermissions);

        $userData = $this->userRepository->findById($id);
        if (isset($users->permissions) && $userData->hasAnyAccess(['users.superadmin'])) {
            $users['make_super_admin']  = 1;
        }
        $this->data['roles'] = $roles;
        if ($users->is_active == 'No') {
            $this->data['employees'] = Employee::whereIN('is_active', ['Yes', 'No'])->orWhere('id', $users->emp_id)
                ->select(DB::raw("(CASE WHEN is_active !='Yes' THEN  CONCAT(first_name, ' ', last_name,' - Inactive') ELSE CONCAT(first_name, ' ', last_name,'') END) as person_name"), "id")->pluck('person_name', 'id')->toArray();
        } else {
            $this->data['employees'] = Employee::where(['id' => $users->emp_id, 'is_active' => "Yes"])
                ->select(DB::raw("CONCAT(first_name, ' ', last_name) as person_name"), "id")->pluck('person_name', 'id')->toArray();
        }

        $this->data['users'] = $users;
        
        $this->data['userPermissions'] = $userPermissions;
        $this->data['groupPermissions'] = $groupPermissions;
        return view('admin.users.edit', $this->data);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        // Validate the form data
        $validated = $request->validated();
        // dd($validated);
        // dd($request->all());
        $attributes = [
            'email' => trim($request->get('email')),
            'first_name' => $request->get('first_name', null),
            'last_name' => $request->get('last_name', null),
        ];

        $loginUser = Sentinel::getUser();
        $user_id = $loginUser ? $loginUser->id : 0;
        $allow_access_from_other_network = $request->allow_access_from_other_network ?? 'No';

        $userData = [
            // 'emp_type' => $request->get('emp_type'),
            // 'emp_id' => $request->get('emp_id', null),
            'middle_name' => $request->get('middle_name', null),
            // 'location_id' => $request->get('location_id', null),
            'mobile' => $request->get('mobile', null),
            'roles_id' => $request->get('roles_id', null),
            'is_ip_base' => $request->get('is_ip_base', 'No'),
            'allow_multi_login' => $request->get('allow_multi_login', null),
            'ip' => request()->ip(),
            'updated_by' => $user_id,
            'update_from_ip' => request()->ip(),
            'allow_access_from_other_network' => $allow_access_from_other_network,

        ];
        $attributes = $request->except(['password', 'password_confirmation']);
        // Do we need to update the password as well?
        if (!empty($request->get('password'))) {
            $userData['password'] = Hash::make($request->get('password'));
        }

        if ($request->get('emp_type') == 'customer') {
            unset($userData['roles_id']);
        }

        $permissions = [];
        $userData['permissions'] = "";
        $rolePermission = explode(',', $request->get('user_permission'));

        foreach ($rolePermission as $permission => $value) {
            if (strlen($value) > 3) {
                $permissions[base64_decode($value)] = true;
            }
        }

        // Fetch the user object
        $user = $this->userRepository->findById($id);

        // if ($user->hasAnyAccess(['users.superadmin']) ) {
        //     $permissions['users.superadmin'] = true;
        // }
        if (count($permissions) > 0) {
            $userData['permissions'] = json_encode($permissions);
        }

        // if (!isset($request['make_super_admin']) && $user->hasAnyAccess(['users.superadmin'])) {
        //     $userData['permissions'] = null;
        // }

        // Update the user
        $user = $this->userRepository->update($user, $attributes);

        $user_id = $id;
        if ($request->get('emp_type') != 'customer') {
            $user->roles()->sync(array($request->get('roles_id')));
        }
        //Attachments
        $file['image'] = '';
        if ($request->hasFile('image')) {
            $storepath = '/uploads/users/' . $user_id . '/';
            if (!file_exists($storepath)) {
                mkdir($storepath, 0777, true);
            }
            $file['image'] = self::getUniqueFilename($request->file('image'), self::getImagePath($storepath));
            $request->file('image')->move(self::getImagePath($storepath), $file['image']);
            $userData['image'] = $file['image'];
            $userData['image_path'] = $storepath . $file['image'];
        }
        $user = User::findOrFail($user_id);
        $user->update($userData);
        $customer_id = $user->customer_id;
        $emp_id = $user->emp_id;
        if($emp_id){
            $employee = Employee::find($emp_id);
            if($employee){
                $employeedata=[
                    'mobile' => $request->get('mobile', null),
                    'email' => trim($request->get('email')),
                    'first_name' => $request->get('first_name', null),
                    'last_name' => $request->get('last_name', null),
                    'middle_name' => $request->get('middle_name', null),
                ];
                $employee->update($employeedata);
            }
        }
        if($customer_id){
            $customer = Customer::findOrFail($customer_id);
            $customerdata=[
                'mobile' => $request->get('mobile', null),
                'email' => trim($request->get('email')),
                'person_name' => $request->get('first_name', null),
            ];           
            $customer->update($customerdata);
        }

        if (!is_null($request->get('loginips')) && $request->get('is_ip_base')) {
            $loginips_array = $request->get('loginips');
            UserIp::where('user_id', $user_id)->delete();
            foreach ($loginips_array as $key => $value) {
                $ipdata['user_id'] = $user_id;
                $ipdata['login_ip'] = $value['login_ip'];
                UserIp::create($ipdata);
            }
        } else {
            UserIp::where('user_id', $user_id)->delete();
        }

        return redirect()->route('users.index')->with('success', __('common.update_success'));
    }

    public function getEmployeeData(Request $request)
    {
        $employeeData = Employee::with('employeeAddress')->where('id', $request->emp_id)->first();
        return  $employeeData;
    }

    /* public function destroy(Request $request, $id)
    {
        // Fetch the user object
        //$id = $this->decode($hash);
        $user = $this->userRepository->findById($id);

        // Check to be sure user cannot delete himself
        if (Sentinel::getUser()->id == $user->id) {
            $message = "You cannot remove yourself!";

            if ($request->expectsJson()) {
                return response()->json($message, 422);
            }
            session()->flash('error', $message);
            return redirect()->route('users.index');
        }

        // Remove the user
        $user->delete();

        // All done
        $message = "{$user->email} has been removed.";
        if ($request->expectsJson()) {
            return response()->json([$message], 200);
        }

        session()->flash('success', $message);
        return redirect()->route('users.index');
    } */

    // protected function decode($hash)
    // {
    //     $decoded = $this->hashids->decode($hash);

    //     if (!empty($decoded)) {
    //         return $decoded[0];
    //     } else {
    //         return null;
    //     }
    // }

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
            }
        }
        return $data;
    }

    public function getUserPermission($userId)
    {
        $permissionArr = $userPermission = $groupPermissions = [];
        $user = User::find($userId);
        $allPermission = $this->getPermissionArrayToNameWise((new Permission)->getPermissions());
        $role = $user->role;
        if ($role) {
            $groupPermissions = $this->getPermissionJsonToArray($role->permissions);
        }
        if (!empty($user->permissions)) {
            $userPermission = $this->getPermissionJsonToArray(json_decode($user->permissions, true));
        }
        $userPermission = array_merge($userPermission, $groupPermissions);
        $cardWisePerm = [
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
        $index = $ind = $i = 1;
        if (count($allPermission) > 0) {
            $groupName = "";
            foreach ($allPermission as $group => $permission) {
                $subItems = [];
                if (count($permission) > 0) {
                    foreach ($permission as $per) {
                        $isSelected = false;
                        if (array_key_exists($per['permission'], $userPermission)) {
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
                if (array_key_exists($group, $cardWisePerm)) {
                    $groupName = $group;
                    $permissionArr[$group] = [
                        'id'                => $index,
                        'text'              => $cardWisePerm[$group],
                        'expanded'          => false,
                        'spriteCssClass'    => 'rootfolder',
                        'items'             => [$items]
                    ];
                    $index++;
                } else {
                    array_push($permissionArr[$groupName]['items'], $items);
                }
                $ind++;
            }
        }
        //dd($permissionArr);
        return $permissionArr;
    }

    public function autologin(Request $request, $id, $back_login = '')
    {
        try {
            if ($back_login) {
                $request->session()->forget('back_login_id');
            }
            $loginUser = Sentinel::getUser();
            $user_id = $loginUser ? $loginUser->id : 0;
            $user = Sentinel::findById($id);
            if ($user) {
                if (!$back_login) {
                    $request->session()->put('back_login_id', $user_id);
                    $request->session()->save();
                }

                Sentinel::login($user);
                if (Sentinel::check()) {
                    return redirect()->route('dashboard');
                }

                return redirect()->back();
            }
        } catch (Exception $e) {
            Log::info($e);
            session()->flash('error', 'User not found!');
            return redirect()->route('users.index');
        }
    }

    public function getUniqueFilename($fileInput, $destination)
    {
        $filename = $fileInput->getClientOriginalName();
        $i = 0;
        $path_parts = pathinfo($filename);
        $path_parts['filename'] = Str::slug($path_parts['filename'], '-');
        $filename = $path_parts['filename'];
        while (File::exists($destination . '/' . $filename . '.' . $path_parts['extension'])) {
            $filename = $path_parts['filename'] . '-' . $i;
            $i++;
        }
        return time() . '_' . $filename . '.' . $path_parts['extension'];
    }

    public function getImagePath($file_name = '')
    {
        if ($this->is_public) {
            $path = public_path($this->path);
        } else {
            $path = storage_path($this->path);
        }

        if (File::isDirectory($path) === false) {
            File::makeDirectory($path, 0777, true);
            $this->createIndexHtmlFile($path);
        }
        return $path . $file_name;
    }
}
