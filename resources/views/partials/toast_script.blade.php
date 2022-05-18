<script>
    const allMessages = document.getElementsByClassName('system-message');
    timeout = 5000;
    timeout_difference = 500;
    for(message of allMessages){
        setTimeout(function (){
            $(message).fadeOut(500);
        },timeout);
        timeout += timeout_difference;
    }
    const messagesBox = document.getElementById('ToastsBox');
    setTimeout(function (){
        messagesBox.classList.add('d-none');
    },timeout + timeout_difference);

    const successMessage = document.getElementById('success-message');
    if ({{Session::get('success') == true ? 'true': 'false'}}) {
        showMessage(successMessage);
    }

    const Messages = document.getElementsByClassName('error-message');
    if({{$errors->any()? 'true' : 'false'}}){
        for (i = 0; i < Messages.length; i++){
            showMessage(Messages[i]);
        }
    }
    function showMessage(value){
        let toast = new bootstrap.Toast(value);
        toast.show();
    }
</script>
