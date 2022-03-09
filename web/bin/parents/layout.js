export default class Layout{
    constructor(elm,page,renderCallback = null,redirectCallback = null,beforeRenderPage = null,beforeRender = null){
        this.beforeRender = beforeRender;
        this.beforeRenderPage = beforeRenderPage;
        this.renderCallback = renderCallback;
        this.redirectCallback = redirectCallback;
        this.page = page;
        this.referance = elm;
    }

    loadCss(){
       
        //render css elements to dom
        this.styles.forEach(el=>{
            const link = document.createElement('link');
            link.href = el;
            link.rel  = 'stylesheet';
            link.dataset.type='layout';
            document.querySelector('head').appendChild(link);
        });
    }

    /**
     * this method will be return view to html target
     */
    async view(view){
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