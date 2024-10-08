function encodeForAjax(data) {
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

function sendMessage(){
    //sends a message asynchronously
    const button = document.querySelector('#messageForm button');

    if(button == null){
        return;
    }

    messagesDiv = document.getElementById("UserMessages");
    const form = button.parentElement;
    
    if(button == null){
        return;
    }
    messagesDiv.scrollTo(0,messagesDiv.scrollHeight);

    button.addEventListener('click',async function(event){
        event.preventDefault();
        
        const form = button.parentElement;
        const newMessage = document.createElement('p');
        newMessage.innerHTML = form.elements['messageText'].value;
        newMessage.classList.add("sender");
        messagesDiv.appendChild(newMessage);
        messagesDiv.scrollTo(0,messagesDiv.scrollHeight);

        
        const request = new XMLHttpRequest();
        request.open('post','/../actions/action_send_message.php',true);
        request.setRequestHeader('Content-Type', 
        'application/x-www-form-urlencoded')
        request.send(encodeForAjax({senderID : form.elements['senderID'].value,
            receiverID : form.elements['receiverID'].value,
            productID : form.elements['productID'].value,
            messageText : form.elements['messageText'].value,
            csrf: form.elements["csrf"].value
        }
            )
        );

        form.reset();

    })
}

sendMessage();