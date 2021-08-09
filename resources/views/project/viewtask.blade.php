@extends('layouts.app')
@section('content')
<div class="content">
            <div class="task-detail">
                <div class="tash-headwrap">
                    <ul class="td-head">
                         @if($task->completed == false)
                        <li>
                            Priority: 
                            @if($task->priority==1)
                                <span class="pcircle urgent" ></span> Urgent
                        
                            @elseif($task->priority==2)
                                <span class="pcircle high" ></span> High
                        
                            @elseif($task->priority==3)
                                <span class="pcircle med" ></span> Medium
                        
                            @elseif($task->priority==4)
                                <span class="pcircle low" ></span> Low
                            @endif               
                        </li>
                        @endif
                       
                      @if($task->completed == false)     
                        @if(auth()->user()->isAdmin())
                        <li>                       
                             <form action="{{ $task->email_proofing == true ? route('tasks.completed', $task->id) : route('tasks.email_proofing', $task->id) }}" method="POST">
                                 @csrf
                                 @method('PATCH')               
                                <button class="complete"><i class="far fa-check-circle"></i> <span>{{ $task->email_proofing == true ? "Mark as completed" : "Email Proofing"}}</span></button>
                            </form>
                        </li>
                        @else
                        <li>
                            @if(count(array_intersect([Auth::id()], json_decode($task->assigned_to))) > 0)
                                 <form action="{{ $task->email_proofing == true ? route('tasks.completed', $task->id) : route('tasks.email_proofing', $task->id) }}" method="POST">
                                     @csrf
                                     @method('PATCH')                            
                                     <button class="complete"><i class="far fa-check-circle"></i> <span>{{ $task->email_proofing == true ? "Mark as completed" : "Email Proofing"}}</span></button>
                                 </form>
                            @endif
                        @endif
                        </li>
                    @else
                        <li class="comp-box"> <i class="far fa-check-circle"></i> Task is completed</li>
                    @endif
                        
                        
                        <li> Deadline: {{ $task->deadline  }}  </li>
                        
                        @if($task->email_proofing == true)
                         <li> Email Proofed At: {{ $task->email_proofed_at  }}</li>
                        @endif
                        
                         @if($task->completed == true)
                         <li> Completed At: {{ $task->completed_at  }}</li>
                        @endif
                        
                       
                    </ul>
                   @if(auth()->user()->isAdmin())
                    <ul class='actions'>
                         @if($task->completed == false) 
                            <li>                           
                                <a href="{{route('tasks.edit', $task->id)}}" class="edit-btn">Edit</a>
                            </li>
                         @endif
                         
                        <li><button onclick="handleDelete({{ $task->id  }})" class="delete-btn">Delete</button></li>
                    </ul>
                    @endif
                </div>

            

                
                <div class="task-wrap">
                    <div class="main-det">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="task-show">
                                    <label for="">Company</label>
                                    <h4>{{ $task->company->name}}</h4>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="task-show">
                                    <label for="">Title</label>
                                    <h4>{{ $task->title  }}</h4>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="task-show">
                                    <label for="">Contact Person</label>
                                    <h4>{{ $task->contact_person  }}</h4>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="task-show">
                                    <label for="">Phone Number</label>
                                    <h4>{{ $task->contact_number  }}</h4>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="task-show">
                                    <label for="">Email Address</label>
                                    <h4>{{ $task->contact_email  }}</h4>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="task-show">
                                    <label for="">Department</label>
                                    <h4>{{ $task->department  }}</h4>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="task-show">
                                    <label for="">Assigned To</label>
                                    <h4>
                                    @if(!$task->assigned_to == '')
                                    @foreach(json_decode($task->assigned_to) as $assigned_id)
                                    <span>{{ \App\Models\User::find($assigned_id)->name }}</span>
                                    @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="task-show">
                                    <label for="">Assigned Date</label>
                                    <h4>{{ $task->assigned_date  }}</h4>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="task-show">
                                    <label for=""> Description</label>
                                    <h4> {!! $task->description  !!}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                   
                </div>

                <div class="task-media">
                    <h5>Media</h5>
                    
                  
                    <div class="media-wrap">
                        @if(!$task->image == '')
                        @foreach(json_decode($task->image,true) as $item)
                            @if (in_array($extension = pathinfo($item['name'], PATHINFO_EXTENSION), ['pdf']))
                                <a href="{{ asset('storage/tasks/' . $item['name'])  }}" target="_blank" class="nav-link">{{$item['name']}}</a>
                            @else
                                <a  href="{{ asset('storage/tasks/' . $item['name'])  }}" data-lightbox="media-img" data-title="{{ $item['caption'] }}">                                    
                                        <img src="{{ asset('storage/tasks/' . $item['name'])  }}" alt="">  
                                        <label> {{ $item['caption'] }} </label>
                                </a>
                            @endif
                        @endforeach     
                        @endif     
                    </div>
                </div>              
            </div>   
        </div>

    </div>


             <!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModallabel" aria-hidden="true">
  <div class="modal-dialog">
        <form action="" method="POST" id="deleteTask">
        
        @csrf
        
        @method('DELETE')
       
        <div class="modal-content del-modal">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Delete Task</h5>
            
      </div>
      <div class="modal-body">
        <p class="text-center text-bold"> Are you sure you want to delete???</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Go Back</button>
        <button type="submit" class="btn btn-danger">Yes, Delete</button>
      </div>
    </div>
        
        </form>
  </div>
</div>

    </div>

@endsection

@section('scripts')

    <script>
    
        function handleDelete(id) {
    
            var form = document.getElementById('deleteTask')        

            form.action = '/tasks/' + id       

            $('#deleteModal').modal('show')        

        }
    
    </script>

@endsection