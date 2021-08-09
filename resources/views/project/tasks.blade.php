@extends('layouts.app')
@section('content')
        <div class="content">
            <div class="ntask">
                <h4 class="task-title">
                {{ isset($task) ? 'Edit Task' : 'New Task' }} 
                </h4>

                <div class="row">
                    <div class="col-md-4">
                        <div class="task-form">
                            <label for="">Company</label>
                    <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                      @if(isset($task))  
                        @method('PUT')
                      @endif

                            <select class="form-select"  aria-label="Default select example" name="company">
                                <option selected></option>
                               
                                 @foreach($companies as $company)
                                 @if(isset($task))
                                <option value="{{ $company->id }}" @if($task->company_id==$company->id) selected @endif>{{ $company->name }}</option>
                                @else
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endif
                                @endforeach
                                <!-- <option value="2">Nick Simons Institute</option>
                                <option value="3">Almira</option>
                                <option value="4">Kathmandu Engineering Collge</option> -->
                            </select>
                        </div>
                    </div>
    
                    <div class="col-md-8">
                        <div class="task-form">
                           <label for="">Task Name</label>
                            <input type="text" name="title" id="title" autocomplete="off" value="{{ isset($task) ? $task->title : '' }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="task-form">
                            
                            <label for="">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person"  value="{{ isset($task) ? $task->contact_person : '' }}">
                        </div>
                        
                    </div>
                    
                    <div class="col-md-4">
                        <div class="task-form">
                            
                            <label for="">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number"  value="{{ isset($task) ? $task->contact_number : '' }}" >
                        </div>
                        
                    </div>
                    
                    <div class="col-md-4">
                        <div class="task-form">
                            
                            <label for="">Contact Email</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ isset($task) ? $task->contact_email : '' }}">
                        </div>
                        
                    </div>

                    <div class="col-md-4">
                        <div class="task-form">
                            <label for="">Department</label>

                            <select class="form-select"  aria-label="Default select example" name="department">
                                <option selected> --- </option>
                                @if(!isset($task))
                                <option value="Design">Design</option>
                                <option value="Web">Web</option>
                                <option value="Print">Print</option>
                               @else
                                <option value="Design" {{ $task->department == 'Design' ? 'selected' : '' }}>Design</option>
                                <option value="Web" {{ $task->department == 'Web' ? 'selected' : '' }}>Web</option>
                                <option value="Print" {{ $task->department == 'Print' ? 'selected' : '' }}>Print</option>
                                @endif
                            </select>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="task-form">
                            <label for="">Assigned To</label>
                            
                            <select class=" js-example-basic-multiple" name="assigned_to[]" multiple >
                            @foreach($users as $user) 
                            @if(isset($task))
                                @if(!$task->assigned_to == '')
                                <option value="{{ $user->id }}" @if(in_array($user->id,json_decode($task->assigned_to))) selected @endif>{{ $user->name }}</option>
                                @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @else
                             <option value="{{ $user->id }}">{{ $user->name }}</option>
                             @endif
                            @endforeach
                                <!-- <option value="2">Yogesh Karki</option>
                                <option value="3">Looja Shakya</option>
                                <option value="4">Sameer Khadka</option> -->
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="task-form">
                            <label for="">Priority </label>

                            <select class="form-select"  aria-label="Default select example" name="priority">
                                <option selected></option>
                                @if(!isset($task))
                                <option value="1" >Urgent</option>
                                <option value="2" >High</option>
                                <option value="3" >Medium</option>
                                <option value="4" >Low</option>
                                @else
                                <option value="1" {{ $task->priority == 1 ? 'selected' : '' }}> Urgent</option>
                                <option value="2" {{ $task->priority == 2 ? 'selected' : '' }}>High</option>
                                <option value="3" {{ $task->priority == 3 ? 'selected' : '' }}>Medium</option>
                                <option value="4" {{ $task->priority == 4 ? 'selected' : '' }}>Low</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="task-form">
                            <label for="">Assigned Date </label>
                            <input type="text" class ="datepicker" name="assigned_date" autocomplete="off" value="{{ isset($task) ? $task->assigned_date : '' }}">
                            
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="task-form">
                            <label for="">Deadline </label>
                            <input type="text" class ="datepicker" name="deadline" autocomplete="off" value="{{ isset($task) ? $task->deadline : '' }}">
                            
                        </div>
                    </div>


                </div>


              
                    

                    <div class="col-md-12">
                        <div class="task-form">
                            <label for="description">Description</label>
                         
                            <input id="description" type="hidden" name="description" value="{{ isset($task) ? $task->description : '' }}">
                            <trix-editor input="description"></trix-editor>
                        </div>

                    </div>
                    
                   
          

                    @if(isset($task))
                    <div class="task-media">
                    <h5>Media</h5>
                        <div class="media-wrap">
                        @if(!$task->image == '')
                            @foreach(json_decode($task->image,true) as $item)
                            <div class='m-img-wrap'>
                                @if (in_array($extension = pathinfo($item['name'], PATHINFO_EXTENSION), ['pdf']))
                                <a href="{{ asset('storage/tasks/' . $item['name'])  }}" target="_blank" class="nav-link">{{$item['name']}}</a>
                                @else
                                <a  href="{{ asset('storage/tasks/' . $item['name'])  }}" data-lightbox="media-img" data-title="{{ $item['caption'] }}">                                    
                                        <img src="{{ asset('storage/tasks/' . $item['name'])  }}" alt=""> </a> 
                                </a>
                                @endif
                                <input type="text" placeholder="Caption Here" value="{{ $item['caption'] }}"  name="captions[]">

                                <a onclick="deleteImage({{ $loop->iteration }},{{ $task->id }});" class="delete btn btn-danger">Delete</a>

                            </div>
                            
                         @endforeach
                         
                         @endif
                        
                                                      
                        </div>
                    </div>

                    @endif

                    <div class="col-md-12">
                        <div class="task-form file-up">
                            <label for="image"> {{ isset($task) ? 'Add Files' : 'Upload Files' }}</label>                               

                                <input type="file" name="image[]" multiple><br/>
                        
                                <div id="selectedFiles"></div>                                                 

                            <button type="submit" name="submit" value="1" class="tb-inline btn-submit">  {{ isset($task) ? 'Update Task' : 'Add Task' }} </button>
                          
                            <button type="submit" name="submit_return" value="1" class='tb-inline btn-return' >Save and Return </button>
                       
                        </div>
                    </div>
                    
                </form>
                    
                    </div>
       
                    

                </div>
    
                    
            </div>


        </div>

    </div>


    @endsection

    @section('scripts')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

    @endsection

    @section('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css" >

    @endsection

    @section('ajax')
    @if(isset($task))
    <script>
    function deleteImage(arrayID,taskID){
        console.log( arrayID,taskID);
        if (confirm("Are you sure?")) {
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{ route('images.destroy') }}",
                    type: "POST",
                    data: {
                        arrayID: arrayID,
                        taskID: taskID
                    },
                    beforeSend: function () {
                    },
                    success: function(data){
                        window.location.replace("{{ route('tasks.edit',$task->id) }}");
                        console.log(data);
                    }
                });
        }
        return false;
    }
    </script>
    @endif
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