<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Company;

use App\Models\user;

use App\Models\Task;

use PDF;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class projectcontroller extends Controller
{
    public function index()
    
    {  
        if(auth()->user()->isAdmin())
        {
                $completed = Task::select('*')
                ->where('completed', '=', 0)
                ->where('email_proofing', '=', 0)
                ->orderBy(DB::raw('ISNULL(priority), priority'), 'ASC')
                ->orderBy('priority', 'asc')
                ->orderBy('assigned_date', 'desc')
                ->get();
        }

        else
        { 
                 $user_id = Auth::id();
                 $completed = Task::select('*')
                ->where('assigned_to','like', "%\"{$user_id}\"%")
                ->where('completed', '=', 0)
                ->where('email_proofing', '=', 0)
                ->orderBy('priority', 'asc')
                ->orderBy('assigned_date', 'desc')
                ->get();  
            
        }

        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    } 

    public function login()
    {
        return view('project.new-login');
    }

    public function companytask($company_name)
    {   
        $completed = Task::select('*')
        ->where('company_id', '=', [$company_name])
        ->get();
      

        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }

    public function design()
    {
        $completed = Task::select('*')
        ->where('department', '=', ['design'])
        ->where('completed', '=', 0)
        ->where('email_proofing', '=', 0)
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();

        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }
    
    public function web()
    {   
        $completed = Task::select('*')
        ->where('department', '=', ['web'])
        ->where('completed', '=', 0)
        ->where('email_proofing', '=', 0)
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();
        
        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }
    
    public function print()
    {   
        $completed = Task::select('*')
        ->where('department', '=', ['print'])
        ->where('completed', '=', 0)
        ->where('email_proofing', '=', 0)
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();
    
        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }

    public function user_task($user_id)
    {   
       
        $completed = Task::select('*')
        ->where('assigned_to','like', "%\"{$user_id}\"%")
        ->where('completed', '=', 0)
        ->where('email_proofing', '=', 0)
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();       

        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }
    
     public function email_proofing()
    {
        if(auth()->user()->isAdmin())
        {
        $completed = Task::select('*')
        ->where('completed', '=', 0)
        ->where('email_proofing', '=', 1)
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();
        }
        else
        { 
        $user_id = Auth::id();
        $completed = Task::select('*')
        ->where('completed', '=', 0)
        ->where('email_proofing', '=', 1)
        ->where('assigned_to','like', "%\"{$user_id}\"%") 
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();
        }
        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }

    public function completed()
    {
        if(auth()->user()->isAdmin())
        {
        $completed = Task::select('*')
        ->where('completed', '=', 1)
        ->where('email_proofing', '=', 1)
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();
        }
        else
        { 
        $user_id = Auth::id();
        $completed = Task::select('*')
        ->where('completed', '=', 1)
        ->where('email_proofing', '=', 1)
        ->where('assigned_to','like', "%\"{$user_id}\"%")    
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc')
        ->get();      
        } 
        return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }

    public function register()
    {
        return view('project.register')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get());
    }

    public function createPDF(Request $request) {

         $from = $request->from;
        $to = $request->to;
        $user = $request->assigned_to;
        
        if(isset($request->completed)){
            $tasks = Task::select('*')
            ->where('completed', '=', 1)        
            ->where('completed_at', '>=', $from)
            ->where('completed_at', '<=', $to)  
            ->where('assigned_to','like', "%\"{$user}\"%")             
            ->get();
        }
        else
        {
            $tasks = Task::select('*')
            ->where('assigned_to','like', "%\"{$user}\"%")
            ->where('email_proofed_at', '>=', $from)
            ->where('email_proofed_at', '<=', $to)
            ->where('email_proofing', '=', 1)
            ->where('completed', '=', 0)
            ->get();
        }

         // share data to view
        $count = $tasks->count();
        $pdf = PDF::loadView('pdf_view', compact('tasks','count','user'));
        // download PDF file with download method
        return $pdf->download('Tasks.pdf');

      }
    
}   