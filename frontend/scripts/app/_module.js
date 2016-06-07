var basketModule = (function() {
  var basket = []; // приватная переменная
    return { // методы доступные извне
        addItem: function(values) {
            basket.push(values);
        },
        getItemCount: function() {
            return basket.length;
        },
        getTotal: function() {
           var q = this.getItemCount(),p=0;
            while(q--){
                p+= basket[q].price; 
            }
            return p;
        }
    }
}());

// basketModule - это объект со свойствами, которые могут также быть и методами:
basketModule.addItem({item:'bread', price:0.5});
basketModule.addItem({item:'butter', price:0.3});

console.log(basketModule.getItemCount());
console.log(basketModule.getTotal());

// А следующий ниже код работать не будет:
console.log(basketModule.basket); // undefined потому что не входит в возвращаемый объект
console.log(basket); // массив доступен только из замыкания



// ??

function library(module) {
  $(function() {
    if (module.init) {
      module.init();
    }
  });
  return module;
}

var myLibrary = library(function() {
   return {
     init: function() {
       /* код модуля */
     }
   };
}());

// ?? Facade

var module = (function() {
  var _private = {
    i: 5,
    get: function() {
      console.log('Текущее значение:' + this.i);
    },
    set: function(val) {
      this.i = val;
    },
    run: function() {
      console.log('процесс запущен');
    },
    jump: function() {
      console.log('резкое изменение');
    }
  };
  return {
    facade: function(args) {
      _private.set(args.val);
      _private.get();
      if (args.run) {
        _private.run();
      }
    }
  }
}());

module.facade({run:true, val:10}); // Текущее значение: 10, процесс запущен

// Mediator

var mediator = (function() {
    var subscribe = function(channel, fn) {
        if (!mediator.channels[channel]) mediator.channels[channel] = [];
        mediator.channels[channel].push({ context: this, callback: fn });
        return this;
    },
 
    publish = function(channel) {
        if (!mediator.channels[channel]) return false;
        var args = Array.prototype.slice.call(arguments, 1);
        for (var i = 0, l = mediator.channels[channel].length; i < l; i++) {
            var subscription = mediator.channels[channel][i];
            subscription.callback.apply(subscription.context, args);
        }
        return this;
    };
 
    return {
        channels: {},
        publish: publish,
        subscribe: subscribe,
        installTo: function(obj) {
            obj.subscribe = subscribe;
            obj.publish = publish;
        }
    };

}());

//Pub/sub on a centralized mediator

mediator.name = "tim";
mediator.subscribe('nameChange', function(arg) {
    console.log(this.name);
    this.name = arg;
    console.log(this.name);
});

mediator.publish('nameChange', 'david'); //tim, david


//Pub/sub via third party mediator

var obj = {name: 'sam'};
mediator.installTo(obj);
obj.subscribe('nameChange', function(arg) {
    console.log(this.name);
    this.name = arg;
    console.log(this.name);
});

obj.publish('nameChange', 'john'); //sam, john