<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\EventUserRole;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Hash;

class StaffController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_staff',  ['only' => ['index','destroy']]);
        $this->middleware('permission:add_staff',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_staff',  ['only' => ['edit','update','updateStatus']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->put('staffs_last_url', url()->full());
        $sort_search = $request->has('search') ? $request->search : '';
        $role_id = $request->has('role_id') ? $request->role_id : '';
        $users = User::with(['eventUserRoles.event', 'eventUserRoles.role'])->orderBy('id','desc');
        
        if($sort_search){
            $users = $users->where(function ($query) use ($sort_search){
                        $query->where('name', 'like','%' . $sort_search . '%')
                            ->orWhere('email', 'like', '%' . $sort_search . '%')
                            ->orWhere('phone', 'like', '%' . $sort_search . '%')
                            ->orWhere('military_number', 'like', '%' . $sort_search . '%');
                    });
        }
        
        if ($role_id != '') {
            $users->whereHas('roles', function ($q) use ($role_id) {
                $q->where('name', $role_id);
            });
        }

        if($request->has('module') && $request->module != NULL){
            if($request->module === 'admin'){
                $users->where('user_type','staff');
            }else{
                $users->where('user_type', $request->module);
            }
        }else{
            $users->where('user_type','!=','admin');
        }
         // Filter by status
        if ($request->filled('status')) {
            // Assuming 1 = active, 2 = inactive; 
            if ($request->status == 1) {
                $users->where('banned', 0);
            } elseif ($request->status == 2) {
                $users->where('banned', 1);
            }
        }

        $users = $users->paginate(20);
       
        return view('admin.staffs.index', compact('users','sort_search','role_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('is_active', 1)->get();
        return view('admin.staffs.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required',
            'module' => 'required',
            'username' => 'required|alpha_num|unique:users,username|max:50',
        ], [
            'name.required' => __db('name_required'),
            'email.email' => __db('valid_email'),
            'mobile.max' => __db('mobile_max', ['max' => 20]), 
            'password.min' => __db('password_length', ['min' => 6]),
            'password.confirmed' => __db('new_password_confirmed'),
            'role.required' => __db('role_required'),
            'module.required' => __db('module_required'),
            'username.required' => __db('username_required'),
            'username.alpha_num' => __db('username_alpha_num'), 
            'username.unique' => __db('username_already_exist'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $password = ($request->password) ? $request->password : '123456';

        $user = new User;
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email ?? NULL;
        $user->phone = $request->mobile ?? NULL;
        $user->user_type = ($request->module === 'admin') ? 'staff' : $request->module;
        $user->force_password = $request->password ? 0 : 1;
        $user->password = Hash::make($password);
        if($user->save()){
            $user->assignRole($request->role);
            if($request->module != 'admin'){
                $role = Role::where('name', $request->role)->where('module', $request->module)->first();

                if ($role) {
                    EventUserRole::create([
                        'event_id'  => getDefaultEventId(), 
                        'user_id'   => $user->id,
                        'module'    => $request->module,
                        'role_id'   => $role->id,
                    ]);
                }
            }
            session()->flash('success', __db('staff').__db('created_successfully'));
            return redirect()->route('staffs.index');
        }

        session()->flash('error', __db('something_went_wrong'));
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff = User::findOrFail(base64_decode($id));
        if($staff->user_type === 'staff'){
            $module = 'admin';
        }else{
            $module = $staff->user_type;
        }
        $roles = Role::where('module', $module)->where('is_active', 1)->get(['name']);
        return view('admin.staffs.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
      
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required',
            'module' => 'required',
            'username' => 'required|alpha_num|max:50|unique:users,username,'.$user->id,
        ], [
            'name.required' => __db('name_required'),
            'email.email' => __db('valid_email'),
            'email.unique' => __db('email_already_exist'),
            'mobile.max' => __db('mobile_max', ['max' => 20]), 
            'password.min' => __db('password_length', ['min' => 6]),
            'password.confirmed' => __db('new_password_confirmed'),
            'role.required' => __db('role_required'),
            'module.required' => __db('module_required'),
            'username.required' => __db('username_required'),
            'username.alpha_num' => __db('username_alpha_num'),
            'username.unique' => __db('username_already_exist'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email ?? NULL;
        $user->phone = $request->mobile ?? NULL;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){

            $user->syncRoles([$request->role]);

            if($request->module != 'admin'){
                $role = Role::where('name', $request->role)->where('module', $request->module)->first();

                if ($role) {
                    EventUserRole::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'event_id' => getDefaultEventId(),
                            'module' => $request->module,
                        ],
                        [
                            'role_id' => $role->id,
                        ]
                    );
                }
            }
           
            session()->flash('success', __db('staff').__db('updated_successfully'));
            return redirect()->route('staffs.index');
        }

        session()->flash('error',);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        session()->flash('success','Staff deleted successfully');
        return redirect()->route('staffs.index');
    }

    public function updateStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        
        $user->banned = $request->status;
        $user->save();
       
        return 1;
    }

    public function getByModule($module)
    {
        $roles = Role::where('module', $module)->where('is_active', 1)->get(['name']);
        return response()->json($roles);
    }

    public function show($id)
    {
        return redirect()->route('staffs.index');
    }

    public function showForm()
    {
        return view('admin.staffs.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return back()->with('success', 'Users imported successfully!');
    }
}
