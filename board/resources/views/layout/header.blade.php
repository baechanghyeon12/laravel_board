<h2>header</h2>

{{-- 로그인 중일때 (인증이 됬을때) --}}
@auth
    <div><a href="{{route('users.logout')}}">로그아웃</a></div>
    <div><a href="{{route('users.edit')}}">정보수정</a></div>
@endauth
{{-- 비로그인 상태 (인증이 안됬을때) --}}
@guest
    <div><a href="{{route('users.login')}}">로그인</a></div>
@endguest
<hr>