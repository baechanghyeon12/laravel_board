<?php
/**********************************
 * 프로젝트명 : laravel_board
 * 디렉토리   : Controllers
 * 파일명     : BoardsController.php
 * 이력       :   v001 0526 CH.Bae new
 *                v002 0530 CH.Bae 유효성 체크 추가
**********************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 로그인 체크
        if(auth()->guest()) {
            return redirect()->route('users.login');
        }

        $result = Boards::select(['id', 'title', 'hits','created_at','updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // 유효성체크
        // v002 add start
        $req->validate([
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        // v002 add end

        
        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id); // 실패하면 불린형으로 false로 돌아온다
        $boards->hits++;
        $boards->save();
        
        return view('detail')->with('data',Boards::findOrFail($id)); // 실패하면 404페이지로 넘어간다.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::findOrFail($id);
        return view('edit')->with('data', $boards);
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
        // 유효성체크
        // ********** v002 add start **********

        // ID를 리퀘스크객체에 머지
        $arr = ['id' => $id];
        // $req->merge($arr);
        $request->request->add($arr);
        // $request->validate([
        //     'title' => 'required|between:3,30' // v002 add
        //     ,'content' => 'required|max:2000'
        //     ,'id' => 'required|exists.boards|integer'
        // ]);


        // 유효성 검사 방법 2
        $validator = Validator::make(
            $request->only('id', 'title', 'content') // 필요한 값만 가져오는 방법
            ,[
                'id' => 'required|integer'
                ,'title' => 'required|between:3,30'
                ,'content' => 'required|max:2000'
            ]
        );

        if($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->only('title', 'content'));
        }












        // $validator = Validator::make(['id' -> $id],[
        //     'id' => 'required|exists.boards|integer'
        // ]);
        // if($validator->fails()){
        //     return view('edit')->withErrors($validator);
        // }
        // ********** v002 add end **********

        // $boards = Boards::where('id', $id)->update(['title' => $req->input('title'),'content' => $req->input('content')]);
        // $boards = Boards::findOrFail($id);

        // return view('detail')->with('data', Boards::findOrFail($id));

    // 쌤이랑 한거(ORM을 이용한)
    $result = Boards::find($id);
    $result->title = $request->title;
    $result->content = $request->content;
    $result->save();

    // return redirect('/boards/'.$id);
    return redirect()->route('boards.show',['board' => $id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Boards::destroy($id); destroy는 인수로 무조건PK를 받아야 된다.


        // $board = Boards::find($id);
        // $board->delete();
        
        Boards::where('id', $id)->firstOrFail()->delete();
        return redirect('/boards');
    }
}
