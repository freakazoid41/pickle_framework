import Page    from '../../../bin/parents/page.js';


export default class Home extends Page{

    async render(){
        this.styles = [
            'views/pages/NotFound/page.css?v='+(new Date).getTime()
        ]
        //render page
        this.view(`<div class="noise"></div>
                        <div class="overlay"></div>
                        <div class="terminal">
                            <h1>Error <span class="errorcode">404</span></h1>
                            <p class="output">Aradığınız sayfa bulunmamaktadır..</p>
                            <p class="output">Lütfen anasayfaya dönmek için <a href="/#/home">buraya</a> tıklayınız </a>.</p>
                            <p class="output">İyi Çalışmalar.</p>
                    </div>`);

    }


    async afterRender(){
        if(this.renderCallback !== null) this.renderCallback(this.referance);
    }


}

