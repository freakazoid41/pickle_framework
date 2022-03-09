import Component from '../../../bin/parents/component.js';

export default class BottomBar extends Component{

    async render(){
        this.styles = [
            'views/components/'+this.constructor.name+'/component.css?v='+(new Date).getTime()
        ];
        this.session = JSON.parse(localStorage.getItem('sinfo'));
        await this.view(`  <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                                bottom bar
                            </div>`);
}


    async afterRender(){
        await this.setEvents();
    
        if(this.renderCallback !== null) this.renderCallback(this.referance);
    }


    async setEvents(){
        
    }

}

