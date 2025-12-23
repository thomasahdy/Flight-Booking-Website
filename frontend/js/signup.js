let newUser = {

}


function continueRegisteration(event){
    event.preventDefault();
    newUser.email = document.getElementById("email").value;
    newUser.name = document.getElementById("name").value;
    newUser.tel = document.getElementById("tel").value;
    let selectedIndex = document.getElementById("type").value.selectedIndex;
    switch(selectedIndex)
    {
        case 0: newUser.type = "passenger"; break;
        case 1: newUser.type = "compamy"; break;
    }
    newUser.password = document.getElementById("password").value;
    console.log(newUser);
    
    


}