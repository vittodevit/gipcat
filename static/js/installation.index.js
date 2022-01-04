var deleteInstallationModal = document.getElementById('deleteInstallationModal');
deleteInstallationModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var idInstallation = button.getAttribute('data-bs-dimIid');
    var modalContent = document.getElementById('dim.title');
    modalContent.textContent = idInstallation;
});

var createInstallationModal = document.getElementById('createInstallationModal');
createInstallationModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var idCustomer = button.getAttribute('data-bs-cimIid');
    var modalContent = document.getElementById('cim.title');
    modalContent.textContent = idCustomer;
});

var editInstallationModal = document.getElementById('editInstallationModal');
editInstallationModal.addEventListener('show.bs.modal', function (event) {
    document.getElementById("eim.spinner").classList.remove("visually-hidden");
    var button = event.relatedTarget;
    var idInstallation = button.getAttribute('data-bs-eimIid');
    var modalContent = document.getElementById('eim.title');
    modalContent.textContent = idInstallation;
    $.ajax({
        type: "GET",
        url: './ajax_get.php',
        data: { "idInstallation": idInstallation },
        success: function (dataget) {
            document.getElementById("eim.idCustomer").innerHTML = dataget['idCustomer'];
            document.getElementById("eim.installationAddress").placeholder = !!dataget['installationAddress'] ? dataget['installationAddress'] : "";
            document.getElementById("eim.installationCity").placeholder = !!dataget['installationCity'] ? dataget['installationCity'] : "";
            document.getElementById("eim.heaterBrand").placeholder = !!dataget['heaterBrand'] ? dataget['heaterBrand'] : "";
            document.getElementById("eim.heater").placeholder = !!dataget['heater'] ? dataget['heater'] : "";
            document.getElementById("eim.heaterSerialNumber").placeholder = !!dataget['heaterSerialNumber'] ? dataget['heaterSerialNumber'] : "";
            document.getElementById("eim.installationType").value = !!dataget['installationType'] ? dataget['installationType'] : "";
            document.getElementById("eim.manteinanceContractName").placeholder = !!dataget['manteinanceContractName'] ? dataget['manteinanceContractName'] : "";
            // mapping db's 0 and 1 to true and false
            document.getElementById("eim.toCall").checked = !!dataget['toCall'] && dataget['toCall'] == 1 ? true : false;
            document.getElementById("eim.monthlyCallInterval").placeholder = !!dataget['monthlyCallInterval'] ? dataget['monthlyCallInterval'] : "";
            // substring to get only date not time
            document.getElementById("eim.contractExpiryDate").value = !!dataget['contractExpiryDate'] ? dataget['contractExpiryDate'].substring(0, 10) : "";
            document.getElementById("eim.footNote").value = !!dataget['footNote'] ? dataget['footNote'] : "";
            document.getElementById("eim.createdAt").innerHTML = dataget['createdAt'];
            document.getElementById("eim.updatedAt").innerHTML = dataget['updatedAt'];
            document.getElementById("eim.lastEditedBy").innerHTML = dataget['lastEditedBy'];
            document.getElementById("eim.version").innerHTML = dataget['version'];
            document.getElementById("eim.spinner").classList.add("visually-hidden");
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
})

function deleteInstallationAJAX(idInstallation) {
    $.ajax({
        type: "POST",
        url: './ajax_delete.php',
        data: { "idInstallation": idInstallation },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

function createInstallationAJAX() {
    $.ajax({
        type: "POST",
        url: './ajax_create.php',
        data: {
            "idCustomer": document.getElementById("cim.title").innerText,
            "installationAddress": document.getElementById("installationAddress").value,
            "installationCity": document.getElementById("installationCity").value,
            "heaterBrand": document.getElementById("heaterBrand").value,
            "heater": document.getElementById("heater").value,
            "heaterSerialNumber": document.getElementById("heaterSerialNumber").value,
            "installationType": document.getElementById("installationType").value,
            "manteinanceContractName": document.getElementById("manteinanceContractName").value,
            "toCall": document.getElementById("toCall").checked ? 1 : 0,
            "monthlyCallInterval": document.getElementById("monthlyCallInterval").value,
            "contractExpiryDate": document.getElementById("contractExpiryDate").value,
            "footNote": document.getElementById("footNote").value
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

function editInstallationAjax(idInstallation, version) {
    $.ajax({
        type: "POST",
        url: './ajax_edit.php',
        data: {
            "idInstallation": idInstallation,
            "version": version,
            "installationAddress": document.getElementById("eim.installationAddress").value,
            "installationCity": document.getElementById("eim.installationCity").value,
            "heaterBrand": document.getElementById("heaterBrand").value,
            "heater": document.getElementById("heater").value,
            "heaterSerialNumber": document.getElementById("heaterSerialNumber").value,
            "installationType": document.getElementById("eim.installationType").value,
            "manteinanceContractName": document.getElementById("eim.manteinanceContractName").value,
            "toCall": document.getElementById("eim.toCall").checked ? 1 : 0,
            "monthlyCallInterval": document.getElementById("eim.monthlyCallInterval").value,
            "contractExpiryDate": document.getElementById("eim.contractExpiryDate").value,
            "footNote": document.getElementById("eim.footNote").value
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}