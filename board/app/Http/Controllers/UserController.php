<?php
/**********************************
 * 프로젝트명 : laravel_board
 * 디렉토리   : Controllers
 * 파일명     : UserController.php
 * 이력       :   v001 0526 CH.Bae new
**********************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    function login() {
        return view('login');
    }
    
    function loginpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'email'    => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))){
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user);

        if(Auth::check()) { // 인증작업이 성공했는지 체크해준다.(Auth::check)
            session($user->only('id')); // 세션에 인증된 회원 PK 등록
            return redirect()->intended(route('boards.index')); // 필요한 정보만 빼고 전부 삭제후 리다이렉트
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
        }

        
    }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        $user = User::create($data); // insert
        if(!$user) {
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $error = '잠시 후에 다시 회원가입을 시도해 주십시오.';
            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }
        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.');
    }

    function logout() {
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }
    
    function withdraw() {
        $id = session('id');
        $result = User::destroy($id);
        // return var_dump($result);
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }
    
    function usersedit() {
        $id = session('id');
        $user = User::find($id);
        return view('usersedit')->with('data', $user);
    }
    
    function usersupdate(Request $req ,$id) {
        
        

        $usera = User::where('email', $req->email)->first();
        if($usera){
            $error = '이미 존재하는 아이디 입니다..';
            $user = User::find($id);
            return redirect()->route('users.edit')->with('data', $user)->with('error', $error);
        }




        if(isset($req->password)){
            $validator = Validator::make(
                $req->only('password', 'name', 'email') // 필요한 값만 가져오는 방법
                ,[
                    'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
                    ,'email'    => 'required|email|max:100'
                    ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
                ]
            );
        }

        
        
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
        ]);


        $data['name'] = $req->name;
        $data['email'] = $req->email;
        if(isset($req->password)){
            $data['password'] = Hash::make($req->password);
        }


        $result = User::find($id);
        $result->name = $data['name'];
        $result->email = $data['email'];
        if(isset($req->password)){
            $result->password = $data['password'];
        }
        $user = $result->save();
        
        // $user = User::save($data); // insert
        // return var_dump($id,$user);
        if(!$user) {
            $error = '시스템 에러가 발생하여, 정보수정에 실패했습니다.';
            $error = '잠시 후에 다시 정보수정을 시도해 주십시오.';
            return redirect()
                ->route('users.edit')
                ->with('error', $error);
        }
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '정보수정을 완료 했습니다.<br>수정하신 아이디와 비밀번호로 로그인 해 주십시오.');
            
    }
    
    
    
    
    
    
    
    
    
    
    
}
