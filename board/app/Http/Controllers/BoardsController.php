<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function update(Request $req, $id)
    {
        // $boards = Boards::where('id', $id)->update(['title' => $req->input('title'),'content' => $req->input('content')]);
        // $boards = Boards::findOrFail($id);

        // return view('detail')->with('data', Boards::findOrFail($id));

    // 쌤이랑 한거(ORM을 이용한)
    $result = Boards::find($id);
    $result->title = $req->title;
    $result->content = $req->content;
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
