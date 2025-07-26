<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\EventUserRole;
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
        $users = User::orderBy('id','desc');
        
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
            'email' => 'required|email|unique:users,email',
            'mobile' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            'module' => 'required',
            'military_number' => 'required|unique:users,military_number',
        ], [
            'name.required' => __db('name_required'),
            'email.required' => __db('email_required'),
            'email.email' => __db('valid_email'),
            'email.unique' => __db('email_already_exist'),
            'mobile.max' => __db('mobile_max', ['max' => 20]), 
            'password.required' => __db('password_required'),
            'password.min' => __db('password_length', ['min' => 6]),
            'password.confirmed' => __db('new_password_confirmed'),
            'role.required' => __db('role_required'),
            'module.required' => __db('module_required'),
            'military_number.required' => __db('military_number_required'),
            'military_number.unique' => __db('military_number_already_exist'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(User::where('email', $request->email)->first() == null){
            $user = new User;
            $user->military_number = $request->military_number;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->mobile;
            $user->user_type = ($request->module === 'admin') ? 'staff' : $request->module;
            $user->password = Hash::make($request->password);
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
        }

        session()->flash('error', __db('email_already_exist'));
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
            'email' => 'required|email|unique:users,email,'.$user->id,
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required',
            'module' => 'required',
            'military_number' => 'required|unique:users,military_number,'.$user->id,
        ], [
            'name.required' => __db('name_required'),
            'email.required' => __db('email_required'),
            'email.email' => __db('valid_email'),
            'email.unique' => __db('email_already_exist'),
            'mobile.max' => __db('mobile_max', ['max' => 20]), 
            'password.min' => __db('password_length', ['min' => 6]),
            'password.confirmed' => __db('new_password_confirmed'),
            'role.required' => __db('role_required'),
            'module.required' => __db('module_required'),
            'military_number.required' => __db('military_number_required'),
            'military_number.unique' => __db('military_number_already_exist'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->military_number = $request->military_number;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->mobile;
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
}
