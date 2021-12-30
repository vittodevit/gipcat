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

var viewCustomerModal = document.getElementById('viewCustomerModal');
viewCustomerModal.addEventListener('show.bs.modal', function (event) {
    document.getElementById("vcm.spinner").classList.remove("visually-hidden");
    var button = event.relatedTarget;
    var customerId = button.getAttribute('data-bs-vcmCid');
    var modalContent = document.getElementById('vcm.title');
    modalContent.textContent = customerId;
    var a;
    if(relativeToRoot != ""){
        a = relativeToRoot + "customers/"
    }
    $.ajax({
        type: "GET",
        url: a + 'ajax_get.php',
        data: { "customerId": customerId },
        success: function (dataget) {
            document.getElementById("vcm.businessName").value = !!dataget['businessName'] ? dataget['businessName'] : "";
            document.getElementById("vcm.registeredOfficeAddress").value = !!dataget['registeredOfficeAddress'] ? dataget['registeredOfficeAddress'] : "";
            document.getElementById("vcm.registeredOfficeCity").value = !!dataget['registeredOfficeCity'] ? dataget['registeredOfficeCity'] : "";
            document.getElementById("vcm.headquartersAddress").value = !!dataget['headquartersAddress'] ? dataget['headquartersAddress'] : "";
            document.getElementById("vcm.headquartersCity").value = !!dataget['headquartersCity'] ? dataget['headquartersCity'] : "";
            document.getElementById("vcm.homePhoneNumber").value = !!dataget['homePhoneNumber'] ? dataget['homePhoneNumber'] : "";
            document.getElementById("vcm.officePhoneNumber").value = !!dataget['officePhoneNumber'] ? dataget['officePhoneNumber'] : "";
            document.getElementById("vcm.privateMobilePhoneNumber").value = !!dataget['privateMobilePhoneNumber'] ? dataget['privateMobilePhoneNumber'] : "";
            document.getElementById("vcm.companyMobilePhoneNumber").value = !!dataget['companyMobilePhoneNumber'] ? dataget['companyMobilePhoneNumber'] : "";
            document.getElementById("vcm.privateEMail").value = !!dataget['privateEMail'] ? dataget['privateEMail'] : "";
            document.getElementById("vcm.companyEMail").value = !!dataget['companyEMail'] ? dataget['companyEMail'] : "";
            document.getElementById("vcm.fiscalCode").value = !!dataget['fiscalCode'] ? dataget['fiscalCode'] : "";
            document.getElementById("vcm.vatNumber").value = !!dataget['vatNumber'] ? dataget['vatNumber'] : "";
            document.getElementById("vcm.footNote").value = !!dataget['footNote'] ? dataget['footNote'] : "";
            document.getElementById("vcm.createdAt").innerHTML = dataget['createdAt'];
            document.getElementById("vcm.updatedAt").innerHTML = dataget['updatedAt'];
            document.getElementById("vcm.lastEditedBy").innerHTML = dataget['lastEditedBy'];
            document.getElementById("vcm.version").innerHTML = dataget['version'];
            document.getElementById("vcm.spinner").classList.add("visually-hidden");
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
});

var userDeleteModal = document.getElementById('userDeleteModal');
userDeleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var username = button.getAttribute('data-bs-username');
    var modalContent = document.getElementById('dum.title');
    modalContent.textContent = username;
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

function deleteUserAJAX(){
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/ajax_deluser.php',
        data: {
            "userName": document.getElementById('dum.title').innerText
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

// set persistent success message
function successReload(message){
    var msg;
    if(message == undefined){
        msg = "Operazione completata con successo."
    }
    localStorage.setItem("toastr.showSuccessToast", "true");
    localStorage.setItem("toastr.message", msg);
    location.reload();
}

// display persistent success message
if(localStorage.getItem("toastr.showSuccessToast") == "true"){
    localStorage.setItem("toastr.showSuccessToast", "false");
    toastr.success(localStorage.getItem("toastr.message"));
}