@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Chat Box</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Chat Box</div>
            </div> --}}
        </div>

        <div class="section-body">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-sm-6 col-lg-3 h-100">
                    <div class="card" style="height: 70vh;">
                        <div class="card-header">
                            <h4>Who's Online?</h4>
                        </div>
                        <div class="card-body" style="overflow-y:scroll;">
                            <ul class="list-unstyled list-unstyled-border">
                                @foreach ($chatUsers as $chatUser)
                                    <li class="media">
                                        <img alt="image" class="mr-3 rounded-circle" width="50" height="50"
                                            src="{{ asset($chatUser->avatar) }}"
                                            style="object-fit: cover; height:50px; width:50px;">
                                        <div class="media-body">
                                            <div class="mt-0 mb-1 font-weight-bold">{{ $chatUser->name }}</div>
                                            <div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i>
                                                Online</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-9">
                    <div class="card chat-box card-success" id="mychatbox2" style="height: 70vh;">
                        <div class="card-header">
                            <h4><i class="fas fa-circle text-success mr-2" title="Online" data-toggle="tooltip"></i> Chat
                                with Ryan</h4>
                        </div>
                        <div class="card-body chat-content" tabindex="2" style="overflow: hidden; outline: none;">
                            <div class="chat-item chat-left" style=""><img src="../dist/img/avatar/avatar-1.png">
                                <div class="chat-details">
                                    <div class="chat-text">Hi, dude!</div>
                                    <div class="chat-time">08:25</div>
                                </div>
                            </div>
                            <div class="chat-item chat-right" style=""><img src="../dist/img/avatar/avatar-2.png">
                                <div class="chat-details">
                                    <div class="chat-text">Wat?</div>
                                    <div class="chat-time">08:25</div>
                                </div>
                            </div>
                            <div class="chat-item chat-left" style=""><img src="../dist/img/avatar/avatar-1.png">
                                <div class="chat-details">
                                    <div class="chat-text">You wanna know?</div>
                                    <div class="chat-time">08:25</div>
                                </div>
                            </div>
                            <div class="chat-item chat-right" style=""><img src="../dist/img/avatar/avatar-2.png">
                                <div class="chat-details">
                                    <div class="chat-text">Wat?!</div>
                                    <div class="chat-time">08:25</div>
                                </div>
                            </div>
                            <div class="chat-item chat-left chat-typing" style=""><img
                                    src="../dist/img/avatar/avatar-1.png">
                                <div class="chat-details">
                                    <div class="chat-text"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer chat-form">
                            <form id="chat-form2">
                                <input type="text" class="form-control" placeholder="Type a message">
                                <button class="btn btn-primary">
                                    <i class="far fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
