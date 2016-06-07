(function(d){
    function template(id, data) {
        if (d.getElementById(id) !== null) {
            data = data || {};
            return Mustache.render(d.getElementById(id).innerHTML, data);
        }
        return '';
    }
    window.template = template;
})(document);