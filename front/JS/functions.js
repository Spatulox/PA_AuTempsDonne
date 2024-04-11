function redirect(page, message = null) {
    if(message){
        window.location.href = page+"?message="+message;
    }
    else{
        window.location.href = page;
    }
}