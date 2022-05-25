let gxp = undefined;
let gstart = undefined;
let gend = undefined;
let dayoffset = localStorage.getItem('dayOffset');

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
    data: { "action": "getintervals" , "dayoffset" : dayoffset},
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
            "dayoffset" : dayoffset
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
    // parse data
    var options = {year: 'numeric', month: 'long', day: 'numeric' };
    ui = new Date(obj.interventionDate)
    ultimoIntervento = ui.toLocaleDateString("it-IT", options);
    // contatti
    opzContatto = []
    if(obj.companyMobilePhoneNumber){
        opzContatto.push(obj.companyMobilePhoneNumber)
    }
    if(obj.homePhoneNumber){
        opzContatto.push(obj.homePhoneNumber)
    }
    if(obj.officePhoneNumber){
        opzContatto.push(obj.officePhoneNumber)
    }
    if(obj.privateMobilePhoneNumber){
        opzContatto.push(obj.privateMobilePhoneNumber)
    }
    // html
    return `
    <tr>
        <td>
            <input type="checkbox">
        </td>
        <td>${obj.businessName}</td>
        <td>${opzContatto.join(", ")}</td>
        <td>${obj.heaterBrand + " " + obj.heater}</td>
        <td>${ultimoIntervento}</td>
        <td>${obj.installationAddress}</td>
        <td>${obj.installationCity}</td>
    </tr>
    `;
}

function esporta(){

    html = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Stampa Elenco Chiamate</title> 
        <style>
        body {
            padding: 20px 20px 20px 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 3px;
            border-collapse: collapse;
        }
        </style>
    </head>
    <body>
        <h3>Esportazione Elenco Chiamate - GIPCAT</h3>
        <ul>
        <li><b>Esportato da: </b> ${sessionUserName}</li>
        <li><b>Intervallo: </b> DAL <u>${gstart}</u> AL <u>${gend}</u></li>
        </ul>
        <table>
        <thead>
            <tr>
                <td></td>
                <td>Nome e Cognome</td>
                <td>Contatti</td>
                <td>Caldaia</td>
                <td>Ultimo Intervento</td>
                <td>Indirizzo</td>
                <td>Città</td>
            </tr>
        </thead>
        <tbody>    
    `
    // stampa moduli
    gxp.forEach(obj => {
        html += modulo(obj);
    });

    html += `
        </tbody>
    </table>
    </body>
    <script>
      window.print();
    </script>
    </html>
    `
    var tab = window.open('about:blank', '_blank');
    tab.document.write(html);
    tab.document.close();
}