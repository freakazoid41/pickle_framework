export default class Theme {
    constructor(){
        dayjs.extend(window.dayjs_plugin_customParseFormat);
        this.setEvents()
    }

    setEvents(){
        document.body.addEventListener('click',e=>{
            if(e.target.classList.contains('section_changer')){
                if(parseInt(e.target.dataset.type)==1){
                    document.querySelector('.section_main[data-section="'+e.target.dataset.section+'"]').style.display = 'none';
                    document.querySelector('.section_sub[data-section="'+e.target.dataset.section+'"]').style.display = '';
                }else{
                    document.querySelector('.section_sub[data-section="'+e.target.dataset.section+'"]').style.display = 'none';
                    document.querySelector('.section_main[data-section="'+e.target.dataset.section+'"]').style.display = '';
                }
            }
            
            /*if(e.target.classList.contains('nav-link')){
                const tab = e.target.closest('.tab-content');
                //reset all tabs
                const tabs = tab.querySelectorAll('.nav-link');
                for(let i = 0;i<tabs.length;i++){
                    const target = document.getElementById(tabs[i].dataset.target);
                    if(tabs[i] !== e.target){
                        tabs[i].classList.remove('active');
                        target.classList.remove('active','show');
                    }else{
                        tabs[i].classList.add('active');
                        target.classList.add('active','show');
                    }
                }
            }

            if(e.target.classList.contains('section_changer')){
                if(parseInt(e.target.dataset.type)==1){
                    document.querySelector('.section_main[data-section="'+e.target.dataset.section+'"]').style.display = 'none';
                    document.querySelector('.section_sub[data-section="'+e.target.dataset.section+'"]').style.display = '';
                }else{
                    document.querySelector('.section_sub[data-section="'+e.target.dataset.section+'"]').style.display = 'none';
                    document.querySelector('.section_main[data-section="'+e.target.dataset.section+'"]').style.display = '';
                }
            }*/
        });

        //file change
        document.body.addEventListener('change',e=>{
            if(e.target.classList.contains('custom-file-input')){
                e.target.parentNode.querySelector('label').innerHTML = e.target.value;
            }
        });
    }
}