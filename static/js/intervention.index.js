var deleteInterventionModal = document.getElementById('deleteInterventionModal');
deleteInterventionModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var idIntervention = button.getAttribute('data-bs-dimIid');
    var modalContent = document.getElementById('dim.title');
    modalContent.textContent = idIntervention;
});

var createInterventionModal = document.getElementById('createInterventionModal');
createInterventionModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var idIntervention = button.getAttribute('data-bs-cimIid');
    var modalContent = document.getElementById('cim.title');
    modalContent.textContent = idIntervention;
});

var editInterventionModal = document.getElementById('editInterventionModal');
editInterventionModal.addEventListener('show.bs.modal', function (event) {
    document.getElementById("eim.spinner").classList.remove("visually-hidden");
    var button = event.relatedTarget;
    var idIntervention = button.getAttribute('data-bs-eimIid');
    var modalContent = document.getElementById('eim.title');
    modalContent.textContent = idIntervention;
    $.ajax({
        type: "GET",
        url: './ajax_get.php',
        data: { "idIntervention": idIntervention },
        success: function (dataget) {
                document.getElementById("eim.idInstallation").innerHTML = dataget['idInstallation'];
                document.getElementById("eim.interventionType").value = !!dataget['interventionType'] ? dataget['interventionType'] : "";
                document.getElementById("eim.interventionState").value = !!dataget['interventionState'] ? dataget['interventionState'] : "";
                document.getElementById("eim.assignedTo").value = !!dataget['assignedTo'] ? dataget['assignedTo'] : "";
                document.getElementById("eim.protocolNumber").value = !!dataget['protocolNumber'] ? dataget['protocolNumber'] : "";
                document.getElementById("eim.billingNumber").value = !!dataget['billingNumber'] ? dataget['billingNumber'] : "";
                // mapping db's 0 and 1 to true and false
                document.getElementById("eim.countInCallCycle").checked = !!dataget['countInCallCycle'] && dataget['countInCallCycle'] == 1 ? true : false;
                // DATES //
                document.getElementById("eim.interventionDate").value = !!dataget['interventionDate'] ? dataget['interventionDate'] : "";
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
                document.getElementById("eim.spinner").classList.add("visually-hidden");
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
})

function deleteInterventionAJAX(idIntervention) {
    $.ajax({
        type: "POST",
        url: './ajax_delete.php',
        data: { "idIntervention": idIntervention },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

function createInterventionAJAX() {
    $.ajax({
        type: "POST",
        url: './ajax_create.php',
        data: {
            "idInstallation": document.getElementById("cim.title").innerText,
            "interventionType": document.getElementById("interventionType").value,
            "interventionState": document.getElementById("interventionState").value,
            "assignedTo": document.getElementById("assignedTo").value,
            "countInCallCycle": document.getElementById("countInCallCycle").checked ? 1 : 0,
            "interventionDate": document.getElementById("interventionDate").value,
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

function editInterventionAjax(idIntervention, version) {
    $.ajax({
        type: "POST",
        url: './ajax_edit.php',
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
            "interventionDate": document.getElementById("eim.interventionDate").value,
            // substring to get only date not time
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