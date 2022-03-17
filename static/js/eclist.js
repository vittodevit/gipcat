let gxp = undefined;
let gstart = undefined;
let gend = undefined;

function editCardBody(body){
    document.getElementById("cardbody").innerHTML = body;
    feather.replace({ 'aria-hidden': 'true' })
}

function normalizeMonth(month){
    switch(month){
        case 1:
            return "Gennaio";
        case 2:
            return "Febbraio";
        case 3:
            return "Marzo";
        case 4:
            return "Aprile";
        case 5:
            return "Maggio";
        case 6:
            return "Giugno";
        case 7:
            return "Luglio";
        case 8:
            return "Agosto";
        case 9:
            return "Settembre";
        case 10:
            return "Ottobre";
        case 11:
            return "Novembre";
        case 12:
            return "Dicembre";
    }
}

var lastDay = function(y,m){
    return  new Date(y, m +1, 0).getDate();
}

function mkOpt(normalized, year, month, end=false){
    nm = normalizeMonth(parseInt(month));
    ld = lastDay(parseInt(year), parseInt(month));
    if(end){
        return `<option value="${normalized}-${ld}">${nm} ${year}</option>`;
    }else{
        return `<option value="${normalized}-01">${nm} ${year}</option>`;
    }
    
}

editCardBody(`
<div class="d-flex align-items-center">
    <h5 class="card-title">Caricamento intervalli</h5>
    <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
</div>
`);


// caricamento intervalli AJAX
$.ajax({
    type: "GET",
    url: '../lib/ajax_eclist.php',
    data: { "action": "getintervals" },
    success: function (dataget) {
        // caricamento
        body = `
        <div class="row mb-3">
            <div class="col">
                <label for="startbox">Mese di inzio:</label>
                <select class="form-select" id="startbox">
        `
        dataget.forEach(interval => {
            body += mkOpt(
                interval.normalized, 
                interval.year,
                interval.month
            )
        });
        
        body += `
                </select>
            </div>
            <div class="col">
                <label for="endbox">Mese di fine:</label>
                <select class="form-select" id="endbox">
        `

        dataget.forEach(interval => {
            body += mkOpt(
                interval.normalized, 
                interval.year,
                interval.month,
                true
            )
        });
        
        body += `
                </select>
            </div>
        </div>
        <div class="width-100 text-end">
            <button class="btn btn-primary" onclick="ajax_lista()">
                Scarica dati
                <span data-feather="download"></span>
            </button>
        </div>
        `

        editCardBody(body);
    },
    error: function (data) {
        editCardBody(`
        <div class="d-flex align-items-center">
            <h5 class="card-title">${data.responseText}</h5>
            <div class="spinner-border text-danger ms-auto" role="status" aria-hidden="true"></div>
        </div>
        `);
    }
});

function ajax_lista(){
    gstart = document.getElementById("startbox").value;
    gend = document.getElementById("endbox").value;

    editCardBody(`
    <div class="d-flex align-items-center">
        <h5 class="card-title">Scaricamento dati...</h5>
        <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
    </div>
    `);

    $.ajax({
        type: "GET",
        url: '../lib/ajax_eclist.php',
        data: { 
            "action": "getcalls",
            "start": gstart,
            "end": gend,
        },
        success: function (dataget) {
            callcount = dataget.length;
            gxp = dataget;
            editCardBody(`
            <h5 class="card-title">Pronto all'esportazione</h5>
            <p class="card-text">
            Sono state trovate <b>${callcount}</b> chiamate.
            </p>
            <div class="width-100 text-end">
                <button class="btn btn-secondary" onclick="window.location.reload()" style="margin-right: 6px;">
                    Ripeti
                    <span data-feather="refresh-cw"></span>
                </button>
                <button class="btn btn-primary" onclick="esporta()">
                    Esporta
                    <span data-feather="arrow-right"></span>
                </button>
            </div>
            `);
        },
        error: function (data) {
            editCardBody(`
            <div class="d-flex align-items-center">
                <h5 class="card-title">${data.responseText}</h5>
                <div class="spinner-border text-danger ms-auto" role="status" aria-hidden="true"></div>
            </div>
            `);
        }
    });

}

function modulo(obj){
    html = `
    <div class="card mb-3 scrollbar-w">
    <div class="card-header">
        <div class="row">
        <div class="col col-md-6">
            <span data-feather="phone-call"></span>
            <b>Chiamata per </b>
            ${obj.businessName}

            <span data-feather="user" class="it"></span>
            <b>C. CLI. </b>
            ${obj.idCustomer}

            <span data-feather="box" class="it"></span>
            <b>C. INST. </b>
            ${obj.idInstallation}
        </div>
    `
    // check rimando
    if(obj.associatedCallPosticipationDate){
        html += `
        <div class="col col-md-6 text-end">
        <a>
        <span data-feather="skip-forward"> </span>
        Rimandata al ${obj.associatedCallPosticipationDate}
        </a>
        </div>
        `
    }
    
    html += `</div>
    </div>
    <div class="card-body">
        <div class="row">
        <div class="col col-md-11">
            <span data-feather="file-text"></span>
            <b>Contratto di Manutenzione:</b>
            ${obj.manteinanceContractName}
            <br />
            <span data-feather="compass"></span>
            <b>Indirizzo Installazione:</b>
            ${obj.installationAddress} ${obj.installationCity}
            <br />
            <span data-feather="home"></span>
            <b>Tipo Installazione:</b>
            ${obj.installationType}
            <br />
            <span data-feather="box"></span>
            <b>Marca e Modello:</b>
            ${obj.heaterBrand} ${obj.heater}
        </div>
        </div>
        <hr style="margin-top: 8px; margin-bottom: 8px" />
        <span data-feather="calendar"></span>
        <b>Ultimo Intervento: </b>
        ${obj.interventionDate}
        <br />
        <span data-feather="tool"></span>
        <b>Tipo Ultimo Intervento</b>
        ${obj.interventionType}
    `
        // controllo note

        if(obj.associatedCallNote){
            html += `
            <hr style="margin-top: 8px; margin-bottom: 8px" />
            <span data-feather="paperclip"></span>
            <b>Note: </b>
            <br>
            ${obj.associatedCallNote}
            `
        }
    
    html +=`
    </div>
    </div>
    `
    return html;
}

function esporta(){

    html = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Stampa Elenco Chiamate</title>
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous"
        />
        <style>
        body {
            padding: 20px 20px 20px 20px;
        }
        .it{
            margin-left: 10px;
        }
        </style>
    </head>
    <body>
        <h3>Esportazione Elenco Chiamate - GIPCAT</h3>
        <ul>
        <li><b>Esportato da: </b> ${sessionUserName}</li>
        <li><b>Intervallo: </b> DAL <u>${gstart}</u> AL <u>${gend}</u></li>
        </ul>
    `
    // stampa moduli
    gxp.forEach(obj => {
        html += modulo(obj);
    });

    html += `
    </body>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
      integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
      crossorigin="anonymous"
    ></script>
    <script>
      var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
      );
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
      feather.replace({ "aria-hidden": "true" });
      window.print();
    </script>
    </html>
    `
    var tab = window.open('about:blank', '_blank');
    tab.document.write(html);
    tab.document.close();
}