var version;

var doNotCallModal = document.getElementById('doNotCallModal');
if (doNotCallModal != null) {
    doNotCallModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var idInstallation = button.getAttribute('data-bs-dncIid');
        var modalContent = document.getElementById('dnc.title');
        modalContent.textContent = idInstallation;
        $.ajax({
            type: "GET",
            url: './installations/ajax_get.php',
            data: { "idInstallation": idInstallation },
            success: function (dataget) {
                version = dataget['version'];
            },
            error: function (data) {
                toastr.error(data.responseText);
            }
        });
    });
}

function editInstallationDNC_AJAX(idInstallation){
    $.ajax({
        type: "POST",
        url: './installations/ajax_edit.php',
        data: {
            "idInstallation": idInstallation,
            "version": version,
            "toCall": 0
        },
        success: function (data) {
            successReload();
        },
        error: function (data) {
            toastr.error(data.responseText);
        }
    });
    version = "";
}