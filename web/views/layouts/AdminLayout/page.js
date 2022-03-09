import Layout from '/web/bin/parents/layout.js';

/*import Breadcrump    from '../../components/Breadcrump/component.js';
import NavBar       from '../../components/NavBar/component.js';
import SideBar      from '../../components/SideBar/component.js';
import BottomBar    from '../../components/BottomBar/component.js';*/


export default class AdminLayout extends Layout{

    async render(){
       
        this.styles = [
            '/web/views/layouts/AdminLayout/page.css?v='+(new Date).getTime()
        ];

        //render layout
        await this.view(` <div data-layout="AdminLayout" class="aside aside-left aside-fixed h-100" id="kt_aside">
                                <!-- menu component container -->
                                <div id="kt_aside_menu" class="aside-menu my-5" data-menu-vertical="1" data-menu-scroll="1"
                                    data-menu-dropdown-timeout="500">

                                </div>
                                <!-- menu component container -->
                            </div>
                            <div class="header-mobile" id="kt_header_mobile">
                                
                            </div>
                            <div class="d-flex flex-column flex-root">
                                <div class="d-flex flex-row flex-column-fluid page">
                                    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                                        <div id="kt_header" class="header">
                                            <div class="container">
                                                <div class="d-flex align-items-center flex-wrap mr-1 mt-5 mt-lg-0" id="bread_bar">
                                                    
                                                </div>
                                                <div class="topbar" id="header_bar">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <!-- page component container --> 
                                        <div class="content d-flex flex-column flex-column-fluid container" id="kt_content">
                                            
                                        </div>
                                        <!-- page component container --> 
                                        <!-- footer component container --> 
                                        <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">

                                            
                                        </div>
                                        <!-- footer component container --> 
                                    </div>
                                </div>
                            </div>`);
    }

    async redirect(){
        console.log('### Redirected...');
        let comp = await import('/web/views/components/Breadcrump/component.js?v='+(new Date).getTime());
        await (new comp.default(document.getElementById('bread_bar'))).render();
        

        const page = new this.page(document.getElementById('kt_content'),null,this.beforeRenderPage);
        await page.render();
        if(this.redirectCallback !== null) this.redirectCallback(this.referance);
    }

    async afterRender(){
        let comp = await import('/web/views/components/Breadcrump/component.js?v='+(new Date).getTime());
        await (new comp.default(document.getElementById('bread_bar'))).render();

        comp = await import('/web/views/components/NavBar/component.js?v='+(new Date).getTime());
        await (new comp.default(document.getElementById('header_bar'))).render();

        comp = await import('/web/views/components/SideBar/component.js?v='+(new Date).getTime());
        await (new comp.default(document.getElementById('kt_aside_menu'))).render();

        //page referance
        const page = new this.page(document.getElementById('kt_content'),null,this.beforeRenderPage);
        await page.render();

        if(this.renderCallback !== null) this.renderCallback(this.referance);

        //sidebar toggler
        /*const div_sidebar = document.getElementById('kt_aside');
        document.querySelectorAll('.side-toggler').forEach(el=>{
            el.addEventListener('click',e=>{
                console.log(e.target)
                if(e.target.id === 'kt_aside_mobile_toggle'){
                    div_sidebar.classList.toggle('aside-on');
                }else{
                    div_sidebar.classList.remove('aside-on');
                    document.body.classList.toggle('aside-minimize');
                }
            })
        });*/
    }


}

