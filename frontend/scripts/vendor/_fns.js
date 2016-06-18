// Adding [].indexOf to Illustrator JavaScript

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(elt /*, from*/) {
        var len = this.length;
        var from = Number(arguments[1]) || 0;
        from = (from < 0) ? Math.ceil(from) : Math.floor(from);
        if (from < 0) from += len;
        for (; from < len; from++) {
            if (from in this && this[from] === elt) return from;
        }
        return -1;
   };
}

// =====================================
// functions
// =====================================

// html entity substitution
// not used ==> ["\x22","&quot;"], ["\x3C","&lt;"], ["\x3E","&gt;"], ["\x26","&amp;"],
var htmlCharacterCodes = [["\xA0","&nbsp;"], ["\xA1","&iexcl;"], ["\xA2","&cent;"], ["\xA3","&pound;"], ["\xA4","&curren;"], ["\xA5","&yen;"], ["\xA6","&brvbar;"], ["\xA7","&sect;"], ["\xA8","&uml;"], ["\xA9","&copy;"], ["\xAA","&ordf;"], ["\xAB","&laquo;"], ["\xAC","&not;"], ["\xAD","&shy;"], ["\xAE","&reg;"], ["\xAF","&macr;"], ["\xB0","&deg;"], ["\xB1","&plusmn;"], ["\xB2","&sup2;"], ["\xB3","&sup3;"], ["\xB4","&acute;"], ["\xB5","&micro;"], ["\xB6","&para;"], ["\xB7","&middot;"], ["\xB8","&cedil;"], ["\xB9","&sup1;"], ["\xBA","&ordm;"], ["\xBB","&raquo;"], ["\xBC","&frac14;"], ["\xBD","&frac12;"], ["\xBE","&frac34;"], ["\xBF","&iquest;"], ["\xD7","&times;"], ["\xF7","&divide;"], ["\u0192","&fnof;"], ["\u02C6","&circ;"], ["\u02DC","&tilde;"], ["\u2002","&ensp;"], ["\u2003","&emsp;"], ["\u2009","&thinsp;"], ["\u200C","&zwnj;"], ["\u200D","&zwj;"], ["\u200E","&lrm;"], ["\u200F","&rlm;"], ["\u2013","&ndash;"], ["\u2014","&mdash;"], ["\u2018","&lsquo;"], ["\u2019","&rsquo;"], ["\u201A","&sbquo;"], ["\u201C","&ldquo;"], ["\u201D","&rdquo;"], ["\u201E","&bdquo;"], ["\u2020","&dagger;"], ["\u2021","&Dagger;"], ["\u2022","&bull;"], ["\u2026","&hellip;"], ["\u2030","&permil;"], ["\u2032","&prime;"], ["\u2033","&Prime;"], ["\u2039","&lsaquo;"], ["\u203A","&rsaquo;"], ["\u203E","&oline;"], ["\u2044","&frasl;"], ["\u20AC","&euro;"], ["\u2111","&image;"], ["\u2113",""], ["\u2116",""], ["\u2118","&weierp;"], ["\u211C","&real;"], ["\u2122","&trade;"], ["\u2135","&alefsym;"], ["\u2190","&larr;"], ["\u2191","&uarr;"], ["\u2192","&rarr;"], ["\u2193","&darr;"], ["\u2194","&harr;"], ["\u21B5","&crarr;"], ["\u21D0","&lArr;"], ["\u21D1","&uArr;"], ["\u21D2","&rArr;"], ["\u21D3","&dArr;"], ["\u21D4","&hArr;"], ["\u2200","&forall;"], ["\u2202","&part;"], ["\u2203","&exist;"], ["\u2205","&empty;"], ["\u2207","&nabla;"], ["\u2208","&isin;"], ["\u2209","&notin;"], ["\u220B","&ni;"], ["\u220F","&prod;"], ["\u2211","&sum;"], ["\u2212","&minus;"], ["\u2217","&lowast;"], ["\u221A","&radic;"], ["\u221D","&prop;"], ["\u221E","&infin;"], ["\u2220","&ang;"], ["\u2227","&and;"], ["\u2228","&or;"], ["\u2229","&cap;"], ["\u222A","&cup;"], ["\u222B","&int;"], ["\u2234","&there4;"], ["\u223C","&sim;"], ["\u2245","&cong;"], ["\u2248","&asymp;"], ["\u2260","&ne;"], ["\u2261","&equiv;"], ["\u2264","&le;"], ["\u2265","&ge;"], ["\u2282","&sub;"], ["\u2283","&sup;"], ["\u2284","&nsub;"], ["\u2286","&sube;"], ["\u2287","&supe;"], ["\u2295","&oplus;"], ["\u2297","&otimes;"], ["\u22A5","&perp;"], ["\u22C5","&sdot;"], ["\u2308","&lceil;"], ["\u2309","&rceil;"], ["\u230A","&lfloor;"], ["\u230B","&rfloor;"], ["\u2329","&lang;"], ["\u232A","&rang;"], ["\u25CA","&loz;"], ["\u2660","&spades;"], ["\u2663","&clubs;"], ["\u2665","&hearts;"], ["\u2666","&diams;"]];

var is_undefined = function(x) {
    return typeof x === 'undefined';
}

var is_null = function(x) {
    return x == null;
}

var is_string = function(o) {
    return typeof o === 'string';
}

var is_object = function(o) {
    return typeof o === 'object';
}

var is_number = function(o) {
    return typeof o === 'number';
}

var is_defined = function(x) {
    return typeof x !== 'undefined';
}

var is_empty = function(s) {
    return (!s || 0 === s.length);
};

var is_blank = function(s) {
    return (!s || /^\s*$/.test(s));
};

var makeKeyword = function(t) {
    return t.replace( /[^A-Za-z0-9_\-]/g , "_" ).toLowerCase();
};

var cleanText = function(text) {
    for (var i=0; i < htmlCharacterCodes.length; i++) {
        var charCode = htmlCharacterCodes[i];
        text = text.replace( new RegExp(htmlCharacterCodes[i][0],'g'), htmlCharacterCodes[i][1] )
    };
    return text;
};

var zero_pad = function(value, padding) {
    var zeroes = "0";
    for (var i = 0; i < padding; i++) { zeroes += "0"; }
    return (zeroes + value).slice(padding * -1);
}

var roundTo = function(numberToRound,decimalPlaces) {
    var roundedNumber = Math.round(numberToRound * Math.pow(10,decimalPlaces)) / Math.pow(10,decimalPlaces);
    return roundedNumber;
};

var round = function(number, precision) {
    var d = Math.pow(10, precision || 0);
    return Math.round(number * d) / d;
}

// #

var on = function(elm, events, handler) {
   if (elm) {
       events.split(' ').forEach(function (event) {
           elm.addEventListener(event, handler);
       });
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