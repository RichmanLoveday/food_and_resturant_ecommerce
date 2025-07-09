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
                            <ul class="list-unstyled list-unstyled-border fp_chat_user_list">
                                @foreach ($senders as $sender)
                                    <li class="media fp_chat_user" data-name="{{ $sender->sender->name }}"
                                        data-user="{{ $sender->sender->id }}" style="cursor: pointer">
                                        <img alt="image" class="mr-3 rounded-circle" width="50" height="50"
                                            src="{{ asset($sender->sender->avatar) }}"
                                            style="object-fit: cover; height:50px; width:50px;">
                                        <div class="media-body">
                                            <div class="mt-0 mb-1 font-weight-bold">
                                                {{ Str::ucfirst($sender->sender->name) }}</div>
                                            <div class="text-warning text-small font-600-bold got_new_message">
                                                @if ($sender->unseen_messages > 0)
                                                    <i class="beep"></i> New Message
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-9">
                    <div class="card chat-box card-success" data-inbox="" id="mychatbox" style="height: 70vh;">
                        <div class="card-header">
                            <h4 id="chat_header">
                                @isset($senderDetails->name)
                                    {{-- <i class="fas fa-circle text-success mr-2" title="Online" data-toggle="tooltip"></i> --}}
                                    Chat with {{ Str::ucfirst(@$senderDetails->name) }}
                                </h4>
                            @endisset
                        </div>
                        <div class="card-body chat-content" tabindex="2" style="overflow: hidden; outline: none;">

                            @isset($messages)
                                @foreach ($messages as $message)
                                    <div class="chat-item {{ $message->sender_id == auth()->user()->id ? 'chat-right' : 'chat-left' }}"
                                        style="">
                                        <img style="width:40px; height:40px; object-fit:cover;"
                                            src="{{ asset($message->sender->avatar) }}" alt="person"
                                            class="img-fluid rounded-circle">
                                        <div class="chat-details">
                                            <div class="chat-text">{{ $message->message }}</div>
                                            {{-- <div class="chat-time">{{ $message->created_at->diffForHumans() }}</div> --}}
                                        </div>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                        <div class="card-footer chat-form">
                            <form id="chat-form2">
                                @csrf
                                <input type="hidden" name="msg_temp_id" class="msg_temp_id" value="">
                                <input type="text" class="form-control fp_send_message" name="message"
                                    placeholder="Type a message">
                                <input type="hidden" id="receiver_id" name="receiver_id"
                                    value="{{ $senderDetails->id ?? '' }}">
                                <button type="submit" class="btn btn-primary">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            var userId = "{{ auth()->user()->id }}";
            var avatar = "{{ auth()->user()->avatar }}";
            // $('#receiver_id').val('');

            function scrollToBottom() {
                let chatContent = $('.chat-content');
                chatContent.scrollTop(chatContent.prop("scrollHeight"));
            }
            scrollToBottom();

            //? Load chat messages when a user is clicked
            $(document).on('click', '.fp_chat_user', function(e) {
                e.preventDefault();
                let senderId = $(this).data('user');
                let senderName = $(this).data('name');
                console.log(senderName);
                let clickedElement = $(this);

                //? Set the chat box index and receiver ID
                $('#mychatbox').attr('data-inbox', senderId);
                $('#receiver_id').val(senderId);

                // console.log(senderId);

                $.ajax({
                    url: "{{ route('admin.chat.get-conversation', ':senderId') }}".replace(
                        ":senderId",
                        senderId),
                    method: "GET",
                    beforeSend: function() {
                        history.pushState(null, '',
                            "{{ route('admin.chat.conversation', ':senderId') }}".replace(
                                ":senderId",
                                senderId
                            ));

                        $('#chat_header').text(
                            `Chat with ${senderName.charAt(0).toUpperCase() + senderName.slice(1)}`
                        );
                        $('.chat-content').empty();
                    },
                    success: function(response) {
                        $('.chat-content').empty(); //? clear chat box

                        $.each(response, function(index, message) {
                            let avatar = "{{ asset(':avatar') }}".replace(':avatar',
                                message.sender
                                .avatar);
                            let html = `
                            <div class="chat-item ${message.sender_id == userId ? "chat-right" : "chat-left"}" style=""><img style="width:40px; height:40px; object-fit:cover;" src="${avatar}">
                                <div class="chat-details">
                                    <div class="chat-text">${message.message}</div>
                                </div>
                            </div>
                            `;

                            clickedElement.find('.got_new_message').empty();
                            $('.chat-content').append(html);

                            //? remove user notification message 
                            $('.fp_user_message_notification').each(function() {
                                let senderId = $(this).data('user');

                                if (senderId == message.sender_id) {
                                    $(this).remove();
                                }
                            });

                            // If no .fp_user_message_notification left, remove beep class from .fp_message_envelope
                            if ($('.fp_user_message_notification').length === 0) {
                                $('.fp_message_envelope').removeClass('beep');
                            }
                        });

                        scrollToBottom();
                    },
                    error: function(xhr, status, error) {},
                });
            });

            $("#chat-form2").on('submit', function(e) {
                e.preventDefault();
                let msgId = Math.floor(Math.random() * (1 - 1000 + 1)) +
                    10000; //? Generate a random message ID
                $('.msg_temp_id').val(msgId);

                let formData = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: "{{ route('admin.chat.send-message') }}",
                    data: formData,
                    beforeSend: function() {
                        let message = $('.fp_send_message').val();
                        let html = `
                         <div class="chat-item chat-right" style=""><img style="width:40px; height:40px; object-fit:cover;" src="{{ asset('') }}${avatar}">
                                <div class="chat-details">
                                    <div class="chat-text">${message}</div>
                                    <div class="chat-time ${msgId}">sending...</div>
                                </div>
                            </div>
                            `;

                        $('.chat-content').append(html);
                        $('.fp_send_message').val('');

                        scrollToBottom();

                        //? remove beep notification
                        $('.fp_chat_user').each(function() {
                            let senderId = $(this).data('user');

                            if ($('#mychatbox').attr('data-inbox') == senderId) {
                                $(this).find('.got_new_message').remove();
                            }
                        });


                        //? remove user notification message 
                        $('.fp_user_message_notification').each(function() {
                            let senderId = $(this).data('user');

                            if (senderId == message.sender_id) {
                                $(this).remove();
                            }
                        });

                        // If no .fp_user_message_notification left, remove beep class from .fp_message_envelope
                        if ($('.fp_user_message_notification').length === 0) {
                            $('.fp_message_envelope').removeClass('beep');
                        }
                    },
                    success: function(response) {
                        //? Remove the "sending..." message
                        console.log(response)
                        if ($('.msg_temp_id').val() == response.msgId) {
                            $(`.${response.msgId}`).remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        let errors = xhr.responseJSON.errors;
                        console.log(errors);

                        $.each(errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                })
            });
        });
    </script>
@endpush
