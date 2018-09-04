if(document.domain.length){
    var parts = document.domain.replace(/^(www\.)/,"").split('.');
    while(parts.length > 2){
        var subdomain = parts.shift();
    }
    var domain = parts.join('.');
    document.domain =  domain.replace(/(^\.*)|(\.*$)/g, "");
}