<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\CustomPermission;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;
    
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_roles', ['only' => ['index']]);
        $this->middleware('permission:add_role',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_role',  ['only' => ['edit','update']]);
        $this->middleware('permission:view_role', ['only' => ['index']]);
    }
    
    public function index(Request $request)
    {
        $request->session()->put('roles_last_url', url()->full());
        $search = $request->has('search') ? $request->search : '';
        $module = $request->has('module') ? $request->module : '';
        $query = Role::where('is_active',1);

        if($search){
            $query->where('name', 'like','%' . $search . '%');
        }

        if($module){
            $query->where('module', $module);
        }

        $roles = $query->orderBy('id','DESC')->paginate(15);

        return view('admin.roles_permissions.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $permission = CustomPermission::whereNull('parent_id')->where('module','admin')
                        ->with(['children' => function ($q) {
                            $q->where('is_active', 1);
                        }])->where('is_active',1)->get();
        return view('admin.roles_permissions.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
            'module' => 'required',
        ], [
            'name.required'     => __db('role_name_required'),
            'name.unique'       => __db('name_unique'),
            'permissions.required' => __db('permission_required'),
            'module.required' => __db('module_required'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = Role::create(['name' => $request->input('name'), 'module' => $request->input('module')]);
        $role->givePermissionTo($request->permissions);
        
        session()->flash('success', __db('role').__db('created_successfully'));
        return redirect()->route('roles.index');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = CustomPermission::whereNull('parent_id')->where('module', $role->module)
                        ->with(['children' => function ($q) {
                            $q->where('is_active', 1);
                        }])->where('is_active',1)->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('admin.roles_permissions.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,'.$id,
            'permissions' => 'required',
            'module' => 'required',
        ], [
            'name.required'     => __db('role_name_required'),
            'name.unique'       => __db('name_unique'),
            'permissions.required' => __db('permission_required'),
            'module.required' => __db('module_required'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->module = $request->input('module');
        $role->save();
    
        $role->syncPermissions($request->input('permissions'));

        session()->flash('success', __db('role').__db('updated_successfully'));
        return redirect()->route('roles.index');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('status',trans('messages.role_delete_success'));
    }

    public function getPermissionsByModule(Request $request)
    {
        $permissions = CustomPermission::where('module', $request->module)
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->where('is_active', 1);
            }])
            ->where('is_active', 1)
            ->get();

        return response()->json([
            'html' => view('admin.roles_permissions.module-permissions', compact('permissions'))->render()
        ]);
    }
    
    public function show($id)
    {
       return redirect()->route('roles.index');
    }
}