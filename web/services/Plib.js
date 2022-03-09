export default class Plib{
    constructor(){
        //this.CdnUrl = 'http://cache.cilogluarge.com';
    }


    /**
     * system request method
     * @param {json object} rqs 
     */
    async request(rqs, file = null,isApi = true) {
        
        //set fetch options
        let op = {
            method: rqs['method'],
        };

        if(op.method === 'delete'){
            //because patch and delete methods will send url encoded data !!
            op.headers = { 'Content-Type':'application/x-www-form-urlencoded' };
            op.body = new URLSearchParams(rqs.data);
        }else{
            //post will send form data
            if(op.method !== 'GET') {
                //create form data
                const fD = new FormData();
                for (let key in rqs['data']) {
                    fD.append(key, rqs['data'][key]);
                }

                if (file !== null && file !== undefined) {
                    fD.append('file', file, file.name);
                }

                if(op.method == 'PATCH'){
                    op.method = 'POST';
                    fD.append('method', 'PATCH');
                }


                op.body = fD;
            }else{
                
            }
        }

        
        //send fetch
        const rsp = await fetch(isApi ? '/api/'+rqs['url'] : rqs['url'], op).then((response) => {
            //convert to json
            return response.json();
        });
        //in this point check if api is send timeout command 
        if (rsp.command !== undefined) {
            switch (parseInt(rsp.command)) {
                case 0:
                    //this mean token is not valid
                    this._logout();
                    break;
            }
        }
        return rsp;
    }

    /**
     * this method will set loading to div
     * @param {string} selector 
     * @param {boolean} event 
     */
    setLoader(selector, event = true) {
        let elms = document.querySelectorAll(selector);
        if (event) {
            //document.body.style.pointerEvents = 'none';
            for (let i = 0; i < elms.length; i++) {
                elms[i].classList.add('b-loader');
            }
        } else {
            document.body.style.pointerEvents = '';
            for (let i = 0; i < elms.length; i++) {
                elms[i].classList.remove('b-loader');
            }
        }
    }

    //this method will show sweet alert loading..
    showLoading(title){
        Swal.fire({
            title: title,
            allowOutsideClick: false,
            showConfirmButton : false,
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        });
    }

    /**
     * this function will ask client to session information 
     */
    async checkSession(){
        if(localStorage.getItem('sinfo') === null || localStorage.getItem('sinfo') === '-1'){
            //ask session  info to client
            /*return await this.request({
                method: 'POST',
                url: '/src/passage.php?job=data&event=checkSession',
            }).then(rsp => {
                if(rsp.rsp){
                    sessionStorage.setItem('sinfo',JSON.stringify(rsp.data));
                }
                return rsp.rsp;
            });*/
            return false;
        }else{
            return true;
        }
    }

    /**
     * Toast message (sweet alert 2)
     * @param {string} type 
     * @param {string} msg 
     */
    toast(type, msg) {
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,

        }).fire({
            icon: type,
            title: msg,
            heightAuto: true
        });
    }

    /**
     * Clear all items
     * @param {string} selector 
     */
    clearElements(selector) {
        let elms = document.querySelectorAll(selector);
        for (let i = 0; i < elms.length; i++) {
            switch (elms[i].tagName) {
                case 'SELECT':
                    elms[i].selectedIndex = 0;
                    break;
                case 'LABEL':
                    elms[i].innerHTML='';
                    break;    
                case 'INPUT':
                    if (elms[i].getAttribute('type') === 'radio' || elms[i].getAttribute('type') === 'checkbox') {
                        elms[i].checked = false;
                    } else {
                        elms[i].value = '';
                    }
                    break;
                default:
                    elms[i].value = '';
                    break;
            }
            elms[i].classList.remove('is-invalid');
        }
        //set invisible language inputs
        elms = document.querySelectorAll('.div_lang_row');
        for (let i = 0; i < elms.length; i++) {
            elms[i].style.display = 'none';
        }
    }

    /**
     * Get form element with validation
     * @param {string} selector 
     */
    checkForm(selector){
        const rsp = {
            obj:{},
            s_file:null,
            valid:true
        }
        //get elements
        const elms = document.querySelectorAll(selector);
        for(let i=0;i<elms.length;i++){
            if(elms[i].value.trim() !== ''){
                if(elms[i].type == 'file'){
                    rsp.s_file = elms[i].files[0];
                }else{
                    if(elms[i].name !== null && elms[i].name.trim() !== ''){
                        
                        switch(elms[i].type){
                            default:
                                rsp.obj[elms[i].name] = elms[i].value;
                                break;
                            case 'checkbox':
                                if(elms[i].dataset.active !== undefined){
                                    rsp.obj[elms[i].name] = elms[i].checked ? elms[i].dataset.active : elms[i].dataset.passive;
                                }else{
                                    rsp.obj[elms[i].name] = Number(elms[i].checked);
                                }
                                break;
                            case 'radio':
                                if(elms[i].checked){
                                    rsp.obj[elms[i].name] = elms[i].value;
                                }
                                break;
                        }
                    } 
                }    
                elms[i].classList.remove('is-invalid');
            }else{
                if(elms[i].required && !elms[i].disabled){
                    elms[i].classList.add('is-invalid');
                    rsp.valid = false;
                }

                if(elms[i].type === 'number'){
                    rsp.obj[elms[i].name] = 0;
                }
            }
        }
        return rsp;
    }

    
    



    exportData(type,data){
        switch(type){
            case 'print':
            case 'excel':
                const tableToExcel = (() => {
                    let uri = 'data:application/vnd.ms-excel;base64,',
                        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
                        base64 = (s) =>{
                            return window.btoa(unescape(encodeURIComponent(s)))
                        }, format = (s, c) =>{
                            return s.replace(/{(\w+)}/g, (m, p) => {
                                return c[p];
                            });
                        }
                    return function (table, name, filename) {
                        const ctx = {
                            worksheet: name || 'Worksheet',
                            table: table.innerHTML
                        }
                        const link = document.createElement("a");
                        link.href = uri + base64(format(template, ctx));
                        link.download = filename;
                        link.click();
                        link.remove();
                    }
                })();

                //at this point create hidden html table for printing
                const table = document.createElement('table');
                for(let i = 0;i < data.length;i++){
                    const row = document.createElement('tr');
                    for(let j = 0;j < data[i].length;j++){
                        let column;
                        if(type == 'print' && i == 0){
                            column = document.createElement('th');
                        }else{
                            column = document.createElement('td');
                        }
                        column.innerHTML = data[i][j];
                        if(!isNaN(data[i][j])  && String(data[i][j]).includes('.')) {
                            switch(type){
                                case 'excel':
                                    column.setAttribute('style','mso-number-format:"#,##0.00"');
                                    break;
                                default:
                                    column.innerHTML = this.formatMoney(data[i][j],2,',','.');
                                    break;
                            }
                           // console.log(column.outerHTML);
                        }
                        row.appendChild(column);
                    }
                    table.appendChild(row);
                }
                if(type == 'excel'){
                    /*const template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><title></title><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Worksheet</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>'+table.innerHTML.replace(/ /g, '%20')+'</table></body></html>';
                    const dataType = 'application/vnd.ms-excel;';
                    const link = document.createElement("a");
                    const title = 'liste.xls'; 
                    //const tableHTML = table.outerHTML.replace(/ /g, '%20');
                    document.body.appendChild(link);
                    // Create a link to the file
                    console.log(template);
                    link.href = 'data:' + dataType + ', ' + template;
                    link.download = title;
                    link.click();
                    link.remove();*/
                    tableToExcel(table,'falan','falan.xls');
                }else{
                    const newWin = window.open("");
                    newWin.document.write('<style>table, th, td {border: 1px solid black;}</style>');
                    newWin.document.write(table.outerHTML);
                    newWin.print();
                    newWin.close();
                }
                break;
            case 'csv':
                const csvContent = "data:text/csv;charset=utf-8," + data.map(e => {
                    for(let key in e){
                        if(!isNaN(e[key]) && String(e[key]).includes('.')) {
                            e[key] = this.formatMoney(e[key],2,',','.');
                        }
                    }
                    return e.join(";");
                }).join("\n");
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "list.csv");
                document.body.appendChild(link); // Required for FF
                link.click();
                link.remove();
                break;        
        }
    }

    /**
     * this method will return unique document number
     */
    async getDocNo(){
        const rsp =  await this.request({
            url    : '/getDocno',
            method : 'GET'
        });
        return rsp.data; 
    }

    /**
     * this method will format money decimal
     * @param {float} amount 
     * @param {int} decimalCount 
     * @param {string} decimal 
     * @param {string} thousands 
     */
    formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
      
            const negativeSign = amount < 0 ? "-" : "";
        
            const i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            const j = (i.length > 3) ? i.length % 3 : 0;
      
            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
          console.log(e)
        }
    }


    async getCurrency(from = 'EUR'){
        const main = document.getElementById('mainCur').value;
        return await fetch('https://api.frankfurter.app/latest?amount=1&from='+from+'&to='+main).then(async (data) => {
            if (data.ok) {
                data = await data.json();
                return data.rates[main];
            }else{
                return 1;
            }
        }).catch(e => { 
            console.log('Connection error', e);
            return 1;
        });
    }
}