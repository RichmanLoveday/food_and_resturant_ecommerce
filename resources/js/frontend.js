window.Echo.private(`chat.${loggedInUserId}`)
    .listen('ChatEvent', (e) => {
        console.log(e);
        function scrollToBottom() {
            let chatContent = $('.fp__chat_body');
            chatContent.scrollTop(chatContent.prop("scrollHeight"));
        }

        let html = `
         <div class="fp__chating">
            <div class="fp__chating_img">
                <img style="width:30px; height:30px; object-fit:cover;" src="${e.avatar}" alt="person" class="img-fluid w-100 rounded-circle">
            </div>
            <div class="fp__chating_text">
                <p>${e.message}</p>
            </div>
        </div>
        `;

        //? append the message to the chat body
        $('.fp__chat_body').append(html);

        scrollToBottom(); //? scroll to bottom after appending the message
        $('.unseen-messages-count').text(1);
    });
