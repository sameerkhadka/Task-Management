<?php

namespace App\Http\Controllers;

use App\Models\Company;

use App\Models\Task;

use App\Models\user;

use Illuminate\Http\Request;

use App\Http\Requests\Tasks\CreateTasksRequest;

use App\Http\Requests\Tasks\UpdateTaskRequest;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('verify.admin', ['except' => ['index', 'show', 'email_proofing', 'completed']]);
    }

    public function index()
    {
        $completed = Task::select('*')
        ->where('completed', '=', 0)
        ->where('email_proofing', '=', 0)  
        ->orderBy(DB::raw('ISNULL(priority), priority'), 'ASC')
        ->orderBy('priority', 'asc')
        ->orderBy('assigned_date', 'desc') 
        ->get();
 
       
       return view('project.tasks')->with('companies', Company::orderBy('name', 'ASC')->get())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'title' => 'required',
            'company' => 'required',
            'image.*' => 'mimes:jpg,jpeg,png,pdf|max:20000'
          ],[
            'image.*.mimes' => 'Only jpeg, png, jpg ,pdf images are allowed',
            'image.*.max' => 'Sorry! Maximum allowed size for an image is 20MB',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->department = $request->department;
        if($assigned = $request->assigned_to)
        {
        $task->assigned_to = json_encode($assigned);
        }
        else
        {
            $task->assigned_to = '[]' ; 
        }
        $task->contact_person = $request->contact_person;
        $task->priority = $request->priority;        
        $task->company_id = $request->company;
        $task->assigned_date = $request->assigned_date;
        $task->deadline = $request->deadline;
        $task->contact_number = $request->contact_number;
        $task->contact_email = $request->contact_email;

        //upload the image to the storage
        if($request->hasFile('image'))
        {

            foreach($request->file('image') as $file) 
            {

                $name = $file->getClientOriginalName();
                $filename = pathinfo($name, PATHINFO_FILENAME);
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $i = 1;
                while(file_exists( public_path().'/storage/tasks/'. $name )) 
                {                           
                  $name = $filename."(".$i.")".".".$extension;
                  $i++;
                }                 
                $file->storeAs('tasks', $name);

                $imgData[] = [
                    "name" => $name,
                    "caption" => ''
                ];

            }
        }
            if(isset($imgData))
            {
            $task->image = json_encode($imgData);
            }
        
        $task->save();
        if(isset($request->submit_return)){
            return redirect(route('tasks.edit',$task->id));
        }

       session()->flash('success', 'Task Created successfully'); 

       $completed = Task::select('*')
       ->where('completed', '=', 0)
       ->where('email_proofing', '=', 0)
       ->orderBy(DB::raw('ISNULL(priority), priority'), 'ASC')
       ->orderBy('priority', 'asc')
       ->orderBy('assigned_date', 'desc') 
       ->get();
       
       return redirect()->route('index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Id)
    {   
        $task = Task::all()->where('id', $Id)->first();
        return view('project.viewtask')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->with('task', $task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('project.tasks')->with('companies', Company::orderBy('name', 'ASC')->get())->with('users', User::orderBy('name', 'ASC')->get())->with('task', $task);
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
        $request->validate([
            'image.*' => 'mimes:jpg,jpeg,png,pdf|max:20000'
          ],[
             'title' => 'required',
            'company' => 'required',
            'image.*.mimes' => 'Only jpeg, png, jpg, pdf images are allowed',
            'image.*.max' => 'Sorry! Maximum allowed size for an image is 20MB',
                ]);

        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->department = $request->department;
        if(isset($request->assigned_to))
        {
        $task->assigned_to = json_encode($request->assigned_to);
        }
        else
        {
            $task->assigned_to = '[]' ; 
        }
        $task->contact_person = $request->contact_person;
        $task->priority = $request->priority;
        $task->company_id = $request->company;
        $task->assigned_date = $request->assigned_date;
        $task->deadline = $request->deadline;
        $task->contact_number = $request->contact_number;
        $task->contact_email = $request->contact_email;
        $images = $task->image ? json_decode($task->image, true) : [];
        if(count($images)>0)
        {
            for($i = 0; $i<count($images); $i++)
            {
                $images[$i]['caption'] = $request->captions[$i];
            }
        }
        if($files=$request->file('image'))
        {
            foreach($files as $file)
            {
               $name = $file->getClientOriginalName();
                $filename = pathinfo($name, PATHINFO_FILENAME);
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                
                $i = 1;
                while(file_exists( public_path().'/storage/tasks/'. $name )) 
                {                           
                  $name = $filename."(".$i.")".".".$extension;
                  $i++;
                }                 
                $file->storeAs('tasks', $name);
                $images[] = [
                    "name" => $name,
                    "caption" => ''
                ];        
            }

        }

            if(isset($images))
            {
                $task->image = json_encode($images);
            }

            $task->update();

            if(isset($request->submit_return)){
                return redirect(route('tasks.edit',$task->id));
            }
        
        //flash message

        session()->flash('success', 'Task updated successfully');

        //redirect user
        $completed = Task::select('*')
            ->where('completed', '=', 0)
            ->where('email_proofing', '=', 0)
            ->orderBy('priority', 'asc')
            ->orderBy('assigned_date', 'desc')
            ->get();    
       
        return redirect()->route('tasks.show', $task->id)->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {   
        if(!$task->image == '')
        {
            foreach (json_decode($task->image, true) as $images) 
            {
                unlink(public_path().'/storage'.'/tasks'.'/'.$images['name']);

            } 
        }  
            $task->delete();   
            session()->flash('success', 'Task deleted successfully');   
            $completed = Task::select('*')
            ->where('completed', '=', 0)
            ->where('email_proofing', '=', 0)
            ->orderBy(DB::raw('ISNULL(priority), priority'), 'ASC')
            ->orderBy('priority', 'asc')
            ->orderBy('assigned_date', 'DESC')
            ->get();    
            return view('project.index')->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);
    }

    public function completed(Task $task)
    {   
        $completed = DB::select('select * from tasks where completed = ?', ['true']);
 
        if($task->completed == false)
        {
            $task->completed = true;
            $task->update([
                'completed' => $task->completed,
                'completed_at' => now()
                ]);
         }

        return back()->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);

        
    }
    
    public function email_proofing(Task $task)
    {   
        $completed = DB::select('select * from tasks where email_proofing = ?', ['true']);
 
        if($task->email_proofing == false)
        {
            $task->email_proofing = true;
            $task->update([
                'email_proofing' => $task->email_proofing,
                'email_proofed_at' => now()
                ]);
         }

        return back()->with('companies', Company::all())->with('users', User::orderBy('name', 'ASC')->get())->withTasks($completed);

        
    }

    public function deleteImage(Request $request)
    {
        $task = Task::find($request->taskID);
        $imageArray = json_decode($task->image);
       

        // deleting image from array $imageName stores array of only image which will be deleted but, imageArray is new array without that image
        $imageName = array_splice($imageArray, $request->arrayID-1, 1);
         
        // deleting image from storage
        if(file_exists(public_path().'/storage'.'/tasks'.'/'.$imageName[0]->name)){
            unlink(public_path().'/storage'.'/tasks'.'/'.$imageName[0]->name);
        }

        $task->image = json_encode($imageArray);
        $task->update();

        return 'done';
    }
   
}