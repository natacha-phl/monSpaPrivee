
document.getElementById("modify").onclick = function() {Modify()};

function Modify() {
    document.getElementById('firstname').disabled = false;
    document.getElementById('lastname').disabled = false;
    document.getElementById('email').disabled = false;
    document.getElementById('street').disabled = false;
    document.getElementById('zipcode').disabled = false;
    document.getElementById('city').disabled = false;
    document.getElementById('modificationvalidation').hidden = false;
    document.getElementById("modify").hidden = true;
    document.getElementById("cancel").hidden = false;

}


document.getElementById("cancel").onclick = function() {Cancel()};

function Cancel() {

    document.getElementById('firstname').disabled = true;
    document.getElementById('lastname').disabled = true;
    document.getElementById('email').disabled = true;
    document.getElementById('street').disabled = true;
    document.getElementById('zipcode').disabled = true;
    document.getElementById('city').disabled = true;
    document.getElementById('modificationvalidation').hidden = true;
    document.getElementById("modify").hidden = false;
    document.getElementById('cancel').hidden = true;


}

document.getElementById("delete-form").onclick = function(event) {confirmDelete(event)};

/*
function confirmDelete(){
    let result = confirm("Etes vous sur de vouloir supprimer votre compte (cette action est irréversible)");
    result;
    if (result === false){
        window.location.href;
        // window.open(url);

    } else {
        let url = document.getElementById('delete-form').getAttribute('action');

        window.location.href = url;

    }


}

 */


function confirmDelete(event){
    // let url = document.getElementById('delete-form').getAttribute('action');
    let form = document.getElementById('delete-form');

    let result = confirm("Confirmez-vous la suppression (cette action est irréversible)");

    if (result === false){

        event.preventDefault();

    }


}

// In your Javascript (external .js resource or <script> tag)










