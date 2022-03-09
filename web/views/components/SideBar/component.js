import Component from '/web/bin/parents/component.js';

export default class SideBar extends Component{
    async render(){
        this.styles = [
            '/web/views/components/'+this.constructor.name+'/component.css?v='+(new Date).getTime()
        ];
        await this.view(`  sidebar `);
    }


    async afterRender(){
        await this.setEvents();
    
        if(this.renderCallback !== null) this.renderCallback(this.referance);
    }


    async setEvents(){
        //listen menu opens
        const toggles = document.querySelectorAll('.menu-toggle');
        for(let i=0;i<toggles.length;i++){
            toggles[i].addEventListener('click',e=>{
                toggles[i].parentNode.classList.toggle('menu-item-open');
            });
        }

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

