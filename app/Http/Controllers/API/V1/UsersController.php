<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\contracts\ApiController;
use App\repositories\Contracts\UserRepositoryInterface;

class UsersController extends ApiController
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function index(Request $request)
    {
        $this->validate($request,[
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric'
        ]);

        $users = $this->userRepository->paginate($request->search,$request->page,$request->pagesize??20,['full_name','mobile','email']);

        return $this->respondSuccess('کاربران',$users);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'password' => 'min:6|required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ]);

        $createdUser = $this->userRepository->create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => app('hash')->make($request->password)
        ]);

        return $this->respondCreated('کاربر با موفقیت ایجاد شد', [
            'full_name' => $createdUser->getFullName(),
            'email' => $createdUser->getEmail(),
            'mobile' => $createdUser->getMobile(),
            'password' => $createdUser->getPassword()
        ]);

    }

    public function updateInfo(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|string',
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string'
        ]);

        $user = $this->userRepository->update($request->id, [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile
        ]);

        return $this->respondSuccess('کاربر با موفقیت بروز رسانی شد!', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile()
        ]);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'password' => 'min:6|required_with:password_reapet|same:password_reapet|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'password_reapet' => 'min:6'
        ]);

        try {
            $user = $this->userRepository->update($request->id, [
                'password' => app('hash')->make($request->password)
            ]);
    
        } catch (\Exception $e) {
            return $this->respondInternalError('کاربر بروز رسانی نشد');
        }

       
        return $this->respondSuccess('رمز عبور شما با موفقیت بروز رسانی شد.', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        if (!$this->userRepository->find($request->id)) {
           return $this->respondNotFound('کاربری با این آیدی وجود ندارد');
        }

        if (!$this->userRepository->delete($request->id)) {
           return $this->respondInternalError('خطایی وجود دارد لطفا مجدد تلاش نمایید');
        }


        return $this->respondSuccess('کاربر با موفقیت حذف شد');
    }
}
