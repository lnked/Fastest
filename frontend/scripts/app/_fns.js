function is_undefined(x) {
    return typeof x === 'undefined';
}

function is_null(x) {
    return x == null;
}

function is_string(o) {
    return typeof o === 'string';
}

function is_object(o) {
    return typeof o === 'object';
}

function is_number(o) {
    return typeof o === 'number';
}

function is_defined(x) {
    return typeof x !== 'undefined';
}

// #

function on(elm, events, handler)
{
   if (elm) {
       events.split(' ').forEach(function (event) {
           elm.addEventListener(event, handler);
       });
   }
}

function scrollToElement(element)
{
    if ($(element).length)
    {
        var block;

        if ($(element).closest('.popup').length)
        {
            block = $(element).closest('.popup');
        }
        else {
            block = $(element);
        }

        block.stop().animate({ scrollTop: $(element).position().top }, 'fast');
    }
}

function getMax(arr)
{
    return Math.max.apply(Math, arr);
}

function getMin(arr)
{
    return Math.min.apply(Math, arr);
}

function decline(number, titles) {  
    cases = [2, 0, 1, 1, 1, 2];  
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
}
// decline(count, ['найдена', 'найдено', 'найдены']);

var shuffle = function( array ) {
	return array.sort( function() {
		return Math.random() - 0.5;
	} );
};

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};