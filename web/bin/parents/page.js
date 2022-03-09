export default class Page{
    constructor(elm,renderCallback = null,beforeRender = null){
        this.beforeRender = beforeRender
        this.renderCallback = renderCallback;
        this.referance = elm;
    }

    /**
     * this method will load css files for page from local css container
     */
    async loadCss(){
        //render css elements to dom
        this.styles.forEach(el=>{
            const link = document.createElement('link');
            link.href = el;
            link.dataset.type='page';
            link.rel  = 'stylesheet';
            document.querySelector('head').appendChild(link);
        });
    }

    /**
     * this method will be return view to html target
     */
    async view(view){
        //some cleaning
        document.querySelectorAll('.flatpickr-calendar').forEach(el=>el.outerHTML = '');


        //remove inside of referance 
        this.referance.innerHTML = '';

        //trigger before render if is exist 
        if(this.beforeRender !== null) await this.beforeRender();
        
        //render css files
        await this.loadCss();

        //render page
        this.referance.innerHTML = view;

        //trigger after page render event
        await this.afterRender();
    }
}