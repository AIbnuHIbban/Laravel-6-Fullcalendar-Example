<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Redirect,Response;

class FullCalendarController extends Controller
{
    public function index(){
        if(request()->ajax()) {

            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');

            $data = Event::whereDate('mulai', '>=', $start)->whereDate('akhir',   '<=', $end)->get(['id','judul','mulai', 'akhir']);

            return Response::json($data);
        }

        return view('fullcalender');
    }


    public function create(Request $request)
    {  
        $insertArr = [  'judul' => $request->title,
                        'mulai' => $request->start,
                        'akhir' => $request->end
                    ];
        $event = Event::insert($insertArr);   
        return Response::json($event);
    }


    public function update(Request $request)
    {   
        $where = array('id' => $request->id);
        $updateArr = ['judul' => $request->title,'mulai' => $request->start, 'akhir' => $request->end];
        $event  = Event::where($where)->update($updateArr);

        return Response::json($event);
    } 

    public function destroy(Request $request)
    {
        $event = Event::where('id',$request->id)->delete();

        return Response::json($event);
    }   
}
