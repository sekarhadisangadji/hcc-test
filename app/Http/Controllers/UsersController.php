<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User};
use Illuminate\Support\Facades\{Auth, DB, Hash, Validator};


class UsersController extends Controller
{

    public function update(Request $term) {
        $validate = Validator::make($term->all(), [
            'id'        => 'required|exists:users,id',
            'name'      => 'required|string|min:5|max:100',
            'password'  => 'required|string|confirmed|min:6|max:50',
            'role'      => 'required|string|exists:roles,role_name'
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $update = User::where('id','=',$term->id)->update([
            'name'      => $term->name,
            'role'      => $term->role,
            'password'  => bcrypt($term->password)
        ]);
        if($update) {
            return response()->json([
                'error'         => false,
                'message'       => 'Successfully update user.'
            ], 200);
        }
        return response()->json([
            'error'    => true,
            'message'   => "Failed update user."
        ], 500);
    }

    public function add(Request $term) {
        $validate = Validator::make($term->all(), [
            'name'  => 'required|string|min:5|max:100',
            'email' => 'required|string|email|unique:users,email|max:100',
            'password' => 'required|string|confirmed|min:6|max:50',
            'role'     => 'required|string|exists:roles,role_name'
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $create = User::create([
            'name'      => $term->name,
            'email'     => $term->email,
            'role'      => $term->role,
            'password'  => bcrypt($term->password)
        ]);
        if($create) {
            return response()->json([
                'error'         => false,
                'message'       => 'Successfully add new user.'
            ], 200);
        }
        return response()->json([
            'error'    => true,
            'message'   => "Failed add new user."
        ], 500);
    }

    public function delete(Request $term) {
        $validate = Validator::make($term->all(), [
            'id' => 'required'
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $find = User::where('id','=',$term->id)->first();
        if($find) {
            $find->delete();
            return response()->json([
                'error'         => false,
                'message'       => 'Successfully delete user.'
            ], 200);
        }
        return response()->json([
            'error'    => true,
            'message'   => "Failed delete user."
        ], 500);
    }

    public function detail(Request $term) {
        $validate = Validator::make($term->all(), [
            'id' => 'required'
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $user = User::select('id','name','email','role','last_login','created_at','updated_at')
        ->where('id','=',$term->id)->first();
        if(!$user) {
            return response()->json([
                'error'     => true,
                'message'   => "User not found.",
                'data'      => null
            ], 404);
        }
        return response()->json([
            'error'         => false,
            'message'       => 'Successfully.',
            'data'          => $user
        ], 200);

    }

    public function list(Request $term) {
        $validate = Validator::make($term->all(), [
            'search' => 'sometimes|nullable|string'
        ]);
        if ($validate->fails()) { 
            return response()->json([
                'error'    => true,
                'message'   => $validate->errors()->all()[0]
            ], 400);
        }
        $search = $term->search ? $term->search : '';
        $query = User::query();
        $query->when($search != null, function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
        });
        $result = $query->orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'error'         => false,
            'message'       => 'Successfully.',
            'data'          => $result->toArray()['data'],
            'pagination' => [
                'total'        => (int)$result->total(),
                'count'        => (int)$result->count(),
                'per_page'     => (int)$result->perPage(),
                'current_page' => (int)$result->currentPage(),
                'total_pages'  => (int)$result->lastPage()
            ]
        ], 200);
    }


}
