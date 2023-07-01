<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\contracts\ApiController;
use App\repositories\Contracts\UserRepositoryInterface;

class UsersController extends ApiController
{
    public function __construct(private UserRepositoryInterface $userRepository) {

    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'password' => 'required'
        ]);
        
        $this->userRepository->create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => app('hash')->make($request->password)
        ]);

        return $this->respondCreated('کاربر با موفقیت ایجاد شد',[
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password
        ]);

    }

    public function updateInfo(Request $request)
    {
        $this->validate($request,[
            'id' => 'required|string',
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string'
        ]);

        $this->userRepository->update($request->id,[
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile
        ]);

        return $this->respondSuccess('کاربر با موفقیت بروز رسانی شد!',[
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password
        ]);
    }
}