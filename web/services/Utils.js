const Utils = { 
    // --------------------------------
    //  Parse a url and break it into resource, id and verb
    // --------------------------------
    parseRequestURL : () => {
        const url = (location.hash.slice(1).toLowerCase() || '/').split('?');
        const params = {};
        if(url[1] !== undefined){
            url[1].split('&').forEach(element => {
                element = element.split('=');
                params[element[0]] = element[1];
            });
        }
        

        sessionStorage.setItem('params',JSON.stringify(params));
        const r = url[0].split("/");
        const request = {
            resource    : r[1],
            id          : r[2],
            verb        : r[3],
            params      : params
        }
        return request;
    }

    // --------------------------------
    //  Simple sleep implementation
    // --------------------------------
    , sleep: (ms) => {
        return new Promise(resolve => setTimeout(resolve, ms));
    },
    //cleaning debris from html for ol css filesasync ()=>{
    clearDebris : (type = 'page' )=>{
        const debris = {
            css:()=>{},
            js:()=>{}
        }
        if(type == 'page'){
            debris.css = async () =>{
                //clean old css files if exist
                document.querySelectorAll('link[data-type="page"]').forEach(el=>{
                    el.outerHTML = '';
                });
            }
            debris.js = async () =>{
                //clean old layout js files if exist
                document.querySelectorAll('script[data-type="page"]').forEach(el=>{
                    el.outerHTML = '';
                });
            }
        }else{
            debris.css = async () =>{
                //clean old css files if exist
                document.querySelectorAll('link[data-type="layout_component"]').forEach(el=>{
                    el.outerHTML = '';
                });
            }
            debris.js = async () =>{
                //clean old layout js files if exist
                document.querySelectorAll('link[data-type="layout"]').forEach(el=>{
                    el.outerHTML = '';
                });
            }
        }
        return async () => { await Promise.all([debris.js(), debris.css()]) };
    }
}

export default Utils;