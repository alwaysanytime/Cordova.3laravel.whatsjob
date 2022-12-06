@extends('layouts.admin_app')

@section('content')   

<div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                                <div class="nk-block-des">
                                    @if (session('error'))
                                            <div class="alert alert-danger" role="alert">
                                                {{ session('error') }}
                                            </div>
                                    @endif
                                    @if (session('success'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('success') }}
                                            </div>
                                    @endif  
                                </div>
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between g-3">
                                    
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Generation of User Codes</h3>
                                            <div class="nk-block-des text-soft">

                                                @if(session('message'))
                                                        <p>
                                                            {{ session('message') }}
                                                        </p>
                                                @endif                                                
                                            </div>
                                            
                                        </div><!-- .nk-block-head-content -->
                                        
                                        <div class="nk-block-head-content">

                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                    
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card card-bordered card-stretch">
                                        <div class="card-inner-group">
                                            <div class="card-inner">                                        
                                            <div class="card-inner">
                                                <div class="card-title-group">
                                                    <div class="card-title">
                                                        <h5 class="title"></h5>

                                                    </div>
                                                    
                                                    <div class="card-tools mr-n1">
                                                        <ul class="btn-toolbar">
                                                            <li>
                                                                <a href="#" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                                            </li><!-- li -->
                                                        </ul><!-- .btn-toolbar -->
                                                    </div><!-- card-tools -->
                                                    
                                                    <div class="card-search search-wrap" data-search="search">
                                                        <div class="search-content">
                                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                                            <form action="{{ route('employee') }}" method="GET" role="search" style="display: flex;">
                                                                <input type="text" class="form-control form-control-sm border-transparent form-focus-none" name="incoming_did" placeholder="Quick search by Number">
                                                                <!--<input type="text" class="form-control form-control-sm border-transparent form-focus-none" name="description" placeholder="Quick search by Location Name">-->
                                                                <button type="submit" class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                                            </form>
                                                        </div>
                                                    </div><!-- card-search -->
                                                </div><!-- .card-title-group -->
                                            </div><!-- .card-inner -->
                                            
                                            <div class="card-inner p-0">
                                            <form action="{{route('generate-usercode')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                    @csrf 
                                                    <input type="hidden" name="code_type" value="">  
                                                    <div class="form-group">
                                                        <label class="form-label" for="default-01"></label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" name="name" value="" class="form-control" id="name" placeholder="Enter name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="text-align: right;">
                                                        <button type="submit" class="btn btn-lg btn-danger">Generate Codes</button>
                                                        <button type="submit" class="btn btn-lg btn-danger">Free Codes</button>
                                                    </div>                                                                                    
                                            </form>                                        
                                                <table class="table table-tranx table-dids">
                                                    <thead>
                                                        <tr class="">
                                                            
                                                            <th class="tb-tnx-info">
                                                                <span class="">
                                                                    <span>Name</span>
                                                                </span>
                                                            </th>
                                                            <th class="tb-tnx-info">
                                                                <span class="">
                                                                    <span>Email</span>
                                                                </span>
                                                            </th>
                                                            
                                                            <th class="tb-tnx-action noExl">
                                                                <span>&nbsp;</span>
                                                            </th>
                                                        </tr><!-- tb-tnx-item -->
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($employees))
                                                            @foreach($employees as $employee)
                                                                @csrf
                                                            <tr class="tb-tnx-item">
                                                                
                                                                <td class="tb-tnx-info">
                                                                    <div class="">
                                                                        <span class="title">{{$employee->name}}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="tb-tnx-info">
                                                                    <div class="">
                                                                        <span class="title">{{$employee->email}}</span>
                                                                    </div>
                                                                </td>
                                                                
                                                                <td class="tb-tnx-action noExl">
                                                                    
                                                                    <div class="dropdown">
                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                            <ul class="link-list-plain">
                                                                                
                                                                                <li><a href="#" data-toggle="modal" data-target="#modalEditEmployee{{$employee->id}}">Edit</a></li>
                                                                                <li><a href="#" data-toggle="modal" data-target="#modalDeleteEmployee{{$employee->id}}">Delete</a></li>
                                                                                
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                </td>
                                                            </tr><!-- tb-tnx-item -->
                                                          
                                                            @endforeach
                                                            </form>
                                                        @endif
                                                    </tbody>
                                                </table>

                                            </div><!-- .card-inner -->
                                                                                                                               
                                        </div><!-- .card-inner-group -->
                                        
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>

@endsection('content')  