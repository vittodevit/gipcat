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
document.getElementById("upcm.newPassword").addEventListener('input', (event) => {
    updatePasswordMeter("upcm.passMeter" ,event.target.value.length);
});

// password change data loading
var userPasswordChangeModal = document.getElementById('userPassChangeModal');
userPasswordChangeModal.addEventListener('show.bs.modal', function (event) {
    document.getElementById("upcm.spinner").classList.remove("visually-hidden");
    var button = event.relatedTarget;
    var username = button.getAttribute('data-bs-username');
    var modalContent = document.getElementById('upcm.title');
    modalContent.textContent = username;
    if(sessionUserName == username){
        document.getElementById('upcm.isSelf').value = 'true';
        document.getElementById('upcm.oldPasswordContainer').classList.remove("visually-hidden");
    }else{
        document.getElementById('upcm.isSelf').value = 'false';
        document.getElementById('upcm.oldPasswordContainer').classList.add("visually-hidden");
    }
    $.ajax({
        type: "GET",
        url: relativeToRoot + 'lib/ajax_readusermeta.php',
        data: { "userName": username },
        success: function (dataget) {
            document.getElementById("upcm.createdAt").innerHTML = dataget['createdAt'];
            document.getElementById("upcm.updatedAt").innerHTML = dataget['updatedAt'];
            document.getElementById("upcm.lastEditedBy").innerHTML = dataget['lastEditedBy'];
            document.getElementById("upcm.version").innerHTML = dataget['version'];
            document.getElementById("upcm.spinner").classList.add("visually-hidden");
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
});

function userChangePasswordAJAX(isSelf){
    var oldPasswordTb = document.getElementById("upcm.oldPassword");
    var newPasswordTb = document.getElementById("upcm.newPassword");
    var confirmPasswordTb = document.getElementById("upcm.confirmPassword");

    if(isSelf == "true"){
        if(oldPasswordTb.value.length < 2){
            toastr.error("La vecchia password è obbligatoria per il cambiamento.");
            return;
        }
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
            "userName": document.getElementById('upcm.title').innerText,
            "oldPassword": document.getElementById("upcm.oldPassword").value,
            "newPassword": document.getElementById("upcm.newPassword").value,
            "version": document.getElementById("upcm.version").innerText,
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