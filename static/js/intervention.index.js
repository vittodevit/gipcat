var createInterventionModal = document.getElementById('createInterventionModal');
createInterventionModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var idIntervention = button.getAttribute('data-bs-cimIid');
    var modalContent = document.getElementById('cim.title');
    modalContent.textContent = idIntervention;
});

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
