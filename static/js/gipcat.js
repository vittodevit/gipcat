feather.replace({ 'aria-hidden': 'true' })

function updatePasswordMeter(target, length){
    // calc values
    var pvalue = length < 16 ? length * 6 : 100;
    var pcolor = "";
    if(length > 11){
        pcolor = "success";
    }else{
        if(length > 7){
            pcolor = "warning";
        }else{
            pcolor = "danger";
        }
    }
    // apply on dom
    var passwordMeter = document.getElementById(target);
    // removing all bg classes
    passwordMeter.classList.remove("bg-success");
    passwordMeter.classList.remove("bg-warning");
    passwordMeter.classList.remove("bg-danger");
    // adding right color class
    passwordMeter.classList.add("bg-" + pcolor);
    // updating width values
    passwordMeter.style.width = pvalue + "%";
    passwordMeter.setAttribute("aria-valuenow", pvalue);
}

// add event listener for passwordMeter update
document.getElementById("spcm.newPassword").addEventListener('input', (event) => {
    updatePasswordMeter("spcm.passMeter" ,event.target.value.length);
});

function changePasswordSelfAJAX(){
    var oldPasswordTb = document.getElementById("spcm.oldPassword");
    var newPasswordTb = document.getElementById("spcm.newPassword");
    var confirmPasswordTb = document.getElementById("spcm.confirmPassword");

    if(oldPasswordTb.value.length < 2){
        toastr.error("La vecchia password è obbligatoria per il cambiamento.");
        return;
    }

    if(newPasswordTb.value.length < 8){
        toastr.error("Password troppo corta. Il minimo è di 8 caratteri.");
        return;
    }

    if(newPasswordTb.value != confirmPasswordTb.value){
        toastr.error("Le due password non corrispondono!");
        return;
    }

    // ajax update
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/ajax_pwdchg.php',
        data: {
            "userName": sessionUserName,
            "oldPassword": document.getElementById("spcm.oldPassword").value,
            "newPassword": document.getElementById("spcm.newPassword").value,
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

function successReload(){
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('s', '1');
    window.location.search = urlParams;
}

if(location.href.includes("s=1")){
    toastr.success("Operazione completata con successo!");
    window.history.replaceState(
        { additionalInformation: 'ok' }, 
        document.title, 
        "."
    );
}