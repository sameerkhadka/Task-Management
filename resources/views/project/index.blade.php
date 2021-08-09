@extends('layouts.app')
@section('content')

        <div class="content">
            <div class="for-download">
            @if(auth()->user()->isAdmin())
                @if(Request::segment(1) === "completed")
                        <form action="{{ route('pdf') }}" method="GET">
                            @CSRF
            
                        <select name="assigned_to" >
                        @foreach($users as $user) 
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach                            
                        </select> 
            
                        From: <input type="text" class ="datepicker" name="from" autocomplete="off" required>
            
                        To: <input type="text" class ="datepicker" name="to" autocomplete="off" required>
                       
                        <button type="submit" class="btn btn-primary" value="1" name="completed">Export to PDF</button>
                     
                      </form>
                 @elseif(Request::segment(1) === "email_proofing")
                    <form action="{{ route('pdf') }}" method="GET">
                        @CSRF
                            <select name="assigned_to">
                            @foreach($users as $user) 
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach                            
                            </select> 
                    
                    From: <input type="text" class ="datepicker" name="from" autocomplete="off" required>
        
                    To: <input type="text" class ="datepicker" name="to" autocomplete="off" required>
                    
                    <button type="submit" name="submit" value="1" class="btn btn-primary">Export to PDF</button>
                  
                  </form>
                @endif
                  
            @endif
            </div>
            
            <div class="table-wrap">
                
                <!-- <h5 class='table-head'>Today</h5> -->
                <table class="list-task mydatatable">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Task</th>
                        <th>Company</th>
                        <th>Assigned To</th>
                        <th>Assigned Date</th>
                        <th>Contact Person</th>
                        <th>Priority</th>
                   </tr>
                </thead>
                <tbody>

                   @foreach($tasks as $task)
                   
                    <tr>                              
                        <td>{{ $loop->iteration }}</td>
                       
                        <td><a href="{{route('tasks.show', $task->id)}}"> {{ $task->title  }}  </a></td>
                       
                        <td>{{ $task->company->name}}</td>
                       
                       
                        <td>
                        @if(!$task->assigned_to == '')
                        @foreach(json_decode($task->assigned_to) as $assigned_id)<span>{{ \App\Models\User::find($assigned_id)->name }}</span>@endforeach
                        @endif
                        </td>

                        <td>{{ $task->assigned_date }} </td>
                        <td><span>{{ $task->contact_person }}</span></td>
                       
                        <td> 
                          @if($task->priority==1)
                               <span title="Urgent" class="pcircle urgent" ></span> <label class='phide'>4</label> 
                        
                            @elseif($task->priority==2)
                               <span title="High" class="pcircle high" ></span>  <label class='phide'>3</label>
                        
                            @elseif($task->priority==3)
                                <span title="Medium"  class="pcircle med" ></span> <label class='phide'>2</label>
                        
                            @elseif($task->priority==4)
                                <span title="Low" class="pcircle low" ></span> <label class='phide'>1</label>
                            @endif    
                        
                        </td>

                        
                        
                    </tr>
            
                    @endforeach
                    </tbody>
                    </table>
            </div>
           
        </div>

@endsection

@section('date')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
         $( function() {
             $( ".datepicker" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
         } );
  </script>
    @endsection