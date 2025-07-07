window.Echo.private(`chat.${loggedInUserId}`)
    .listen('ChatEvent', (e) => {
        console.log(e);
        function scrollToBottom() {
            let chatContent = $('.chat-content');
            chatContent.scrollTop(chatContent.prop("scrollHeight"));
        }

        if (e.senderId == $('#mychatbox').attr('data-inbox')) {
            let html =
                `
                <div class="chat-item chat-left" style=""><img style="width:40px; height:40px; object-fit:cover;" src="${e.avatar}">
                    <div class="chat-details">
                        <div class="chat-text">${e.message}</div>
                        <div class="chat-time">08:25</div>
                    </div>
                </div>
            `;

            //? append the message to the chat body
            $('.chat-content').append(html);

            scrollToBottom(); //? scroll to bottom after appending the message
        }

        //? if the senderId matches the current user, show a notification
        $('.fp_chat_user').each(function () {
            let senderId = $(this).data('user');

            if (senderId == e.senderId) {
                let html = `<i class="beep"></i> New Message`;
                $(this).find('.got_new_message').html(html);
            }
        });
    });
