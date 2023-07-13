<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Roles};
use Illuminate\Support\Facades\{Auth, DB, Hash, Validator};

class RolesController extends Controller
{

    public function update(Request $term) {
        $validate = Validator::make($term->all(), [
            'id'        => 'required|exists:roles,id',
            'name'      => 'required|string|min:3|max:100|unique:roles,role_name,'.$term->id.''
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $update = Roles::where('id','=',$term->id)->update([
            'role_name' => $term->name
        ]);
        if($update) {
            return response()->json([
                'error'         => false,
                'message'       => 'Successfully update role.'
            ], 200);
        }
        return response()->json([
            'error'    => true,
            'message'   => "Failed update role."
        ], 500);
    }

    public function delete(Request $term) {
        $validate = Validator::make($term->all(), [
            'id'      => 'required|exists:roles,id'
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $find = Roles::where('id','=',$term->id)->first();
        if($find) {
            $find->delete();
            return response()->json([
                'error'         => false,
                'message'       => 'Successfully delete role.'
            ], 200);
        }
        return response()->json([
            'error'    => true,
            'message'   => "Failed delete role."
        ], 500);
    }

    public function add(Request $term) {
        $validate = Validator::make($term->all(), [
            'name'      => 'required|string|min:3|max:100|unique:roles,role_name'
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $create = Roles::create([
            'role_name' => $term->name
        ]);
        if($create) {
            return response()->json([
                'error'         => false,
                'message'       => 'Successfully add new role.'
            ], 200);
        }
        return response()->json([
            'error'    => true,
            'message'   => "Failed add new role."
        ], 500);

    }
    
}
