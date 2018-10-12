<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function users()
    {
        $users = User::all();
        return Datatables::of($users)->addColumn('action', function($users){
                    $show = null; //route('users.show', ['id' => $users->id]);
                    $edit = null; //route('users.edit', ['id' => $users->id]);
                    $delete = route('users.destroy', ['id' => $users->id]);
                    return '<a href="'.$show.'" title="show" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="'.$edit.'" title="edit" class="btn btn-success btn-sm"><i class="far fa-edit"></i></a>
                            <button title="delete" type="button" class="btn btn-danger btn-sm delete" url="'.$delete.'"><i class="far fa-trash-alt"></i></button>';
                })->make(true);
    }
}
