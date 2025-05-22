<?php

namespace App\Http\Controllers;

use App\Models\PermissionModel;
use App\Models\PermissionRoleModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function list(){
        $permissionRole = PermissionRoleModel::getPermission('Role', Auth::user()->role_id);
        if(empty($permissionRole)){
            abort(404);
        }

        $data['permissionAdd'] = PermissionRoleModel::getPermission('AddRole', Auth::user()->role_id);
        $data['permissionEdit'] = PermissionRoleModel::getPermission('EditRole', Auth::user()->role_id);
        $data['permissionDelete'] = PermissionRoleModel::getPermission('DeleteRole', Auth::user()->role_id);

        $data['getRecord'] = RoleModel::getRecord();
        return view('panel.role.list', $data);
    }

    public function add(){
        $permissionRole = PermissionRoleModel::getPermission('AddRole', Auth::user()->role_id);
        if(empty($permissionRole)){
            abort(404);
        }

        $getPermission = PermissionModel::getRecord();
        $data['getPermission'] = $getPermission;

        return view('panel.role.add', $data);
    }

    public function insert(Request $request){
        $permissionRole = PermissionRoleModel::getPermission('AddRole', Auth::user()->role_id);
        if(empty($permissionRole)){
            abort(404);
        }

        $save = new RoleModel;
        $save->name = $request->name;
        $save->save();

        PermissionRoleModel::InsertUpdateRecord($request->permission_id, $save->id);

        return redirect('panel/role')->with('success', 'Role Successfully created');
    }

    public function edit($id){
        $permissionRole = PermissionRoleModel::getPermission('EditRole', Auth::user()->role_id);
        if(empty($permissionRole)){
            abort(404);
        }
        $data['getRecord'] = RoleModel::getSingle($id);
        $data['getPermission'] = PermissionModel::getRecord();
        $data['getPermissionRole'] = PermissionRoleModel::getPermissionRole($id);
        // dd($data['getPermissionRole']);
        return view('panel.role.edit', $data);
    }

    public function update($id, Request $request){
        $permissionRole = PermissionRoleModel::getPermission('EditRole', Auth::user()->role_id);
        if(empty($permissionRole)){
            abort(404);
        }

        $save = RoleModel::getSingle($id);

        $save->name = $request->name;
        $save->save();

        PermissionRoleModel::InsertUpdateRecord($request->permission_id, $save->id);

        return redirect('panel/role')->with('success', 'Role Successfully updated');
    }

    public function delete($id){
        $permissionRole = PermissionRoleModel::getPermission('DeleteRole', Auth::user()->role_id);
        if(empty($permissionRole)){
            abort(404);
        }

        $save = RoleModel::getSingle($id);
        $save->delete();

        return redirect('panel/role')->with('success', 'Role Successfully deleted');
    }
}
