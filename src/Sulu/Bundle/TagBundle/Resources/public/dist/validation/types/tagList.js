define(["type/default"],function(a){"use strict";return function(b,c){var d={},e={initializeSub:function(){App.off("husky.auto-complete-list."+this.options.instanceName+".item-added"),App.off("husky.auto-complete-list."+this.options.instanceName+".item-deleted"),App.on("husky.auto-complete-list."+this.options.instanceName+".item-added",this.itemHandler.bind(this)),App.on("husky.auto-complete-list."+this.options.instanceName+".item-deleted",this.itemHandler.bind(this))},itemHandler:function(){App.emit("sulu.preview.update",b,this.getValue()),App.emit("sulu.content.changed")},setValue:function(a){App.dom.data(b,"auraItems",a)},getValue:function(){return App.dom.data(b,"tags")},needsValidation:function(){return!1},validate:function(){return!0}};return new a(b,d,c,"tagList",e)}});