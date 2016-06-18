var length = function(obj) {
    return Object.keys(obj).length;
}

var css = function(element, style) {
    for (var prop in style) {
        element.style[prop] = style[prop];
    }
}

var animate = function(opts, callback) {
    var start = new Date;
    var timer = setInterval(function() {
        var progress = (new Date - start) / opts.duration;
        if (progress > 1) progress = 1;
        opts.step(progress);
        if (progress == 1) {
            if (callback) {
                callback.apply();
            }
            clearInterval(timer);
        }
    }, opts.delay || 20);
    
    return {
        stop: function() {
            clearInterval(timer);
        }
    };
}

var addClass = function(element, classname) {
    var cn = element.className;
    
    classname = classname.replace(/[^\x21-\x7E]+/g, ' ');
    classname.replace(/^\s+|\s+$/g, '');
    
    if(cn.indexOf(classname) != -1 || classname == '') {
        return;
    }
    
    element.className = [cn, classname].join(' ');
}

var removeClass = function(element, classname) {
    var cn = element.className;
    var rxp = new RegExp("\\s?\\b"+classname+"\\b", "g");
    cn = cn.replace(rxp, '');
    element.className = cn;
}