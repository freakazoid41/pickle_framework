import Utils  from './services/Utils.js'
import Plib   from './services/Plib.js'
import routes from './services/Routes.js'


// List of supported routes. Any url other than these routes will throw a 404 error


// The router code. Takes a URL, checks against the list of supported routes and then renders the corresponding content page.
const router = async () => {
    const container = null || document.getElementById('div_container');
    
    // Get the parsed URl from the addressbar
    const request = Utils.parseRequestURL()
    

    // Parse the URL and if it has an id part, change it with the string ":id"
    const parsedURL = (request.resource ? '/' + request.resource : '/') + (request.id ? '/:id' : '') + (request.verb ? '/' + request.verb : '')
    
    // Get the page from our hash of supported routes.
    // If the parsed URL is not in our list of supported routes, select the 404 page instead
    const pageObj = routes[parsedURL] !== undefined ? routes[parsedURL] : routes['/404'];
    
    //check session here and redirect to login if not setted !!
    //const session = (await (new Plib).checkSession());
    const session = true;
    if(session === true || pageObj.page === 'Login'){
        //import page
        let page = await import('./views/pages/'+pageObj.page+'/page.js?v='+(new Date).getTime());
        sessionStorage.setItem('current',JSON.stringify(pageObj.bread));        
        console.log(page);
        //check if has layout
        if(pageObj.layout !== null){
            //import layout
            let layout = await import('./views/layouts/'+pageObj.layout+'/page.js?v='+(new Date).getTime());
            //build layout
            layout = new layout.default(container,page.default,null,null,Utils.clearDebris('page'),Utils.clearDebris('layout'));
            //check layout is diffrent 
            if(document.querySelector('[data-layout="'+pageObj.layout+'"]')== null){
                //render page with layout
                layout.render();
            }else{
                //redirect page
                layout.redirect();
            }
        }else{
            //clear layout debris
            Utils.clearDebris('layout')();
            page = new page.default(container,null,Utils.clearDebris('page'));
            //render page
            page.render();
        }
    }else{
        window.location.href = '/#/home';
    }
}

// Listen on hash change:
window.addEventListener('hashchange', router);

// Listen on page load:
window.addEventListener('load', router);