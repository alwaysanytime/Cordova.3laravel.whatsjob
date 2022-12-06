@extends('layouts.admin_app')

@section('content')   
<style>
    .row{
        margin:5px;
    }
</style>
<div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">

                            <div class="nk-content-body">

                                <div class="nk-block">
                                    <div class="card card-bordered card-stretch">
                                        <div class="card-inner-group">
                                            
                                            <div class="card-inner p-0">
                                                <div class="nk-msg-body bg-white">
                                                    <div class="nk-msg-head">
                                                        <div class="name" style="display: flow-root;">
                                                            <div style="float: left;">
                                                            {{App\Models\Employee::get_employee_name($message_header->receiver_id)}}
                                                            </div>
                                                            <div style="float: right;">
                                                                {{$message_header->created_at}}
                                                            </div>
                                                        </div>
                                                        <h4 class="title d-none d-lg-block">{{$message_header->title}}</h4>
                                                        <div class="nk-msg-head-meta">
                                                            <div class="d-none d-lg-block">
                                                                {{$message_header->object}}
                                                            </div>
                                                            <div class="">Read on {{$message_header->created_at}}</div>
                                                            
                                                        </div>
                                                        
                                                    </div><!-- .nk-msg-head -->

                                                    <div class="">
                                                                <div class="attach-files">
                                                                    <ul class="attach-list" style="display: block;">
                                                                        @foreach($message_attachments as $attachment)
                                                                            <li class="attach-item">
                                                                                <a class="download" href="{{$attachment->path}}" target="_blank"><em class="icon ni ni-img"></em><span>{{$attachment->name}}</span></a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>

                                                                </div>                                                        
                                                    </div>    

                                                    <div class="nk-msg-reply nk-reply" data-simplebar>
                                                        @foreach($messages as $message)
                                                            @if($message->owner_type == "admin")
                                                                <div class="row">
                                                                    <div class="col-md-11">
                                                                        <div class="nk-reply-item card bg-light card-bordered">
                                                                            <div class="nk-reply-header">
                                                                                <div class="date-time" style="color: black;">{{$message->updated_at}}</div>
                                                                            </div>
                                                                            <div class="nk-reply-body">
                                                                                <div class="nk-reply-entry entry">
                                                                                    <p>{{$message->content}}</p>
                                                                                </div>
                                                                            </div>                                                                     
                                                                        </div>                                                                    
                                                                    </div>
                                                                    <div class="col-md-1">

                                                                    </div>
                                                                </div> 
                                                            @else
                                                                <div class="row ">
                                                                    <div class="col-md-1">
                                                                            
                                                                    </div>
                                                                    <div class="col-md-11">
                                                                        <div class="nk-reply-item card text-white @if($message->is_read == 0) bg-danger @else bg-info @endif card-bordered">
                                                                            <div class="nk-reply-header">
                                                                                <div class="date-time" style="color: black;">{{$message->updated_at}}</div>
                                                                            </div>
                                                                            <div class="nk-reply-body">
                                                                                <div class="nk-reply-entry entry">
                                                                                    <p>{{$message->content}}</p>
                                                                                </div>
                                                                            </div>                                                                     
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            @endif
                                                        @endforeach

                                                        <div class="nk-reply-form">
                                                            <div class="">
                                                                <form action="{{route('store-message')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                                                                @csrf  
                                                                    <input type="hidden" name="owner_type" value="admin"> 
                                                                    <input type="hidden" name="header_id" value="{{$message_header->id}}">           
                                                                    <div class="nk-reply-form-editor">
                                                                        <div class="nk-reply-form-field">
                                                                            <textarea name="message" class="form-control form-control-simple no-resize" placeholder="New Message"></textarea>
                                                                        </div>
                                                                        <div class="nk-reply-form-tools">
                                                                            <button class="btn btn-primary" type="submit">Reply</button>
                                                                        </div><!-- .nk-reply-form-tools -->
                                                                    </div><!-- .nk-reply-form-editor -->
                                                            </div>
                                                        </div><!-- .nk-reply-form -->
                                                    </div><!-- .nk-reply -->
                                                </div><!-- .nk-msg-body -->  

                                            </div><!-- .card-inner -->
                                                                                                                               
                                        </div><!-- .card-inner-group -->
                                        
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>
                                                    
@endsection('content')  