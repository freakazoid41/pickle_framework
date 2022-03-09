import Component from '../../../bin/parents/component.js';

export default class Breadcrump extends Component{

    async render(){
        this.styles = [
            '/web/views/components/'+this.constructor.name+'/component.css?v='+(new Date).getTime()
        ];

        const items = JSON.parse(sessionStorage.getItem('current'));

        await this.view(`   <div class="d-flex align-items-baseline flex-wrap mr-5">
                                bread section
                            </div>`);
}


    async afterRender(){
        await this.setEvents();
    
        if(this.renderCallback !== null) this.renderCallback(this.referance);
    }


    async setEvents(){
        
    }

}

