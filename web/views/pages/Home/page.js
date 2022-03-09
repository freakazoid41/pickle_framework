import Page    from '../../../bin/parents/page.js';

import Totalcash        from './Totalcash/component.js';
import Totalwaitingcash from './Totalwaitingcash/component.js';


export default class Home extends Page{

    async render(){
        this.styles = [
            '/web/views/pages/'+this.constructor.name+'/page.css?v='+(new Date).getTime()
        ]
        //render page
        this.view(`<section class="main_section fade-in">
                        <div class="container">
                            Buda ana container

                            <div class="row" id="div_total_cash">
                                            
                            </div>
                            <div class="row" id="div_wtotal_cash">
                                            
                            </div>
                        </div>
                    </section>`);

    }


    async afterRender(){
        await (new Totalcash(document.getElementById('div_total_cash'))).render();
        await (new Totalwaitingcash(document.getElementById('div_wtotal_cash'))).render();

        if(this.renderCallback !== null) this.renderCallback(this.referance);
    }


}

