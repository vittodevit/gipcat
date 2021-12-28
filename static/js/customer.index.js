var deleteCustomerModal = document.getElementById('deleteCustomerModal')
deleteCustomerModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget
    var customerId = button.getAttribute('data-bs-dcmCid')
    var modalContent = document.getElementById('deleteCustomerModalBody')
    modalContent.textContent = customerId
})

var editCustomerModal = document.getElementById('editCustomerModal')
editCustomerModal.addEventListener('show.bs.modal', function (event) {
    document.getElementById("ecm.spinner").classList.remove("visually-hidden");
    var button = event.relatedTarget
    var customerId = button.getAttribute('data-bs-ecmCid')
    var modalContent = document.getElementById('ecm.title')
    modalContent.textContent = customerId
    $.ajax({
        type: "GET",
        url: './ajax_get.php',
        data: { "customerId": customerId },
        success: function (dataget) {
            document.getElementById("ecm.businessName").placeholder = !!dataget['businessName'] ? dataget['businessName'] : "";
            document.getElementById("ecm.registeredOfficeAddress").placeholder = !!dataget['registeredOfficeAddress'] ? dataget['registeredOfficeAddress'] : "";
            document.getElementById("ecm.registeredOfficeCity").placeholder = !!dataget['registeredOfficeCity'] ? dataget['registeredOfficeCity'] : "";
            document.getElementById("ecm.headquartersAddress").placeholder = !!dataget['headquartersAddress'] ? dataget['headquartersAddress'] : "";
            document.getElementById("ecm.headquartersCity").placeholder = !!dataget['headquartersCity'] ? dataget['headquartersCity'] : "";
            document.getElementById("ecm.homePhoneNumber").placeholder = !!dataget['homePhoneNumber'] ? dataget['homePhoneNumber'] : "";
            document.getElementById("ecm.officePhoneNumber").placeholder = !!dataget['officePhoneNumber'] ? dataget['officePhoneNumber'] : "";
            document.getElementById("ecm.privateMobilePhoneNumber").placeholder = !!dataget['privateMobilePhoneNumber'] ? dataget['privateMobilePhoneNumber'] : "";
            document.getElementById("ecm.companyMobilePhoneNumber").placeholder = !!dataget['companyMobilePhoneNumber'] ? dataget['companyMobilePhoneNumber'] : "";
            document.getElementById("ecm.privateEMail").placeholder = !!dataget['privateEMail'] ? dataget['privateEMail'] : "";
            document.getElementById("ecm.companyEMail").placeholder = !!dataget['companyEMail'] ? dataget['companyEMail'] : "";
            document.getElementById("ecm.fiscalCode").placeholder = !!dataget['fiscalCode'] ? dataget['fiscalCode'] : "";
            document.getElementById("ecm.vatNumber").placeholder = !!dataget['vatNumber'] ? dataget['vatNumber'] : "";
            document.getElementById("ecm.footNote").value = !!dataget['footNote'] ? dataget['footNote'] : "";
            document.getElementById("ecm.createdAt").innerHTML = dataget['createdAt'];
            document.getElementById("ecm.updatedAt").innerHTML = dataget['updatedAt'];
            document.getElementById("ecm.lastEditedBy").innerHTML = dataget['lastEditedBy'];
            document.getElementById("ecm.version").innerHTML = dataget['version'];
            document.getElementById("ecm.spinner").classList.add("visually-hidden");
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
})

function deleteCustomerAJAX(customerId) {
    $.ajax({
        type: "POST",
        url: './ajax_delete.php',
        data: { "customerId": customerId },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}

function createCustomerAJAX() {
    $.ajax({
        type: "POST",
        url: './ajax_create.php',
        data: {
            "businessName": document.getElementById("businessName").value,
            "registeredOfficeAddress": document.getElementById("registeredOfficeAddress").value,
            "registeredOfficeCity": document.getElementById("registeredOfficeCity").value,
            "headquartersAddress": document.getElementById("headquartersAddress").value,
            "headquartersCity": document.getElementById("headquartersCity").value,
            "homePhoneNumber": document.getElementById("homePhoneNumber").value,
            "officePhoneNumber": document.getElementById("officePhoneNumber").value,
            "privateMobilePhoneNumber": document.getElementById("privateMobilePhoneNumber").value,
            "companyMobilePhoneNumber": document.getElementById("companyMobilePhoneNumber").value,
            "privateEMail": document.getElementById("privateEMail").value,
            "companyEMail": document.getElementById("companyEMail").value,
            "fiscalCode": document.getElementById("fiscalCode").value,
            "vatNumber": document.getElementById("vatNumber").value,
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

function editCustomerAjax(customerId, version) {
    $.ajax({
        type: "POST",
        url: './ajax_edit.php',
        data: {
            "customerId": customerId,
            "version": version,
            "businessName": document.getElementById("ecm.businessName").value,
            "registeredOfficeAddress": document.getElementById("ecm.registeredOfficeAddress").value,
            "registeredOfficeCity": document.getElementById("ecm.registeredOfficeCity").value,
            "headquartersAddress": document.getElementById("ecm.headquartersAddress").value,
            "headquartersCity": document.getElementById("ecm.headquartersCity").value,
            "homePhoneNumber": document.getElementById("ecm.homePhoneNumber").value,
            "officePhoneNumber": document.getElementById("ecm.officePhoneNumber").value,
            "privateMobilePhoneNumber": document.getElementById("ecm.privateMobilePhoneNumber").value,
            "companyMobilePhoneNumber": document.getElementById("ecm.companyMobilePhoneNumber").value,
            "privateEMail": document.getElementById("ecm.privateEMail").value,
            "companyEMail": document.getElementById("ecm.companyEMail").value,
            "fiscalCode": document.getElementById("ecm.fiscalCode").value,
            "vatNumber": document.getElementById("ecm.vatNumber").value,
            "footNote": document.getElementById("ecm.footNote").value,
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
}