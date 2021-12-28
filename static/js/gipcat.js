feather.replace({ 'aria-hidden': 'true' })

function updatePasswordMeter(length){
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
    var passwordMeter = document.getElementById("spcm.passMeter");
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
    updatePasswordMeter(event.target.value.length);
});

function changePasswordSelfAJAX(){
    var oldPasswordTb = document.getElementById("spcm.oldPassword");
    var newPasswordTb = document.getElementById("spcm.newPassword");
    var confirmPasswordTb = document.getElementById("spcm.confirmPassword");

    if(newPasswordTb.value.length < 2){
        alert("La vecchia password è obbligatoria per il cambiamento.");
        return;
    }

    if(newPasswordTb.value.length < 8){
        alert("Password troppo corta. Il minimo è di 8 caratteri.");
        return;
    }

    if(newPasswordTb.value != confirmPasswordTb.value){
        alert("Le due password non corrispondono!");
        return;
    }

    // ajax update
}

function successReload(){
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('s', '1');
    window.location.search = urlParams;
}

if(location.href.includes("s=1")){
    toastr.success("Operazione completata con successo!");
    // WIP: removing the s=1 param as soon the page reloads
    /*window.history.replaceState(
        { additionalInformation: 'ok' }, 
        document.title, 
        nextURL
    );*/
}