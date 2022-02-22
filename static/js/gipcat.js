feather.replace({ 'aria-hidden': 'true' })

// Attachment Management System //
var windowHasFocus;

$(window).focus(function () {
    windowHasFocus = true;
}).blur(function () {
    windowHasFocus = false;
});

function amsLaunch(amsid) {
    window.location = "gipcat-ams://" + amsid;
    setTimeout(function () {
        if (windowHasFocus) {
            toastr.error("La macchina non è configurata per l'uso di AMS.");
        }
    }, 100);
}

function updatePasswordMeter(target, length) {
    // calc values
    var pvalue = length < 16 ? length * 6 : 100;
    var pcolor = "";
    if (length > 11) {
        pcolor = "success";
    } else {
        if (length > 7) {
            pcolor = "warning";
        } else {
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

function selectTechnician(obj, prefix){
    document.getElementById(prefix + "assignedTo").value = obj.value;
}

function createTableRow(prefix, row){
    var html = "";
    if(row.isBusy == 1){
        addon = "style=\"background-color: rgba(255, 0, 0, 0.1);\"";
    }else{
        addon = "style=\"background-color: rgba(0, 255, 0, 0.1);\"";
    }
    html += `<td ${addon}>`;
    html += `<input type="radio" name="radioAssignedTo" value="${row.userName}" id="${prefix + "radio." + row.userName}" onChange="selectTechnician(this, '${prefix}')">`;
    html += "</td>";
    html += `<td ${addon}>`;
    html += `<span style='color: ${row.color};'>&#9632;</span> `;
    html += row.userName;
    html += "</td>";
    html += `<td ${addon}>`;
    html += row.legalName;
    html += "</td>";
    html += `<td ${addon}>`;
    html += row.legalSurname;
    html += "</td>";
    html += `<td ${addon}>`;
    if(row.isBusy == 1){
        html += "<span style='color: red;'>Occupato</span>";
    }else{
        html += "<span style='color: green;'>Libero</span>";
    }
    html += "</td>";
    return html;
}

// callback function to retrieve and update DOM table for technichian overlaps in interventions
function manageOverlapsAJAX(prefix, assignedTo) {
    $.ajax({
        type: "GET",
        url: relativeToRoot + 'lib/ajax_checkoverlap.php',
        data: { 
            "interventionDate": document.getElementById(prefix + "interventionDate").value + " " +
                                document.getElementById(prefix + "interventionTime").value,
            "interventionDuration": document.getElementById(prefix + "interventionDuration").value
        },
        success: function (dataget) {
            var ot = document.getElementById(prefix + "overlapTable");
            ot.innerHTML = "";
            document.getElementById(prefix + "assignedTo").value = "";
            //nessuno
            var nobody = "";
            nobody += "<tr>";
            nobody += "<td>";
            nobody += `<input type="radio" name="radioAssignedTo" value="" id="${prefix + "radio."}" onChange="selectTechnician(this, '${prefix}')">`;
            nobody += "</td>";
            nobody += "<td colspan='4'>Nessuno</td>";
            nobody += "</tr>";
            ot.innerHTML += nobody;
            //tecnici
            dataget.forEach(row => {
                ot.innerHTML += "<tr>";
                ot.innerHTML += createTableRow(prefix, row);
                ot.innerHTML += "</tr>";
            });
            if(prefix != ""){
                try{
                    document.getElementById(prefix + "radio." + assignedTo).checked = true;
                }catch{
                    // lol
                }
            }
            document.getElementById(prefix+"spinner").classList.add("visually-hidden");
            document.getElementById(prefix + "assignedTo").value = assignedTo;
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

// add event listener for passwordMeter update
if(document.getElementById("upcm.newPassword") != null){
    document.getElementById("upcm.newPassword").addEventListener('input', (event) => {
        updatePasswordMeter("upcm.passMeter", event.target.value.length);
    });
}

// password change data loading
var userPasswordChangeModal = document.getElementById('userPassChangeModal');
if(userPasswordChangeModal != null){
    userPasswordChangeModal.addEventListener('show.bs.modal', function (event) {
        document.getElementById("upcm.spinner").classList.remove("visually-hidden");
        var button = event.relatedTarget;
        var username = button.getAttribute('data-bs-username');
        var modalContent = document.getElementById('upcm.title');
        modalContent.textContent = username;
        if (sessionUserName == username) {
            document.getElementById('upcm.isSelf').value = 'true';
            document.getElementById('upcm.oldPasswordContainer').classList.remove("visually-hidden");
        } else {
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
}

var viewCustomerModal = document.getElementById('viewCustomerModal');
viewCustomerModal.addEventListener('show.bs.modal', function (event) {
    document.getElementById("vcm.spinner").classList.remove("visually-hidden");
    var button = event.relatedTarget;
    var customerId = button.getAttribute('data-bs-vcmCid');
    var modalContent = document.getElementById('vcm.title');
    modalContent.textContent = customerId;
    var a;
    if (relativeToRoot != "") {
        a = relativeToRoot + "customers/"
    } else {
        a = "./customers/"
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

var viewInstallationModal = document.getElementById('viewInstallationModal');
viewInstallationModal.addEventListener('show.bs.modal', function (event) {
    document.getElementById("vim.spinner").classList.remove("visually-hidden");
    var button = event.relatedTarget;
    var idInstallation = button.getAttribute('data-bs-vimIid');
    var modalContent = document.getElementById('vim.title');
    modalContent.textContent = idInstallation;
    var a;
    if (relativeToRoot != "") {
        a = relativeToRoot + "installations/"
    } else {
        a = "./installations/"
    }
    $.ajax({
        type: "GET",
        url: a + 'ajax_get.php',
        data: { "idInstallation": idInstallation },
        success: function (dataget) {
            document.getElementById("vim.idCustomer").innerHTML = dataget['idCustomer'];
            document.getElementById("vim.installationAddress").value = !!dataget['installationAddress'] ? dataget['installationAddress'] : "";
            document.getElementById("vim.installationCity").value = !!dataget['installationCity'] ? dataget['installationCity'] : "";
            document.getElementById("vim.heaterBrand").value = !!dataget['heaterBrand'] ? dataget['heaterBrand'] : "";
            document.getElementById("vim.heater").value = !!dataget['heater'] ? dataget['heater'] : "";
            document.getElementById("vim.heaterSerialNumber").value = !!dataget['heaterSerialNumber'] ? dataget['heaterSerialNumber'] : "";
            document.getElementById("vim.installationType").value = !!dataget['installationType'] ? dataget['installationType'] : "";
            document.getElementById("vim.manteinanceContractName").value = !!dataget['manteinanceContractName'] ? dataget['manteinanceContractName'] : "";
            // mapping db's 0 and 1 to true and false
            document.getElementById("vim.toCall").checked = !!dataget['toCall'] && dataget['toCall'] == 1 ? true : false;
            document.getElementById("vim.monthlyCallInterval").value = !!dataget['monthlyCallInterval'] ? dataget['monthlyCallInterval'] + " MESI" : "";
            // substring to get only date not time
            document.getElementById("vim.contractExpiryDate").value = !!dataget['contractExpiryDate'] ? dataget['contractExpiryDate'].substring(0, 10) : "";
            document.getElementById("vim.footNote").value = !!dataget['footNote'] ? dataget['footNote'] : "";
            document.getElementById("vim.createdAt").innerHTML = dataget['createdAt'];
            document.getElementById("vim.updatedAt").innerHTML = dataget['updatedAt'];
            document.getElementById("vim.lastEditedBy").innerHTML = dataget['lastEditedBy'];
            document.getElementById("vim.version").innerHTML = dataget['version'];
            document.getElementById("vim.spinner").classList.add("visually-hidden");
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
})

var userDeleteModal = document.getElementById('userDeleteModal');
if(userDeleteModal != null){
    userDeleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var username = button.getAttribute('data-bs-username');
        var modalContent = document.getElementById('dum.title');
        modalContent.textContent = username;
    });
}

function userChangePasswordAJAX(isSelf) {
    var oldPasswordTb = document.getElementById("upcm.oldPassword");
    var newPasswordTb = document.getElementById("upcm.newPassword");
    var confirmPasswordTb = document.getElementById("upcm.confirmPassword");

    if (isSelf == "true") {
        if (oldPasswordTb.value.length < 2) {
            toastr.error("La vecchia password è obbligatoria per il cambiamento.");
            return;
        }
    }

    if (newPasswordTb.value.length < 8) {
        toastr.error("Password troppo corta. Il minimo è di 8 caratteri.");
        return;
    }

    if (newPasswordTb.value != confirmPasswordTb.value) {
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

function deleteUserAJAX() {
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
function successReload(message) {
    var msg;
    if (message == undefined) {
        msg = "Operazione completata con successo."
    }
    localStorage.setItem("toastr.showSuccessToast", "true");
    localStorage.setItem("toastr.message", msg);
    location.reload();
}

// display persistent success message
if (localStorage.getItem("toastr.showSuccessToast") == "true") {
    localStorage.setItem("toastr.showSuccessToast", "false");
    toastr.success(localStorage.getItem("toastr.message"));
}

var deleteInterventionModal = document.getElementById('deleteInterventionModal');
if (deleteInterventionModal != null) {
    deleteInterventionModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var idIntervention = button.getAttribute('data-bs-dimIid');
        var modalContent = document.getElementById('dim.title');
        modalContent.textContent = idIntervention;
    });
}


var editInterventionModal = document.getElementById('editInterventionModal');
if (editInterventionModal != null) {
    editInterventionModal.addEventListener('show.bs.modal', function (event) {
        document.getElementById("eim.spinner").classList.remove("visually-hidden");
        var button = event.relatedTarget;
        var idIntervention = button.getAttribute('data-bs-eimIid');
        var modalContent = document.getElementById('eim.title');
        modalContent.textContent = idIntervention;
        $.ajax({
            type: "GET",
            url: relativeToRoot + 'lib/int/ajax_get.php',
            data: { "idIntervention": idIntervention },
            success: function (dataget) {
                document.getElementById("eim.idInstallation").innerHTML = dataget['idInstallation'];
                document.getElementById("eim.interventionType").value = !!dataget['interventionType'] ? dataget['interventionType'] : "";
                document.getElementById("eim.interventionState").value = !!dataget['interventionState'] ? dataget['interventionState'] : "";
                document.getElementById("eim.protocolNumber").value = !!dataget['protocolNumber'] ? dataget['protocolNumber'] : "";
                document.getElementById("eim.billingNumber").value = !!dataget['billingNumber'] ? dataget['billingNumber'] : "";
                // mapping db's 0 and 1 to true and false
                document.getElementById("eim.countInCallCycle").checked = !!dataget['countInCallCycle'] && dataget['countInCallCycle'] == 1 ? true : false;
                // DATES //
                document.getElementById("eim.interventionDate").value = !!dataget['interventionDate'] ? dataget['interventionDate'] : "";
                // TIME //
                document.getElementById("eim.interventionTime").value = dataget['interventionTime'] + ":00";
                // DATES //
                document.getElementById("eim.interventionDuration").value = dataget['interventionDuration'];
                // substring to get only date not time
                document.getElementById("eim.shipmentDate").value = !!dataget['shipmentDate'] ? dataget['shipmentDate'].substring(0, 10) : "";
                document.getElementById("eim.billingDate").value = !!dataget['billingDate'] ? dataget['billingDate'].substring(0, 10) : "";
                document.getElementById("eim.paymentDate").value = !!dataget['paymentDate'] ? dataget['paymentDate'].substring(0, 10) : "";
                //bottom
                document.getElementById("eim.footNote").value = !!dataget['footNote'] ? dataget['footNote'] : "";
                document.getElementById("eim.createdAt").innerHTML = dataget['createdAt'];
                document.getElementById("eim.updatedAt").innerHTML = dataget['updatedAt'];
                document.getElementById("eim.lastEditedBy").innerHTML = dataget['lastEditedBy'];
                document.getElementById("eim.version").innerHTML = dataget['version'];
                manageOverlapsAJAX("eim.", dataget['assignedTo']);
                document.getElementById("eim.spinner").classList.add("visually-hidden");
            },
            error: function (data) {
                toastr.error(data.responseText);
            }
        });
    })
}

function deleteInterventionAJAX(idIntervention) {
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/int/ajax_delete.php',
        data: { "idIntervention": idIntervention },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

function editInterventionAjax(idIntervention, version) {
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/int/ajax_edit.php',
        data: {
            "idIntervention": idIntervention,
            "version": version,
            "interventionType": document.getElementById("eim.interventionType").value,
            "interventionState": document.getElementById("eim.interventionState").value,
            "assignedTo": document.getElementById("eim.assignedTo").value,
            "protocolNumber": document.getElementById("eim.protocolNumber").value,
            "billingNumber": document.getElementById("eim.billingNumber").value,
            // mapping db's 0 and 1 to true and false
            "countInCallCycle": document.getElementById("eim.countInCallCycle").checked ? 1 : 0,
            // DATES //
            "interventionDate": document.getElementById("eim.interventionDate").value + " " +
                                document.getElementById("eim.interventionTime").value,
            "interventionDuration": document.getElementById("eim.interventionDuration").value,
            // other dates
            "shipmentDate": document.getElementById("eim.shipmentDate").value,
            "billingDate": document.getElementById("eim.billingDate").value,
            "paymentDate": document.getElementById("eim.paymentDate").value,
            //bottom
            "footNote": document.getElementById("eim.footNote").value,
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

var createInterventionModal = document.getElementById('createInterventionModal');
if (createInterventionModal != null) {
    createInterventionModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var idIntervention = button.getAttribute('data-bs-cimIid');
        var modalContent = document.getElementById('cim.title');
        modalContent.textContent = idIntervention;
        manageOverlapsAJAX("");
    });
}

function createInterventionAJAX() {
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/int/ajax_create.php',
        data: {
            "idInstallation": document.getElementById("cim.title").innerText,
            "interventionType": document.getElementById("interventionType").value,
            "interventionState": document.getElementById("interventionState").value,
            "assignedTo": document.getElementById("assignedTo").value,
            "countInCallCycle": document.getElementById("countInCallCycle").checked ? 1 : 0,
            "interventionDate": document.getElementById("interventionDate").value + " " +
                                document.getElementById("interventionTime").value,
            "interventionDuration": document.getElementById("interventionDuration").value,
            "shipmentDate": document.getElementById("shipmentDate").value,
            "protocolNumber": document.getElementById("protocolNumber").value,
            "billingDate": document.getElementById("billingDate").value,
            "billingNumber": document.getElementById("billingNumber").value,
            "paymentDate": document.getElementById("paymentDate").value,
            "footNote": document.getElementById("footNote").value,
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

// not bound interventions (generics) //

var deleteInterventionNBModal = document.getElementById('deleteInterventionNBModal');
if (deleteInterventionNBModal != null) {
    deleteInterventionNBModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var idIntervention = button.getAttribute('data-bs-dinbmIid');
        var modalContent = document.getElementById('dinbm.title');
        modalContent.textContent = idIntervention;
    });
}

var editInterventionNBModal = document.getElementById('editInterventionNBModal');
if (editInterventionNBModal != null) {
    editInterventionNBModal.addEventListener('show.bs.modal', function (event) {
        document.getElementById("einbm.spinner").classList.remove("visually-hidden");
        var button = event.relatedTarget;
        var idIntervention = button.getAttribute('data-bs-einbmIid');
        var modalContent = document.getElementById('einbm.title');
        modalContent.textContent = idIntervention;
        $.ajax({
            type: "GET",
            url: relativeToRoot + 'lib/int_nb/ajax_get.php',
            data: { "idIntervention": idIntervention },
            success: function (dataget) {
                document.getElementById("einbm.interventionState").value = !!dataget['interventionState'] ? dataget['interventionState'] : "";
                document.getElementById("einbm.interventionDate").value = !!dataget['interventionDate'] ? dataget['interventionDate'] : "";
                // TIME //
                document.getElementById("einbm.interventionTime").value = dataget['interventionTime'] + ":00";
                // DATES //
                document.getElementById("einbm.interventionDuration").value = dataget['interventionDuration'];
                //bottom
                document.getElementById("einbm.footNote").value = !!dataget['footNote'] ? dataget['footNote'] : "";
                document.getElementById("einbm.createdAt").innerHTML = dataget['createdAt'];
                document.getElementById("einbm.updatedAt").innerHTML = dataget['updatedAt'];
                document.getElementById("einbm.lastEditedBy").innerHTML = dataget['lastEditedBy'];
                document.getElementById("einbm.version").innerHTML = dataget['version'];
                manageOverlapsAJAX("einbm.", dataget['assignedTo']);
                document.getElementById("einbm.spinner").classList.add("visually-hidden");
            },
            error: function (data) {
                toastr.error(data.responseText);
            }
        });
    })
}

function deleteInterventionNBAJAX(idIntervention) {
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/int_nb/ajax_delete.php',
        data: { "idIntervention": idIntervention },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

function editInterventionNBAjax(idIntervention, version) {
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/int_nb/ajax_edit.php',
        data: {
            "idIntervention": idIntervention,
            "version": version,
            "interventionState": document.getElementById("einbm.interventionState").value,
            "assignedTo": document.getElementById("einbm.assignedTo").value,
            // DATES //
            "interventionDate": document.getElementById("einbm.interventionDate").value + " " +
                                document.getElementById("einbm.interventionTime").value,
            "interventionDuration": document.getElementById("einbm.interventionDuration").value,
            //bottom
            "footNote": document.getElementById("einbm.footNote").value,
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

var createInterventionNBModal = document.getElementById('createInterventionNBModal');
if (createInterventionNBModal != null) {
    createInterventionNBModal.addEventListener('show.bs.modal', function (event) {
        manageOverlapsAJAX("cinbm.");
    });
}

function createInterventionNBAJAX() {
    $.ajax({
        type: "POST",
        url: relativeToRoot + 'lib/int_nb/ajax_create.php',
        data: {
            "interventionState": document.getElementById("cinbm.interventionState").value,
            "assignedTo": document.getElementById("cinbm.assignedTo").value,
            "interventionDate": document.getElementById("cinbm.interventionDate").value + " " +
                                document.getElementById("cinbm.interventionTime").value,
            "interventionDuration": document.getElementById("cinbm.interventionDuration").value,
            "footNote": document.getElementById("cinbm.footNote").value,
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

/// add event listeners  for technician table update (create intervention) ///
document.getElementById("interventionDate").addEventListener('input', (event) => {
    document.getElementById("spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("");
});

document.getElementById("interventionTime").addEventListener('input', (event) => {
    document.getElementById("spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("");
});

document.getElementById("interventionDuration").addEventListener('input', (event) => {
    document.getElementById("spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("");
});

/// add event listeners  for technician table update (update intervention) ///
document.getElementById("eim.interventionDate").addEventListener('input', (event) => {
    document.getElementById("eim.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("eim.");
});

document.getElementById("eim.interventionTime").addEventListener('input', (event) => {
    document.getElementById("eim.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("eim.");
});

document.getElementById("eim.interventionDuration").addEventListener('input', (event) => {
    document.getElementById("eim.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("eim.");
});

/// add event listeners  for technician table update (create intervention NB) ///
document.getElementById("interventionDate").addEventListener('input', (event) => {
    document.getElementById("cinbm.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("cinbm.");
});

document.getElementById("interventionTime").addEventListener('input', (event) => {
    document.getElementById("cinbm.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("cinbm.");
});

document.getElementById("interventionDuration").addEventListener('input', (event) => {
    document.getElementById("cinbm.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("cinbm.");
});

/// add event listeners  for technician table update (edit intervention NB) ///
document.getElementById("einbm.interventionDate").addEventListener('input', (event) => {
    document.getElementById("einbm.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("einbm.");
});

document.getElementById("einbm.interventionTime").addEventListener('input', (event) => {
    document.getElementById("eim.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("einbm.");
});

document.getElementById("einbm.interventionDuration").addEventListener('input', (event) => {
    document.getElementById("eim.spinner").classList.remove("visually-hidden");
    manageOverlapsAJAX("einbm.");
});
