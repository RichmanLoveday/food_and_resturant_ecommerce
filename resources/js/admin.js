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

            if (e.senderId == senderId) {
                let html = `<i class="beep"></i> New Message`;
                $(this).find('.got_new_message').html(html);
                console.log(senderId, e.senderId);
            }
        });

        //? if the senderId matches the current user, add beep class to the message envelope
        $('.fp_message_envelope').addClass('beep');

        console.log(baseUrl);
        // Format created_at as "1 min ago" or similar
        function timeAgo(dateString) {
            // Ensure the date string is in ISO 8601 format for reliable parsing
            let parsedDate = Date.parse(dateString);
            if (isNaN(parsedDate)) {
                // Try to replace space with 'T' if not ISO format
                parsedDate = Date.parse(dateString.replace(' ', 'T'));
            }
            const now = new Date();
            const messageDate = new Date(parsedDate);
            const diffMs = now.getTime() - messageDate.getTime();
            const seconds = Math.floor(diffMs / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30);
            const years = Math.floor(days / 365);

            if (seconds < 60) return "just now";
            if (minutes < 60) return minutes === 1 ? "1 minute ago" : `${minutes} minutes ago`;
            if (hours < 24) return hours === 1 ? "1 hour ago" : `${hours} hours ago`;
            if (days < 30) return days === 1 ? "1 day ago" : `${days} days ago`;
            if (months < 12) return months === 1 ? "1 month ago" : `${months} months ago`;
            return years === 1 ? "1 year ago" : `${years} years ago`;
        }

        // Helper function to capitalize the first letter
        function ucfirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        $('.fp_messages_notification_list').prepend(`
            <a data-user="${e.senderId}" href="${baseUrl}/admin/chat/conversation/${e.senderId}"
            class="dropdown-item dropdown-item-unread got_new_message">
                <div class="dropdown-item-avatar">
                    <img style="width: 50px; height:50px; object-fit:cover;" alt="image"
                    src="${baseUrl}/${e.avatar}" class="rounded-circle">
                    </div>
                    <div class="dropdown-item-desc">
                    <b>${ucfirst(e.senderName)}</b>
                    <p>${e.message}</p>
                    <div class="time">${timeAgo(e.created_at)}</div>
                </div>
            </a>
        `);

    });
