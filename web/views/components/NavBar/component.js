import Component from '../../../bin/parents/component.js';

export default class NavBar extends Component{

    async render(){
        this.styles = [
            '/web/views/components/'+this.constructor.name+'/component.css?v='+(new Date).getTime()
        ];
        this.session = JSON.parse(localStorage.getItem('sinfo'));
        await this.view(`<div class="topbar-item mr-2">
                            Header Falan
                        </div>`);
}


    async afterRender(){
        await this.setEvents();
    
        if(this.renderCallback !== null) this.renderCallback(this.referance);
    }


    async setEvents(){
        /*document.body.addEventListener('click',e=>{
            if(e.target.classList.contains('header_btn')){
                window.location.href = e.target.dataset.url;
                const elms = document.querySelectorAll('.header_btn');
                for(let i=0;i<elms.length;i++){
                    if(elms[i] === e.target){
                        e.target.classList.add('head_selected');
                    }else{
                        elms[i].classList.remove('head_selected');
                    }
                }
            }
        });

        //logout
        document.getElementById('i_logout').addEventListener('click',()=>{
            sessionStorage.setItem('sinfo','-1');
            window.location.href = '/src/passage.php?job=logout';
        });*/
    }

}

