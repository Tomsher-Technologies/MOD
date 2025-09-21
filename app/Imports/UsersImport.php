<?php

namespace App\Imports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\EventUserRole;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;


class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $password = '123456';
            $event_code = trim($row['event_code']);
            $username = trim($row['username']);
            $name = trim($row['name']) ?? NULL;
            $email = trim($row['email']) ?? NULL;
            $phone = trim($row['phone']) ?? NULL;
            $module = strtolower(trim($row['module'])) ?? NULL;
            $role = trim($row['role']) ?? NULL;
            $eventCheck  = Event::where('code', $event_code)->first();
            $eventId = $eventCheck->id ?? NULL;
            if($username != NULL || $username != ''){
                $checkUser = User::where('username', $username)->first();
                if($checkUser){
                    $checkUser->update([
                        'name'            => $name,
                        'email'           => $email,
                        'user_type'       => $module,
                        'phone'           => $phone
                    ]);

                    $checkRole = Role::where('name', $role)->first();
                    if($checkRole){
                        $checkUser->assignRole($role);
                        if($module != 'admin'){
                            $alreadyAssignedEvent = EventUserRole::where('user_id', $checkUser->id)
                                                    ->where('event_id', $eventId)->first();

                            if($alreadyAssignedEvent){
                                $alreadyAssignedEvent->update([
                                    'role_id'   => $checkRole->id,
                                    'module'    => $module
                                ]);
                            }else{
                                EventUserRole::create([
                                    'event_id'  => $eventId, 
                                    'user_id'   => $checkUser->id,
                                    'module'    => $module,
                                    'role_id'   => $checkRole->id,
                                ]);
                            }
                        }
                    }
                }else{
                    if($module == 'admin'){
                        $user_type = 'staff';
                    }else{
                        $user_type = $module;
                    }
                    $user =  new User([
                                        'user_type'       => $user_type,
                                        'username'        => $username,
                                        'name'            => $name,
                                        'email'           => $email,
                                        'password'        => Hash::make($password),
                                        'force_password'  => 1,
                                        'phone'           => $phone
                                    ]);

                    if($user->save()){
                        $checkRole = Role::where('name', $role)->first();

                        if($checkRole){
                            $user->assignRole($role);

                            if($module != 'admin'){
                                
                                if($eventCheck){
                                    $roleCheck = Role::where('name', $role)->where('module', $module)->first();

                                    if ($roleCheck) {
                                        EventUserRole::create([
                                            'event_id'  => $eventId, 
                                            'user_id'   => $user->id,
                                            'module'    => $module,
                                            'role_id'   => $roleCheck->id,
                                        ]);
                                    }
                                }
                            }
                        }  
                    }
                }
            }
        }
    }
}
