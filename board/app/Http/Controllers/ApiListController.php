<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json($board, 200);
    }


    function postlist(Request $req) {
        // 유효성 체크 필요
        
        
        $boards = new Boards([
            'title' => $req->title
            ,'content' =>$req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');
        
        return $arr;
    }

    function putlist(Request $request, $id) {

        $arrData = [
            'code'      => '0'
            ,'msg'      => ''
        ];
        
        $data =$request->only('title', 'content');
        $data['id'] = $id;
        
        // 유효성체크
        $validator = Validator::make(
            $data // 필요한 값만 가져오는 방법
            ,[
                'id' => 'required|integer|exists:boards'
                ,'title' => 'required|between:3,30'
                ,'content' => 'required|max:2000'
            ]
        );
        if($validator->fails()){
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();
            return $arrData;
        }else {
            $board = Boards::find($id);
            $board->title = $request->title;
            $board->content = $request->content;
            $board->save();
            $arrData['code'] = '0';
            $arrData['msg'] = 'Success';
        }
        return $arrData;
    }

    function deletelist($id) {
        $idData = ['id' => $id];
        $validator = Validator::make(
            $idData // 필요한 값만 가져오는 방법
            ,[
                'id' => 'required|integer|exists:boards'
            ]);
            if($validator->fails()){
                $arrData['code'] = 'E01';
                $arrData['msg'] = 'Validate Error';
                $arrData['errmsg'] = $validator->errors()->all();
                return $arrData;
            }else {
                $result = Boards::where('id', $id)->firstOrFail()->delete();
                    $arr['errorcode'] = '0';
                    $arr['msg'] = 'success';
                    return $arr;
            }
    }    
}
