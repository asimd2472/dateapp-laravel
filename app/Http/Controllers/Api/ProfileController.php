<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interests;
use App\Models\ProfileImage;
use App\Models\User;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function register_update_profile(Request $request){

        // print_r($request->all());exit;

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'occupation' => 'required|string|max:255',
        //     'city_id' => 'required',
        //     'gender' => 'required|in:male,female,other',
        //     'dob' => 'required|date',
        //     'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }

        $user = auth()->user();

        // dd($request->occupation);

        $avatarPath = $user->avatar;

        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');

            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/avatars'), $filename);

            $avatarPath = 'uploads/avatars/' . $filename;
        }

        User::where('id', $user->id)->update([
            'name' => $request->name,
            'occupation' => $request->occupation,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'dob' => $request->dob,
        ]);

        ProfileImage::create([
            'user_id' => $user->id,
            'avatar' => $avatarPath,
        ]);



        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => '',
        ]);

    }

    public function Interests_update(Request $request){
        
        $user = auth()->user();
        Interests::where('user_id', $user->id)->delete();
        foreach ($request->interests as $interest) {
            Interests::create([
                'user_id' => $user->id,
                'name' => $interest,
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Interests saved successfully'
        ]);
    }

    public function index(Request $request)
    {
        $authUser = auth()->user();

        $users = User::with(['profileImages', 'interests'])
            ->where('id', '!=', $authUser->id)
            ->get();

        $data = $users->map(function ($user) use ($authUser) {

            // Check existing action
            $action = UserAction::where('from_user_id', $authUser->id)
                ->where('to_user_id', $user->id)
                ->first();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'occupation' => $user->occupation,
                'gender' => $user->gender,
                'age' => $user->dob
                    ? Carbon::parse($user->dob)->age
                    : null,

                'images' => $user->profileImages->map(function ($img) {
                    return asset($img->avatar);
                }),

                'interests' => $user->interests->pluck('name'),

                'action' => $action ? $action->action : null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Profiles fetched successfully',
            'data' => $data,
        ]);
    }
}
