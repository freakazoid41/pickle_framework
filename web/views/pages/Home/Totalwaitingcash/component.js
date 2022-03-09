import Component from '../../../../bin/parents/component.js';


import Plib from '../../../../services/Plib.js';
import PickleTable  from '../../../../assets/table/pickletable.js';


export default class Totalwaitingcash extends Component{
    
    async render(){
        this.styles = [
            '/web/views/pages/Home/Totalwaitingcash/component.css?v='+(new Date).getTime(),
        ];

        await this.view(`<div class="col-lg-5">
                            Component 2
                        </div>`);
    }


    async afterRender(){
        await this.build();
        await this.events();
        if(this.renderCallback !== null) this.renderCallback(this.referance);
    }

    async build(){
        this.container = {};
        this.plib = new Plib();
        this.getinfo();
    }


    async events(){
        /*document.querySelectorAll('.wtotal_item').forEach(el=>{
            el.addEventListener('click',e=>{
                this.setTable(e.target.dataset.name,e.target);
            });
        });*/
    }

    async getinfo(){
       /* this.plib.setLoader('#card_wtotal');
        let total = 0;
        await this.plib.request({
            method:'GET',
            url: '/dashboard/totalWaitingCash',
        }).then(rsp => {
            this.current = rsp;
            for(let key in rsp){
                document.querySelector('.spn_wtotal[data-name="'+key+'"]').innerHTML = this.plib.formatMoney(rsp[key].total);
                total += parseFloat(rsp[key].total);
            }
            
            document.querySelector('.per_wtotal_invoice').style.width = this.plib.formatMoney(rsp.totalInvoice.total/ (total/100),0)+'%';
            document.querySelector('.per_wtotal_cash').style.width    = '0%';
            document.querySelector('.per_wtotal_cheque').style.width  = this.plib.formatMoney(rsp.totalCheques.total /(total/100),0)+'%';
            document.querySelector('.per_wtotal_bank').style.width    = '0%';
        });
        document.getElementById('spn_wtotal').innerHTML = this.plib.formatMoney(total);
        
        this.plib.setLoader('#card_wtotal',false);*/
    }

    
}

