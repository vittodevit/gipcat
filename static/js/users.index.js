// add event listener for passwordMeter update
document.getElementById("ucum.newPassword").addEventListener('input', (event) => {
    updatePasswordMeter("ucum.passMeter" ,event.target.value.length);
});

// add event listener for permissionType update
document.getElementById("ucum.permissionType").addEventListener('input', () => {
    if(document.getElementById("ucum.permissionType").value != 1){
        document.getElementById("ucum.idCustomer").disabled = true;
    }else{
        document.getElementById("ucum.idCustomer").disabled = false;
    }
});

function userCreateUnlockedAJAX(){
    var newPassword = document.getElementById("ucum.newPassword");
    var confirmPassword = document.getElementById("ucum.confirmPassword");

    if(newPassword.value.length < 8){
        toastr.error("Password troppo corta. Il minimo è di 8 caratteri.");
        return;
    }

    if(newPassword.value != confirmPassword.value){
        toastr.error("Le due password non corrispondono!");
        return;
    }

    // ajax update
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/ajax_mkuser.php',
        data: {
            "userName": document.getElementById("ucum.userName").value,
            "legalName": document.getElementById("ucum.legalName").value,
            "legalSurname": document.getElementById("ucum.legalSurname").value,
            "password": document.getElementById("ucum.newPassword").value,
            "permissionType": document.getElementById("ucum.permissionType").value,
            "idCustomer": document.getElementById("ucum.idCustomer").value
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}