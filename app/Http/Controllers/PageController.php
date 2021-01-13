<?php

namespace App\Http\Controllers;
use App\Models\Human;

use Illuminate\Http\Request;

class PageController extends Controller
{

    function home_page(){
        
        return view('app');
    }
    // function get_forms(){
    //     return view('forms/form');
    // }
    // function get_all(Request $request){
    //     if (is_null($request->address)) {
    //         $humans = Human::all();
    //         return view('forms/table',['humans'=>$humans]);
    //     } else {
    //         $humans = Human::findOrFail($request->address);
    //         return view('forms/table',['humans'=>[$humans]]);
    //     }
        

       
    // }

    // function get_edit(Request $request){
    //     $humans = Human::findOrFail($request->id);
    //     return view('forms/edit_form',['human'=>$humans]);
    // }

    // function filterLocation(Request $request){
       
    //    return  redirect('/all')->with("message",$request->address);
    // }




}
