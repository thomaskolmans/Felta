if(document.domain.length){
    var parts = document.domain.replace(/^(www\.)/,"").split('.');
    while(parts.length > 2){
        var subdomain = parts.shift();
    }
    var domain = parts.join('.');
    if (subdomain) {
        document.domain = subdomain + "." + domain.replace(/(^\.*)|(\.*$)/g, "");
    } else {
        document.domain =  domain.replace(/(^\.*)|(\.*$)/g, "");
    }
}
