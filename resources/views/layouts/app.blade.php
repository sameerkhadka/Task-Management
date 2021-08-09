<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hue Shine</title>
    <link rel="icon" type="image/png" href="{{  asset('asset/images/logo1.png') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.css" integrity="sha512-Woz+DqWYJ51bpVk5Fv0yES/edIMXjj3Ynda+KWTIkGoynAMHrqTcDUQltbipuiaD5ymEo9520lyoVOo9jCQOCA==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{  asset('asset/css/bootstrap.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    @yield('css')
    
</head>
<body>
            @if($errors->any())            

                 <div class="alert alert-danger">

                    @foreach($errors->all() as $error)

                            <p> <i class="fas fa-exclamation-circle"></i> {{ $error }} </p>

                    @endforeach             
            
                  </div>

            @endif 

@guest



<section class="login-wrap">
        <div class="login-html">
            <img src="{{ asset('asset/images/logo.png') }}" alt="">

            <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="login-form">
                
                <label for="">Email</label>
                <input id="email" type="email" name="email" class=" @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                 <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
                 </span>
                 @enderror
           
            </div>

            <div class="login-form">
            <label for="password" class="col-form-label ">{{ __('Password') }}</label>
            <input id="password" type="password" class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

             @error('password')
               <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
              </span>
            @enderror
            </div>

            <div class="form-group row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit">Login</button>           

            </form>

        </div>
    </section>

@endguest

@auth


@if(session()->has('success'))

<div class="alert alert-success  hs-alert" role="alert">
 
  <p><i class="far fa-check-circle"></i> {{session()->pull('success')}} </p>
</div>


@endif

@if(Session::has('error'))
<div class="alert alert-danger  hs-alert" role="alert">
 
  <p><i class="far "></i> {{session()->pull('error')}} </p>

</div>
@endif
  
<nav id="navbar" >
        <ul>
            <li>
            <!--<form action="/" method="GET">-->
            <!--    <div class="search-bar">                -->
            <!--        <input type="text" name="search" placeholder="Search by task name">-->
            <!--        <i class="fas fa-search"></i>-->
            <!--    </div> -->
            <!--</form>-->
            </li>

            <!-- <li>
                <div class="sort">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Sort By </option>
                        <option value="1">Active</option>
                        <option value="2">Completed</option>
                      </select>
                </div>
            </li> -->
        </ul>

        

        <ul class="ml-auto">
        @if(auth()->user()->isAdmin())
            <li>
                <a href="{{route('tasks.index') }}" class="btn-add task-btn"><i class="fas fa-plus"></i> Task</a>
            </li>

            <li>
                <a href="#" class="btn-add company-btn" data-toggle="modal" data-target="#new-company"><i class="fas fa-plus"></i> Company</a>

            

                <form action="{{ route('companies.store')  }}" method="POST">
                        @csrf
                  <div class="modal fade" id="new-company" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">                    
                        <div class="modal-body">
                            <label for="new-company">New Company</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="modal-btn btn-cancel"data-dismiss="modal">Cancel</button>
                          <button type="submit" class="modal-btn btn-save">Add</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
            </li>
            @endif 

            <li>
                <a href="" class="profile" id="profile-click"><img src="{{  asset('asset/images/male.jpg') }}"  alt="">  </a>
                <div class="prf-box">   
                <div class="prf-des">
                        <img src="{{  asset('asset/images/male.jpg') }}" alt="">
                        <h5>
                        {{ Auth::user()->name }} <p>{{ Auth::user()->email }}</p>
                        </h5>
                    </div>
              

                    <!-- <a class="sign-out" href="{{ route('logout') }}"><i class="fas fa-power-off"></i> Logout</a> -->
                    <a class="sign-out" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-power-off"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                
                </div>
            </li>
        </ul>

    </nav>


    <div class="wrapper">
        <aside>
            <div class="aside-head">
                <div class="awrp">
                    <a href="/"><img src="{{  asset('asset/images/logo.png') }}" class="avatar" alt="Hue Shine">  </a>
                    
                </div>
            </div>
           
            <div class="as-cont">
                <h4>
                    <i class="fas fa-folder-open"></i> Department
                </h4>

                <ul>
                    <li>
                        <a href="/design">Design</a>
                    </li>
                    <li> 
                        <a href="/web">Web </a>
                    </li>
                    <li>
                        <a href="/print">Print</a>
                    </li>
                </ul>
            </div>


            <div class="as-cont">
                <h4>
                    <i class="fas fa-users"></i> Team
                </h4>

                <ul>
                     @foreach($users as $user)
                    <li>
                        <a href="/user_task/{{ $user->id }}">{{ $user->name }}</a>
                    </li>
                    @endforeach
                    <!-- <li> 
                        <a href="">Yogesh Karki </a>
                    </li>
                    <li>
                        <a href="">Looja Shakya</a>
                    </li>
                    <li>
                        <a href="">Sameer Khadka</a>
                    </li> -->

                </ul>
            </div>

            <div class="as-cont">
                <h4>
                <a href="{{ route('view') }}" > <i class="fas fa-th-list"></i> Company</a>
                </h4>

                <!-- <ul>
                    @foreach($companies as $company)
                    <li>
                        <a href="/companytask/{{ $company->id }}">{{ $company->name }}</a>
                    </li>                   
                    @endforeach

                   
              

                </ul>

                <a href="{{ route('view') }}" class="all-list">View All <i class="fas fa-long-arrow-alt-right"></i></a> -->
            </div>
            
            <div class="as-cont">
                <h4>
                    <a href="/email_proofing"><i class="fas fa-clipboard-check"></i> Email Proofing</a>
                </h4>
            </div>

            <div class="as-cont">
                <h4>
                    <a href="/completed"><i class="fas fa-clipboard-check"></i> Completed</a>
                </h4>
            </div>
            
             @if(auth()->user()->isAdmin())
             <div class="as-cont">
                <h4>
                    <a href="/registeruser"><i class="fas fa-clipboard-check"></i> Register New User</a>
                </h4>
            </div>
             @endif

                <!--<div class="as-cont">-->
                <!--@if(auth()->user()->isAdmin())-->
                <!--<h4>-->
                <!--<a href="{{route('register')}}"><i class="fas fa-clipboard-check"></i> Register</a>-->
                <!--</h4>-->
                <!--@endif-->

        </aside>       

 @yield('content')   

 @endauth
 

</body>


<script src="{{  asset('asset/js/jquery.min.js') }}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" ></script> -->
<script src="{{  asset('asset/js/bootstrap.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="{{  asset('asset/js/main.js') }}"></script>
<script src="{{  asset('asset/js/jquery.dataTables.js') }}"></script>
@yield('ajax')
@yield('scripts')
@yield('date')
<!--<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>-->
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $('.mydatatable').DataTable();
</script>
</html>