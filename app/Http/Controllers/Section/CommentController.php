<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherComment;
use App\Models\HeadTeacherComment;

class CommentController extends Controller
{
    public function index()
    {
        return view('section.comment.index',[
            'teacherComments'=>TeacherComment::all(),
            'headTeacherComments'=>HeadTeacherComment::all()
        ]);
    }

    public function view()
    {
        return view('section.comment.view',[
            'teacherComments'=>TeacherComment::all(),
            'headTeacherComments'=>HeadTeacherComment::all()
        ]);
    }

    public function addComment(Request $request)
    {
        $request->validate(['comment'=>'required','type'=>'required']);
        if($request->type == 1){
            TeacherComment::firstOrCreate(['name'=>$request->comment,'gender'=>$request->gender]);
        }else{
            HeadTeacherComment::firstOrCreate(['name'=>$request->comment,'gender'=>$request->gender]);
        }
        return redirect()->route('dashboard.comment.view')->withSuccess('Comment Added');
    }
    

    public function updateTeacherComment (Request $request, $teacherCommentId)
    {
        $comment = TeacherComment::find($teacherCommentId);
        $comment->update(['name'=>$request->name,'gender'=>$request->gender]);
        return redirect()->route('dashboard.comment.view')->withSuccess('Teacher Comment Updated');
    }

    public function updateHeadTeacherComment (Request $request, $headTeacherCommentId)
    {
        $comment = HeadTeacherComment::find($headTeacherCommentId);
        $comment->update(['name'=>$request->name,'gender'=>$request->gender]);
        return redirect()->route('dashboard.comment.view')->withSuccess('Head Teacher Comment Updated');
    }

    public function deleteTeacherComment ($teacherCommentId)
    {
        $comment = TeacherComment::find($teacherCommentId);
        $comment->delete();
        return redirect()->route('dashboard.comment.view')->withSuccess('Teacher Comment Deleted');
    }

    public function deleteHeadTeacherComment ($headTeacherCommentId)
    {
        $comment = HeadTeacherComment::find($headTeacherCommentId);
        $comment->delete();
        return redirect()->route('dashboard.comment.view')->withSuccess('Head Teacher Comment Deleted');
    }
}
