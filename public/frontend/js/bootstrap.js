/*!
 * Bootstrap v3.1.1 (http://getbootstrap.com)
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one(a.support.transition.end,function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b()})}(jQuery),+function(a){"use strict";var b='[data-dismiss="alert"]',c=function(c){a(c).on("click",b,this.close)};c.prototype.close=function(b){function c(){f.trigger("closed.bs.alert").remove()}var d=a(this),e=d.attr("data-target");e||(e=d.attr("href"),e=e&&e.replace(/.*(?=#[^\s]*$)/,""));var f=a(e);b&&b.preventDefault(),f.length||(f=d.hasClass("alert")?d:d.parent()),f.trigger(b=a.Event("close.bs.alert")),b.isDefaultPrevented()||(f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one(a.support.transition.end,c).emulateTransitionEnd(150):c())};var d=a.fn.alert;a.fn.alert=function(b){return this.each(function(){var d=a(this),e=d.data("bs.alert");e||d.data("bs.alert",e=new c(this)),"string"==typeof b&&e[b].call(d)})},a.fn.alert.Constructor=c,a.fn.alert.noConflict=function(){return a.fn.alert=d,this},a(document).on("click.bs.alert.data-api",b,c.prototype.close)}(jQuery),+function(a){"use strict";var b=function(c,d){this.$element=a(c),this.options=a.extend({},b.DEFAULTS,d),this.isLoading=!1};b.DEFAULTS={loadingText:"loading..."},b.prototype.setState=function(b){var c="disabled",d=this.$element,e=d.is("input")?"val":"html",f=d.data();b+="Text",f.resetText||d.data("resetText",d[e]()),d[e](f[b]||this.options[b]),setTimeout(a.proxy(function(){"loadingText"==b?(this.isLoading=!0,d.addClass(c).attr(c,c)):this.isLoading&&(this.isLoading=!1,d.removeClass(c).removeAttr(c))},this),0)},b.prototype.toggle=function(){var a=!0,b=this.$element.closest('[data-toggle="buttons"]');if(b.length){var c=this.$element.find("input");"radio"==c.prop("type")&&(c.prop("checked")&&this.$element.hasClass("active")?a=!1:b.find(".active").removeClass("active")),a&&c.prop("checked",!this.$element.hasClass("active")).trigger("change")}a&&this.$element.toggleClass("active")};var c=a.fn.button;a.fn.button=function(c){return this.each(function(){var d=a(this),e=d.data("bs.button"),f="object"==typeof c&&c;e||d.data("bs.button",e=new b(this,f)),"toggle"==c?e.toggle():c&&e.setState(c)})},a.fn.button.Constructor=b,a.fn.button.noConflict=function(){return a.fn.button=c,this},a(document).on("click.bs.button.data-api","[data-toggle^=button]",function(b){var c=a(b.target);c.hasClass("btn")||(c=c.closest(".btn")),c.button("toggle"),b.preventDefault()})}(jQuery),+function(a){"use strict";var b=function(b,c){this.$element=a(b),this.$indicators=this.$element.find(".carousel-indicators"),this.options=c,this.paused=this.sliding=this.interval=this.$active=this.$items=null,"hover"==this.options.pause&&this.$element.on("mouseenter",a.proxy(this.pause,this)).on("mouseleave",a.proxy(this.cycle,this))};b.DEFAULTS={interval:5e3,pause:"hover",wrap:!0},b.prototype.cycle=function(b){return b||(this.paused=!1),this.interval&&clearInterval(this.interval),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},b.prototype.getActiveIndex=function(){return this.$active=this.$element.find(".item.active"),this.$items=this.$active.parent().children(),this.$items.index(this.$active)},b.prototype.to=function(b){var c=this,d=this.getActiveIndex();return b>this.$items.length-1||0>b?void 0:this.sliding?this.$element.one("slid.bs.carousel",function(){c.to(b)}):d==b?this.pause().cycle():this.slide(b>d?"next":"prev",a(this.$items[b]))},b.prototype.pause=function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition&&(this.$element.trigger(a.support.transition.end),this.cycle(!0)),this.interval=clearInterval(this.interval),this},b.prototype.next=function(){return this.sliding?void 0:this.slide("next")},b.prototype.prev=function(){return this.sliding?void 0:this.slide("prev")},b.prototype.slide=function(b,c){var d=this.$element.find(".item.active"),e=c||d[b](),f=this.interval,g="next"==b?"left":"right",h="next"==b?"first":"last",i=this;if(!e.length){if(!this.options.wrap)return;e=this.$element.find(".item")[h]()}if(e.hasClass("active"))return this.sliding=!1;var j=a.Event("slide.bs.carousel",{relatedTarget:e[0],direction:g});return this.$element.trigger(j),j.isDefaultPrevented()?void 0:(this.sliding=!0,f&&this.pause(),this.$indicators.length&&(this.$indicators.find(".active").removeClass("active"),this.$element.one("slid.bs.carousel",function(){var b=a(i.$indicators.children()[i.getActiveIndex()]);b&&b.addClass("active")})),a.support.transition&&this.$element.hasClass("slide")?(e.addClass(b),e[0].offsetWidth,d.addClass(g),e.addClass(g),d.one(a.support.transition.end,function(){e.removeClass([b,g].join(" ")).addClass("active"),d.removeClass(["active",g].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger("slid.bs.carousel")},0)}).emulateTransitionEnd(1e3*d.css("transition-duration").slice(0,-1))):(d.removeClass("active"),e.addClass("active"),this.sliding=!1,this.$element.trigger("slid.bs.carousel")),f&&this.cycle(),this)};var c=a.fn.carousel;a.fn.carousel=function(c){return this.each(function(){var d=a(this),e=d.data("bs.carousel"),f=a.extend({},b.DEFAULTS,d.data(),"object"==typeof c&&c),g="string"==typeof c?c:f.slide;e||d.data("bs.carousel",e=new b(this,f)),"number"==typeof c?e.to(c):g?e[g]():f.interval&&e.pause().cycle()})},a.fn.carousel.Constructor=b,a.fn.carousel.noConflict=function(){return a.fn.carousel=c,this},a(document).on("click.bs.carousel.data-api","[data-slide], [data-slide-to]",function(b){var c,d=a(this),e=a(d.attr("data-target")||(c=d.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,"")),f=a.extend({},e.data(),d.data()),g=d.attr("data-slide-to");g&&(f.interval=!1),e.carousel(f),(g=d.attr("data-slide-to"))&&e.data("bs.carousel").to(g),b.preventDefault()}),a(window).on("load",function(){a('[data-ride="carousel"]').each(function(){var b=a(this);b.carousel(b.data())})})}(jQuery),+function(a){"use strict";var b=function(c,d){this.$element=a(c),this.options=a.extend({},b.DEFAULTS,d),this.transitioning=null,this.options.parent&&(this.$parent=a(this.options.parent)),this.options.toggle&&this.toggle()};b.DEFAULTS={toggle:!0},b.prototype.dimension=function(){var a=this.$element.hasClass("width");return a?"width":"height"},b.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var b=a.Event("show.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.$parent&&this.$parent.find("> .panel > .in");if(c&&c.length){var d=c.data("bs.collapse");if(d&&d.transitioning)return;c.collapse("hide"),d||c.data("bs.collapse",null)}var e=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[e](0),this.transitioning=1;var f=function(){this.$element.removeClass("collapsing").addClass("collapse in")[e]("auto"),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!a.support.transition)return f.call(this);var g=a.camelCase(["scroll",e].join("-"));this.$element.one(a.support.transition.end,a.proxy(f,this)).emulateTransitionEnd(350)[e](this.$element[0][g])}}},b.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var b=a.Event("hide.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.dimension();this.$element[c](this.$element[c]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse").removeClass("in"),this.transitioning=1;var d=function(){this.transitioning=0,this.$element.trigger("hidden.bs.collapse").removeClass("collapsing").addClass("collapse")};return a.support.transition?void this.$element[c](0).one(a.support.transition.end,a.proxy(d,this)).emulateTransitionEnd(350):d.call(this)}}},b.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()};var c=a.fn.collapse;a.fn.collapse=function(c){return this.each(function(){var d=a(this),e=d.data("bs.collapse"),f=a.extend({},b.DEFAULTS,d.data(),"object"==typeof c&&c);!e&&f.toggle&&"show"==c&&(c=!c),e||d.data("bs.collapse",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.collapse.Constructor=b,a.fn.collapse.noConflict=function(){return a.fn.collapse=c,this},a(document).on("click.bs.collapse.data-api","[data-toggle=collapse]",function(b){var c,d=a(this),e=d.attr("data-target")||b.preventDefault()||(c=d.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,""),f=a(e),g=f.data("bs.collapse"),h=g?"toggle":d.data(),i=d.attr("data-parent"),j=i&&a(i);g&&g.transitioning||(j&&j.find('[data-toggle=collapse][data-parent="'+i+'"]').not(d).addClass("collapsed"),d[f.hasClass("in")?"addClass":"removeClass"]("collapsed")),f.collapse(h)})}(jQuery),+function(a){"use strict";function b(b){a(d).remove(),a(e).each(function(){var d=c(a(this)),e={relatedTarget:this};d.hasClass("open")&&(d.trigger(b=a.Event("hide.bs.dropdown",e)),b.isDefaultPrevented()||d.removeClass("open").trigger("hidden.bs.dropdown",e))})}function c(b){var c=b.attr("data-target");c||(c=b.attr("href"),c=c&&/#[A-Za-z]/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,""));var d=c&&a(c);return d&&d.length?d:b.parent()}var d=".dropdown-backdrop",e="[data-toggle=dropdown]",f=function(b){a(b).on("click.bs.dropdown",this.toggle)};f.prototype.toggle=function(d){var e=a(this);if(!e.is(".disabled, :disabled")){var f=c(e),g=f.hasClass("open");if(b(),!g){"ontouchstart"in document.documentElement&&!f.closest(".navbar-nav").length&&a('<div class="dropdown-backdrop"/>').insertAfter(a(this)).on("click",b);var h={relatedTarget:this};if(f.trigger(d=a.Event("show.bs.dropdown",h)),d.isDefaultPrevented())return;f.toggleClass("open").trigger("shown.bs.dropdown",h),e.focus()}return!1}},f.prototype.keydown=function(b){if(/(38|40|27)/.test(b.keyCode)){var d=a(this);if(b.preventDefault(),b.stopPropagation(),!d.is(".disabled, :disabled")){var f=c(d),g=f.hasClass("open");if(!g||g&&27==b.keyCode)return 27==b.which&&f.find(e).focus(),d.click();var h=" li:not(.divider):visible a",i=f.find("[role=menu]"+h+", [role=listbox]"+h);if(i.length){var j=i.index(i.filter(":focus"));38==b.keyCode&&j>0&&j--,40==b.keyCode&&j<i.length-1&&j++,~j||(j=0),i.eq(j).focus()}}}};var g=a.fn.dropdown;a.fn.dropdown=function(b){return this.each(function(){var c=a(this),d=c.data("bs.dropdown");d||c.data("bs.dropdown",d=new f(this)),"string"==typeof b&&d[b].call(c)})},a.fn.dropdown.Constructor=f,a.fn.dropdown.noConflict=function(){return a.fn.dropdown=g,this},a(document).on("click.bs.dropdown.data-api",b).on("click.bs.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.bs.dropdown.data-api",e,f.prototype.toggle).on("keydown.bs.dropdown.data-api",e+", [role=menu], [role=listbox]",f.prototype.keydown)}(jQuery),+function(a){"use strict";var b=function(b,c){this.options=c,this.$element=a(b),this.$backdrop=this.isShown=null,this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,a.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};b.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},b.prototype.toggle=function(a){return this[this.isShown?"hide":"show"](a)},b.prototype.show=function(b){var c=this,d=a.Event("show.bs.modal",{relatedTarget:b});this.$element.trigger(d),this.isShown||d.isDefaultPrevented()||(this.isShown=!0,this.escape(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',a.proxy(this.hide,this)),this.backdrop(function(){var d=a.support.transition&&c.$element.hasClass("fade");c.$element.parent().length||c.$element.appendTo(document.body),c.$element.show().scrollTop(0),d&&c.$element[0].offsetWidth,c.$element.addClass("in").attr("aria-hidden",!1),c.enforceFocus();var e=a.Event("shown.bs.modal",{relatedTarget:b});d?c.$element.find(".modal-dialog").one(a.support.transition.end,function(){c.$element.focus().trigger(e)}).emulateTransitionEnd(300):c.$element.focus().trigger(e)}))},b.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.bs.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),a(document).off("focusin.bs.modal"),this.$element.removeClass("in").attr("aria-hidden",!0).off("click.dismiss.bs.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one(a.support.transition.end,a.proxy(this.hideModal,this)).emulateTransitionEnd(300):this.hideModal())},b.prototype.enforceFocus=function(){a(document).off("focusin.bs.modal").on("focusin.bs.modal",a.proxy(function(a){this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.focus()},this))},b.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keyup.dismiss.bs.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keyup.dismiss.bs.modal")},b.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.removeBackdrop(),a.$element.trigger("hidden.bs.modal")})},b.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},b.prototype.backdrop=function(b){var c=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var d=a.support.transition&&c;if(this.$backdrop=a('<div class="modal-backdrop '+c+'" />').appendTo(document.body),this.$element.on("click.dismiss.bs.modal",a.proxy(function(a){a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus.call(this.$element[0]):this.hide.call(this))},this)),d&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;d?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()}else!this.isShown&&this.$backdrop?(this.$backdrop.removeClass("in"),a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()):b&&b()};var c=a.fn.modal;a.fn.modal=function(c,d){return this.each(function(){var e=a(this),f=e.data("bs.modal"),g=a.extend({},b.DEFAULTS,e.data(),"object"==typeof c&&c);f||e.data("bs.modal",f=new b(this,g)),"string"==typeof c?f[c](d):g.show&&f.show(d)})},a.fn.modal.Constructor=b,a.fn.modal.noConflict=function(){return a.fn.modal=c,this},a(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(b){var c=a(this),d=c.attr("href"),e=a(c.attr("data-target")||d&&d.replace(/.*(?=#[^\s]+$)/,"")),f=e.data("bs.modal")?"toggle":a.extend({remote:!/#/.test(d)&&d},e.data(),c.data());c.is("a")&&b.preventDefault(),e.modal(f,this).one("hide",function(){c.is(":visible")&&c.focus()})}),a(document).on("show.bs.modal",".modal",function(){a(document.body).addClass("modal-open")}).on("hidden.bs.modal",".modal",function(){a(document.body).removeClass("modal-open")})}(jQuery),+function(a){"use strict";var b=function(a,b){this.type=this.options=this.enabled=this.timeout=this.hoverState=this.$element=null,this.init("tooltip",a,b)};b.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1},b.prototype.init=function(b,c,d){this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d);for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",i="hover"==g?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},b.prototype.getDefaults=function(){return b.DEFAULTS},b.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},b.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},b.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);return clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show)):c.show()},b.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);return clearTimeout(c.timeout),c.hoverState="out",c.options.delay&&c.options.delay.hide?void(c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide)):c.hide()},b.prototype.show=function(){var b=a.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){if(this.$element.trigger(b),b.isDefaultPrevented())return;var c=this,d=this.tip();this.setContent(),this.options.animation&&d.addClass("fade");var e="function"==typeof this.options.placement?this.options.placement.call(this,d[0],this.$element[0]):this.options.placement,f=/\s?auto?\s?/i,g=f.test(e);g&&(e=e.replace(f,"")||"top"),d.detach().css({top:0,left:0,display:"block"}).addClass(e),this.options.container?d.appendTo(this.options.container):d.insertAfter(this.$element);var h=this.getPosition(),i=d[0].offsetWidth,j=d[0].offsetHeight;if(g){var k=this.$element.parent(),l=e,m=document.documentElement.scrollTop||document.body.scrollTop,n="body"==this.options.container?window.innerWidth:k.outerWidth(),o="body"==this.options.container?window.innerHeight:k.outerHeight(),p="body"==this.options.container?0:k.offset().left;e="bottom"==e&&h.top+h.height+j-m>o?"top":"top"==e&&h.top-m-j<0?"bottom":"right"==e&&h.right+i>n?"left":"left"==e&&h.left-i<p?"right":e,d.removeClass(l).addClass(e)}var q=this.getCalculatedOffset(e,h,i,j);this.applyPlacement(q,e),this.hoverState=null;var r=function(){c.$element.trigger("shown.bs."+c.type)};a.support.transition&&this.$tip.hasClass("fade")?d.one(a.support.transition.end,r).emulateTransitionEnd(150):r()}},b.prototype.applyPlacement=function(b,c){var d,e=this.tip(),f=e[0].offsetWidth,g=e[0].offsetHeight,h=parseInt(e.css("margin-top"),10),i=parseInt(e.css("margin-left"),10);isNaN(h)&&(h=0),isNaN(i)&&(i=0),b.top=b.top+h,b.left=b.left+i,a.offset.setOffset(e[0],a.extend({using:function(a){e.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),e.addClass("in");var j=e[0].offsetWidth,k=e[0].offsetHeight;if("top"==c&&k!=g&&(d=!0,b.top=b.top+g-k),/bottom|top/.test(c)){var l=0;b.left<0&&(l=-2*b.left,b.left=0,e.offset(b),j=e[0].offsetWidth,k=e[0].offsetHeight),this.replaceArrow(l-f+j,j,"left")}else this.replaceArrow(k-g,k,"top");d&&e.offset(b)},b.prototype.replaceArrow=function(a,b,c){this.arrow().css(c,a?50*(1-a/b)+"%":"")},b.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},b.prototype.hide=function(){function b(){"in"!=c.hoverState&&d.detach(),c.$element.trigger("hidden.bs."+c.type)}var c=this,d=this.tip(),e=a.Event("hide.bs."+this.type);return this.$element.trigger(e),e.isDefaultPrevented()?void 0:(d.removeClass("in"),a.support.transition&&this.$tip.hasClass("fade")?d.one(a.support.transition.end,b).emulateTransitionEnd(150):b(),this.hoverState=null,this)},b.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},b.prototype.hasContent=function(){return this.getTitle()},b.prototype.getPosition=function(){var b=this.$element[0];return a.extend({},"function"==typeof b.getBoundingClientRect?b.getBoundingClientRect():{width:b.offsetWidth,height:b.offsetHeight},this.$element.offset())},b.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},b.prototype.getTitle=function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||("function"==typeof c.title?c.title.call(b[0]):c.title)},b.prototype.tip=function(){return this.$tip=this.$tip||a(this.options.template)},b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},b.prototype.validate=function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},b.prototype.enable=function(){this.enabled=!0},b.prototype.disable=function(){this.enabled=!1},b.prototype.toggleEnabled=function(){this.enabled=!this.enabled},b.prototype.toggle=function(b){var c=b?a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type):this;c.tip().hasClass("in")?c.leave(c):c.enter(c)},b.prototype.destroy=function(){clearTimeout(this.timeout),this.hide().$element.off("."+this.type).removeData("bs."+this.type)};var c=a.fn.tooltip;a.fn.tooltip=function(c){return this.each(function(){var d=a(this),e=d.data("bs.tooltip"),f="object"==typeof c&&c;(e||"destroy"!=c)&&(e||d.data("bs.tooltip",e=new b(this,f)),"string"==typeof c&&e[c]())})},a.fn.tooltip.Constructor=b,a.fn.tooltip.noConflict=function(){return a.fn.tooltip=c,this}}(jQuery),+function(a){"use strict";var b=function(a,b){this.init("popover",a,b)};if(!a.fn.tooltip)throw new Error("Popover requires tooltip.js");b.DEFAULTS=a.extend({},a.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}),b.prototype=a.extend({},a.fn.tooltip.Constructor.prototype),b.prototype.constructor=b,b.prototype.getDefaults=function(){return b.DEFAULTS},b.prototype.setContent=function(){var a=this.tip(),b=this.getTitle(),c=this.getContent();a.find(".popover-title")[this.options.html?"html":"text"](b),a.find(".popover-content")[this.options.html?"string"==typeof c?"html":"append":"text"](c),a.removeClass("fade top bottom left right in"),a.find(".popover-title").html()||a.find(".popover-title").hide()},b.prototype.hasContent=function(){return this.getTitle()||this.getContent()},b.prototype.getContent=function(){var a=this.$element,b=this.options;return a.attr("data-content")||("function"==typeof b.content?b.content.call(a[0]):b.content)},b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")},b.prototype.tip=function(){return this.$tip||(this.$tip=a(this.options.template)),this.$tip};var c=a.fn.popover;a.fn.popover=function(c){return this.each(function(){var d=a(this),e=d.data("bs.popover"),f="object"==typeof c&&c;(e||"destroy"!=c)&&(e||d.data("bs.popover",e=new b(this,f)),"string"==typeof c&&e[c]())})},a.fn.popover.Constructor=b,a.fn.popover.noConflict=function(){return a.fn.popover=c,this}}(jQuery),+function(a){"use strict";function b(c,d){var e,f=a.proxy(this.process,this);this.$element=a(a(c).is("body")?window:c),this.$body=a("body"),this.$scrollElement=this.$element.on("scroll.bs.scroll-spy.data-api",f),this.options=a.extend({},b.DEFAULTS,d),this.selector=(this.options.target||(e=a(c).attr("href"))&&e.replace(/.*(?=#[^\s]+$)/,"")||"")+" .nav li > a",this.offsets=a([]),this.targets=a([]),this.activeTarget=null,this.refresh(),this.process()}b.DEFAULTS={offset:10},b.prototype.refresh=function(){var b=this.$element[0]==window?"offset":"position";this.offsets=a([]),this.targets=a([]);{var c=this;this.$body.find(this.selector).map(function(){var d=a(this),e=d.data("target")||d.attr("href"),f=/^#./.test(e)&&a(e);return f&&f.length&&f.is(":visible")&&[[f[b]().top+(!a.isWindow(c.$scrollElement.get(0))&&c.$scrollElement.scrollTop()),e]]||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){c.offsets.push(this[0]),c.targets.push(this[1])})}},b.prototype.process=function(){var a,b=this.$scrollElement.scrollTop()+this.options.offset,c=this.$scrollElement[0].scrollHeight||this.$body[0].scrollHeight,d=c-this.$scrollElement.height(),e=this.offsets,f=this.targets,g=this.activeTarget;if(b>=d)return g!=(a=f.last()[0])&&this.activate(a);if(g&&b<=e[0])return g!=(a=f[0])&&this.activate(a);for(a=e.length;a--;)g!=f[a]&&b>=e[a]&&(!e[a+1]||b<=e[a+1])&&this.activate(f[a])},b.prototype.activate=function(b){this.activeTarget=b,a(this.selector).parentsUntil(this.options.target,".active").removeClass("active");var c=this.selector+'[data-target="'+b+'"],'+this.selector+'[href="'+b+'"]',d=a(c).parents("li").addClass("active");d.parent(".dropdown-menu").length&&(d=d.closest("li.dropdown").addClass("active")),d.trigger("activate.bs.scrollspy")};var c=a.fn.scrollspy;a.fn.scrollspy=function(c){return this.each(function(){var d=a(this),e=d.data("bs.scrollspy"),f="object"==typeof c&&c;e||d.data("bs.scrollspy",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.scrollspy.Constructor=b,a.fn.scrollspy.noConflict=function(){return a.fn.scrollspy=c,this},a(window).on("load",function(){a('[data-spy="scroll"]').each(function(){var b=a(this);b.scrollspy(b.data())})})}(jQuery),+function(a){"use strict";var b=function(b){this.element=a(b)};b.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a")[0],f=a.Event("show.bs.tab",{relatedTarget:e});if(b.trigger(f),!f.isDefaultPrevented()){var g=a(d);this.activate(b.parent("li"),c),this.activate(g,g.parent(),function(){b.trigger({type:"shown.bs.tab",relatedTarget:e})})}}},b.prototype.activate=function(b,c,d){function e(){f.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"),b.addClass("active"),g?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu")&&b.closest("li.dropdown").addClass("active"),d&&d()}var f=c.find("> .active"),g=d&&a.support.transition&&f.hasClass("fade");g?f.one(a.support.transition.end,e).emulateTransitionEnd(150):e(),f.removeClass("in")};var c=a.fn.tab;a.fn.tab=function(c){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new b(this)),"string"==typeof c&&e[c]()})},a.fn.tab.Constructor=b,a.fn.tab.noConflict=function(){return a.fn.tab=c,this},a(document).on("click.bs.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(b){b.preventDefault(),a(this).tab("show")})}(jQuery),+function(a){"use strict";var b=function(c,d){this.options=a.extend({},b.DEFAULTS,d),this.$window=a(window).on("scroll.bs.affix.data-api",a.proxy(this.checkPosition,this)).on("click.bs.affix.data-api",a.proxy(this.checkPositionWithEventLoop,this)),this.$element=a(c),this.affixed=this.unpin=this.pinnedOffset=null,this.checkPosition()};b.RESET="affix affix-top affix-bottom",b.DEFAULTS={offset:0},b.prototype.getPinnedOffset=function(){if(this.pinnedOffset)return this.pinnedOffset;this.$element.removeClass(b.RESET).addClass("affix");var a=this.$window.scrollTop(),c=this.$element.offset();return this.pinnedOffset=c.top-a},b.prototype.checkPositionWithEventLoop=function(){setTimeout(a.proxy(this.checkPosition,this),1)},b.prototype.checkPosition=function(){if(this.$element.is(":visible")){var c=a(document).height(),d=this.$window.scrollTop(),e=this.$element.offset(),f=this.options.offset,g=f.top,h=f.bottom;"top"==this.affixed&&(e.top+=d),"object"!=typeof f&&(h=g=f),"function"==typeof g&&(g=f.top(this.$element)),"function"==typeof h&&(h=f.bottom(this.$element));var i=null!=this.unpin&&d+this.unpin<=e.top?!1:null!=h&&e.top+this.$element.height()>=c-h?"bottom":null!=g&&g>=d?"top":!1;if(this.affixed!==i){this.unpin&&this.$element.css("top","");var j="affix"+(i?"-"+i:""),k=a.Event(j+".bs.affix");this.$element.trigger(k),k.isDefaultPrevented()||(this.affixed=i,this.unpin="bottom"==i?this.getPinnedOffset():null,this.$element.removeClass(b.RESET).addClass(j).trigger(a.Event(j.replace("affix","affixed"))),"bottom"==i&&this.$element.offset({top:c-h-this.$element.height()}))}}};var c=a.fn.affix;a.fn.affix=function(c){return this.each(function(){var d=a(this),e=d.data("bs.affix"),f="object"==typeof c&&c;e||d.data("bs.affix",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.affix.Constructor=b,a.fn.affix.noConflict=function(){return a.fn.affix=c,this},a(window).on("load",function(){a('[data-spy="affix"]').each(function(){var b=a(this),c=b.data();c.offset=c.offset||{},c.offsetBottom&&(c.offset.bottom=c.offsetBottom),c.offsetTop&&(c.offset.top=c.offsetTop),b.affix(c)})})}(jQuery);
// /*!
//  * Bootstrap v3.0.2 by @fat and @mdo
//  * Copyright 2013 Twitter, Inc.
//  * Licensed under http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Designed and built with all the love in the world by @mdo and @fat.
//  */

// if (typeof jQuery === "undefined") { throw new Error("Bootstrap requires jQuery") }

// /* ========================================================================
//  * Bootstrap: transition.js v3.0.2
//  * http://getbootstrap.com/javascript/#transitions
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
//   // ============================================================

//   function transitionEnd() {
//     var el = document.createElement('bootstrap')

//     var transEndEventNames = {
//       'WebkitTransition' : 'webkitTransitionEnd'
//     , 'MozTransition'    : 'transitionend'
//     , 'OTransition'      : 'oTransitionEnd otransitionend'
//     , 'transition'       : 'transitionend'
//     }

//     for (var name in transEndEventNames) {
//       if (el.style[name] !== undefined) {
//         return { end: transEndEventNames[name] }
//       }
//     }
//   }

//   // http://blog.alexmaccaw.com/css-transitions
//   $.fn.emulateTransitionEnd = function (duration) {
//     var called = false, $el = this
//     $(this).one($.support.transition.end, function () { called = true })
//     var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
//     setTimeout(callback, duration)
//     return this
//   }

//   $(function () {
//     $.support.transition = transitionEnd()
//   })

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: alert.js v3.0.2
//  * http://getbootstrap.com/javascript/#alerts
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // ALERT CLASS DEFINITION
//   // ======================

//   var dismiss = '[data-dismiss="alert"]'
//   var Alert   = function (el) {
//     $(el).on('click', dismiss, this.close)
//   }

//   Alert.prototype.close = function (e) {
//     var $this    = $(this)
//     var selector = $this.attr('data-target')

//     if (!selector) {
//       selector = $this.attr('href')
//       selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
//     }

//     var $parent = $(selector)

//     if (e) e.preventDefault()

//     if (!$parent.length) {
//       $parent = $this.hasClass('alert') ? $this : $this.parent()
//     }

//     $parent.trigger(e = $.Event('close.bs.alert'))

//     if (e.isDefaultPrevented()) return

//     $parent.removeClass('in')

//     function removeElement() {
//       $parent.trigger('closed.bs.alert').remove()
//     }

//     $.support.transition && $parent.hasClass('fade') ?
//       $parent
//         .one($.support.transition.end, removeElement)
//         .emulateTransitionEnd(150) :
//       removeElement()
//   }


//   // ALERT PLUGIN DEFINITION
//   // =======================

//   var old = $.fn.alert

//   $.fn.alert = function (option) {
//     return this.each(function () {
//       var $this = $(this)
//       var data  = $this.data('bs.alert')

//       if (!data) $this.data('bs.alert', (data = new Alert(this)))
//       if (typeof option == 'string') data[option].call($this)
//     })
//   }

//   $.fn.alert.Constructor = Alert


//   // ALERT NO CONFLICT
//   // =================

//   $.fn.alert.noConflict = function () {
//     $.fn.alert = old
//     return this
//   }


//   // ALERT DATA-API
//   // ==============

//   $(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: button.js v3.0.2
//  * http://getbootstrap.com/javascript/#buttons
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // BUTTON PUBLIC CLASS DEFINITION
//   // ==============================

//   var Button = function (element, options) {
//     this.$element = $(element)
//     this.options  = $.extend({}, Button.DEFAULTS, options)
//   }

//   Button.DEFAULTS = {
//     loadingText: 'loading...'
//   }

//   Button.prototype.setState = function (state) {
//     var d    = 'disabled'
//     var $el  = this.$element
//     var val  = $el.is('input') ? 'val' : 'html'
//     var data = $el.data()

//     state = state + 'Text'

//     if (!data.resetText) $el.data('resetText', $el[val]())

//     $el[val](data[state] || this.options[state])

//     // push to event loop to allow forms to submit
//     setTimeout(function () {
//       state == 'loadingText' ?
//         $el.addClass(d).attr(d, d) :
//         $el.removeClass(d).removeAttr(d);
//     }, 0)
//   }

//   Button.prototype.toggle = function () {
//     var $parent = this.$element.closest('[data-toggle="buttons"]')
//     if ($parent.length) {
//       var $input = this.$element.find('input')
//         .prop('checked', !this.$element.hasClass('active'))
//         .trigger('change')
//       if ($input.prop('type') === 'radio') $parent.find('.active').removeClass('active')
//     }

//     this.$element.toggleClass('active')
//   }


//   // BUTTON PLUGIN DEFINITION
//   // ========================

//   var old = $.fn.button

//   $.fn.button = function (option) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.button')
//       var options = typeof option == 'object' && option

//       if (!data) $this.data('bs.button', (data = new Button(this, options)))

//       if (option == 'toggle') data.toggle()
//       else if (option) data.setState(option)
//     })
//   }

//   $.fn.button.Constructor = Button


//   // BUTTON NO CONFLICT
//   // ==================

//   $.fn.button.noConflict = function () {
//     $.fn.button = old
//     return this
//   }


//   // BUTTON DATA-API
//   // ===============

//   $(document).on('click.bs.button.data-api', '[data-toggle^=button]', function (e) {
//     var $btn = $(e.target)
//     if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn')
//     $btn.button('toggle')
//     e.preventDefault()
//   })

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: carousel.js v3.0.2
//  * http://getbootstrap.com/javascript/#carousel
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // CAROUSEL CLASS DEFINITION
//   // =========================

//   var Carousel = function (element, options) {
//     this.$element    = $(element)
//     this.$indicators = this.$element.find('.carousel-indicators')
//     this.options     = options
//     this.paused      =
//     this.sliding     =
//     this.interval    =
//     this.$active     =
//     this.$items      = null

//     this.options.pause == 'hover' && this.$element
//       .on('mouseenter', $.proxy(this.pause, this))
//       .on('mouseleave', $.proxy(this.cycle, this))
//   }

//   Carousel.DEFAULTS = {
//     interval: 5000
//   , pause: 'hover'
//   , wrap: true
//   }

//   Carousel.prototype.cycle =  function (e) {
//     e || (this.paused = false)

//     this.interval && clearInterval(this.interval)

//     this.options.interval
//       && !this.paused
//       && (this.interval = setInterval($.proxy(this.next, this), this.options.interval))

//     return this
//   }

//   Carousel.prototype.getActiveIndex = function () {
//     this.$active = this.$element.find('.item.active')
//     this.$items  = this.$active.parent().children()

//     return this.$items.index(this.$active)
//   }

//   Carousel.prototype.to = function (pos) {
//     var that        = this
//     var activeIndex = this.getActiveIndex()

//     if (pos > (this.$items.length - 1) || pos < 0) return

//     if (this.sliding)       return this.$element.one('slid', function () { that.to(pos) })
//     if (activeIndex == pos) return this.pause().cycle()

//     return this.slide(pos > activeIndex ? 'next' : 'prev', $(this.$items[pos]))
//   }

//   Carousel.prototype.pause = function (e) {
//     e || (this.paused = true)

//     if (this.$element.find('.next, .prev').length && $.support.transition.end) {
//       this.$element.trigger($.support.transition.end)
//       this.cycle(true)
//     }

//     this.interval = clearInterval(this.interval)

//     return this
//   }

//   Carousel.prototype.next = function () {
//     if (this.sliding) return
//     return this.slide('next')
//   }

//   Carousel.prototype.prev = function () {
//     if (this.sliding) return
//     return this.slide('prev')
//   }

//   Carousel.prototype.slide = function (type, next) {
//     var $active   = this.$element.find('.item.active')
//     var $next     = next || $active[type]()
//     var isCycling = this.interval
//     var direction = type == 'next' ? 'left' : 'right'
//     var fallback  = type == 'next' ? 'first' : 'last'
//     var that      = this

//     if (!$next.length) {
//       if (!this.options.wrap) return
//       $next = this.$element.find('.item')[fallback]()
//     }

//     this.sliding = true

//     isCycling && this.pause()

//     var e = $.Event('slide.bs.carousel', { relatedTarget: $next[0], direction: direction })

//     if ($next.hasClass('active')) return

//     if (this.$indicators.length) {
//       this.$indicators.find('.active').removeClass('active')
//       this.$element.one('slid', function () {
//         var $nextIndicator = $(that.$indicators.children()[that.getActiveIndex()])
//         $nextIndicator && $nextIndicator.addClass('active')
//       })
//     }

//     if ($.support.transition && this.$element.hasClass('slide')) {
//       this.$element.trigger(e)
//       if (e.isDefaultPrevented()) return
//       $next.addClass(type)
//       $next[0].offsetWidth // force reflow
//       $active.addClass(direction)
//       $next.addClass(direction)
//       $active
//         .one($.support.transition.end, function () {
//           $next.removeClass([type, direction].join(' ')).addClass('active')
//           $active.removeClass(['active', direction].join(' '))
//           that.sliding = false
//           setTimeout(function () { that.$element.trigger('slid') }, 0)
//         })
//         .emulateTransitionEnd(600)
//     } else {
//       this.$element.trigger(e)
//       if (e.isDefaultPrevented()) return
//       $active.removeClass('active')
//       $next.addClass('active')
//       this.sliding = false
//       this.$element.trigger('slid')
//     }

//     isCycling && this.cycle()

//     return this
//   }


//   // CAROUSEL PLUGIN DEFINITION
//   // ==========================

//   var old = $.fn.carousel

//   $.fn.carousel = function (option) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.carousel')
//       var options = $.extend({}, Carousel.DEFAULTS, $this.data(), typeof option == 'object' && option)
//       var action  = typeof option == 'string' ? option : options.slide

//       if (!data) $this.data('bs.carousel', (data = new Carousel(this, options)))
//       if (typeof option == 'number') data.to(option)
//       else if (action) data[action]()
//       else if (options.interval) data.pause().cycle()
//     })
//   }

//   $.fn.carousel.Constructor = Carousel


//   // CAROUSEL NO CONFLICT
//   // ====================

//   $.fn.carousel.noConflict = function () {
//     $.fn.carousel = old
//     return this
//   }


//   // CAROUSEL DATA-API
//   // =================

//   $(document).on('click.bs.carousel.data-api', '[data-slide], [data-slide-to]', function (e) {
//     var $this   = $(this), href
//     var $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
//     var options = $.extend({}, $target.data(), $this.data())
//     var slideIndex = $this.attr('data-slide-to')
//     if (slideIndex) options.interval = false

//     $target.carousel(options)

//     if (slideIndex = $this.attr('data-slide-to')) {
//       $target.data('bs.carousel').to(slideIndex)
//     }

//     e.preventDefault()
//   })

//   $(window).on('load', function () {
//     $('[data-ride="carousel"]').each(function () {
//       var $carousel = $(this)
//       $carousel.carousel($carousel.data())
//     })
//   })

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: collapse.js v3.0.2
//  * http://getbootstrap.com/javascript/#collapse
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // COLLAPSE PUBLIC CLASS DEFINITION
//   // ================================

//   var Collapse = function (element, options) {
//     this.$element      = $(element)
//     this.options       = $.extend({}, Collapse.DEFAULTS, options)
//     this.transitioning = null

//     if (this.options.parent) this.$parent = $(this.options.parent)
//     if (this.options.toggle) this.toggle()
//   }

//   Collapse.DEFAULTS = {
//     toggle: true
//   }

//   Collapse.prototype.dimension = function () {
//     var hasWidth = this.$element.hasClass('width')
//     return hasWidth ? 'width' : 'height'
//   }

//   Collapse.prototype.show = function () {
//     if (this.transitioning || this.$element.hasClass('in')) return

//     var startEvent = $.Event('show.bs.collapse')
//     this.$element.trigger(startEvent)
//     if (startEvent.isDefaultPrevented()) return

//     var actives = this.$parent && this.$parent.find('> .panel > .in')

//     if (actives && actives.length) {
//       var hasData = actives.data('bs.collapse')
//       if (hasData && hasData.transitioning) return
//       actives.collapse('hide')
//       hasData || actives.data('bs.collapse', null)
//     }

//     var dimension = this.dimension()

//     this.$element
//       .removeClass('collapse')
//       .addClass('collapsing')
//       [dimension](0)

//     this.transitioning = 1

//     var complete = function () {
//       this.$element
//         .removeClass('collapsing')
//         .addClass('in')
//         [dimension]('auto')
//       this.transitioning = 0
//       this.$element.trigger('shown.bs.collapse')
//     }

//     if (!$.support.transition) return complete.call(this)

//     var scrollSize = $.camelCase(['scroll', dimension].join('-'))

//     this.$element
//       .one($.support.transition.end, $.proxy(complete, this))
//       .emulateTransitionEnd(350)
//       [dimension](this.$element[0][scrollSize])
//   }

//   Collapse.prototype.hide = function () {
//     if (this.transitioning || !this.$element.hasClass('in')) return

//     var startEvent = $.Event('hide.bs.collapse')
//     this.$element.trigger(startEvent)
//     if (startEvent.isDefaultPrevented()) return

//     var dimension = this.dimension()

//     this.$element
//       [dimension](this.$element[dimension]())
//       [0].offsetHeight

//     this.$element
//       .addClass('collapsing')
//       .removeClass('collapse')
//       .removeClass('in')

//     this.transitioning = 1

//     var complete = function () {
//       this.transitioning = 0
//       this.$element
//         .trigger('hidden.bs.collapse')
//         .removeClass('collapsing')
//         .addClass('collapse')
//     }

//     if (!$.support.transition) return complete.call(this)

//     this.$element
//       [dimension](0)
//       .one($.support.transition.end, $.proxy(complete, this))
//       .emulateTransitionEnd(350)
//   }

//   Collapse.prototype.toggle = function () {
//     this[this.$element.hasClass('in') ? 'hide' : 'show']()
//   }


//   // COLLAPSE PLUGIN DEFINITION
//   // ==========================

//   var old = $.fn.collapse

//   $.fn.collapse = function (option) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.collapse')
//       var options = $.extend({}, Collapse.DEFAULTS, $this.data(), typeof option == 'object' && option)

//       if (!data) $this.data('bs.collapse', (data = new Collapse(this, options)))
//       if (typeof option == 'string') data[option]()
//     })
//   }

//   $.fn.collapse.Constructor = Collapse


//   // COLLAPSE NO CONFLICT
//   // ====================

//   $.fn.collapse.noConflict = function () {
//     $.fn.collapse = old
//     return this
//   }


//   // COLLAPSE DATA-API
//   // =================

//   $(document).on('click.bs.collapse.data-api', '[data-toggle=collapse]', function (e) {
//     var $this   = $(this), href
//     var target  = $this.attr('data-target')
//         || e.preventDefault()
//         || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') //strip for ie7
//     var $target = $(target)
//     var data    = $target.data('bs.collapse')
//     var option  = data ? 'toggle' : $this.data()
//     var parent  = $this.attr('data-parent')
//     var $parent = parent && $(parent)

//     if (!data || !data.transitioning) {
//       if ($parent) $parent.find('[data-toggle=collapse][data-parent="' + parent + '"]').not($this).addClass('collapsed')
//       $this[$target.hasClass('in') ? 'addClass' : 'removeClass']('collapsed')
//     }

//     $target.collapse(option)
//   })

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: dropdown.js v3.0.2
//  * http://getbootstrap.com/javascript/#dropdowns
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // DROPDOWN CLASS DEFINITION
//   // =========================

//   var backdrop = '.dropdown-backdrop'
//   var toggle   = '[data-toggle=dropdown]'
//   var Dropdown = function (element) {
//     var $el = $(element).on('click.bs.dropdown', this.toggle)
//   }

//   Dropdown.prototype.toggle = function (e) {
//     var $this = $(this)

//     if ($this.is('.disabled, :disabled')) return

//     var $parent  = getParent($this)
//     var isActive = $parent.hasClass('open')

//     clearMenus()

//     if (!isActive) {
//       if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
//         // if mobile we we use a backdrop because click events don't delegate
//         $('<div class="dropdown-backdrop"/>').insertAfter($(this)).on('click', clearMenus)
//       }

//       $parent.trigger(e = $.Event('show.bs.dropdown'))

//       if (e.isDefaultPrevented()) return

//       $parent
//         .toggleClass('open')
//         .trigger('shown.bs.dropdown')

//       $this.focus()
//     }

//     return false
//   }

//   Dropdown.prototype.keydown = function (e) {
//     if (!/(38|40|27)/.test(e.keyCode)) return

//     var $this = $(this)

//     e.preventDefault()
//     e.stopPropagation()

//     if ($this.is('.disabled, :disabled')) return

//     var $parent  = getParent($this)
//     var isActive = $parent.hasClass('open')

//     if (!isActive || (isActive && e.keyCode == 27)) {
//       if (e.which == 27) $parent.find(toggle).focus()
//       return $this.click()
//     }

//     var $items = $('[role=menu] li:not(.divider):visible a', $parent)

//     if (!$items.length) return

//     var index = $items.index($items.filter(':focus'))

//     if (e.keyCode == 38 && index > 0)                 index--                        // up
//     if (e.keyCode == 40 && index < $items.length - 1) index++                        // down
//     if (!~index)                                      index=0

//     $items.eq(index).focus()
//   }

//   function clearMenus() {
//     $(backdrop).remove()
//     $(toggle).each(function (e) {
//       var $parent = getParent($(this))
//       if (!$parent.hasClass('open')) return
//       $parent.trigger(e = $.Event('hide.bs.dropdown'))
//       if (e.isDefaultPrevented()) return
//       $parent.removeClass('open').trigger('hidden.bs.dropdown')
//     })
//   }

//   function getParent($this) {
//     var selector = $this.attr('data-target')

//     if (!selector) {
//       selector = $this.attr('href')
//       selector = selector && /#/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
//     }

//     var $parent = selector && $(selector)

//     return $parent && $parent.length ? $parent : $this.parent()
//   }


//   // DROPDOWN PLUGIN DEFINITION
//   // ==========================

//   var old = $.fn.dropdown

//   $.fn.dropdown = function (option) {
//     return this.each(function () {
//       var $this = $(this)
//       var data  = $this.data('dropdown')

//       if (!data) $this.data('dropdown', (data = new Dropdown(this)))
//       if (typeof option == 'string') data[option].call($this)
//     })
//   }

//   $.fn.dropdown.Constructor = Dropdown


//   // DROPDOWN NO CONFLICT
//   // ====================

//   $.fn.dropdown.noConflict = function () {
//     $.fn.dropdown = old
//     return this
//   }


//   // APPLY TO STANDARD DROPDOWN ELEMENTS
//   // ===================================

//   $(document)
//     .on('click.bs.dropdown.data-api', clearMenus)
//     .on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
//     .on('click.bs.dropdown.data-api'  , toggle, Dropdown.prototype.toggle)
//     .on('keydown.bs.dropdown.data-api', toggle + ', [role=menu]' , Dropdown.prototype.keydown)

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: modal.js v3.0.2
//  * http://getbootstrap.com/javascript/#modals
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // MODAL CLASS DEFINITION
//   // ======================

//   var Modal = function (element, options) {
//     this.options   = options
//     this.$element  = $(element)
//     this.$backdrop =
//     this.isShown   = null

//     if (this.options.remote) this.$element.load(this.options.remote)
//   }

//   Modal.DEFAULTS = {
//       backdrop: true
//     , keyboard: true
//     , show: true
//   }

//   Modal.prototype.toggle = function (_relatedTarget) {
//     return this[!this.isShown ? 'show' : 'hide'](_relatedTarget)
//   }

//   Modal.prototype.show = function (_relatedTarget) {
//     var that = this
//     var e    = $.Event('show.bs.modal', { relatedTarget: _relatedTarget })

//     this.$element.trigger(e)

//     if (this.isShown || e.isDefaultPrevented()) return

//     this.isShown = true

//     this.escape()

//     this.$element.on('click.dismiss.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))

//     this.backdrop(function () {
//       var transition = $.support.transition && that.$element.hasClass('fade')

//       if (!that.$element.parent().length) {
//         that.$element.appendTo(document.body) // don't move modals dom position
//       }

//       that.$element.show()

//       if (transition) {
//         that.$element[0].offsetWidth // force reflow
//       }

//       that.$element
//         .addClass('in')
//         .attr('aria-hidden', false)

//       that.enforceFocus()

//       var e = $.Event('shown.bs.modal', { relatedTarget: _relatedTarget })

//       transition ?
//         that.$element.find('.modal-dialog') // wait for modal to slide in
//           .one($.support.transition.end, function () {
//             that.$element.focus().trigger(e)
//           })
//           .emulateTransitionEnd(300) :
//         that.$element.focus().trigger(e)
//     })
//   }

//   Modal.prototype.hide = function (e) {
//     if (e) e.preventDefault()

//     e = $.Event('hide.bs.modal')

//     this.$element.trigger(e)

//     if (!this.isShown || e.isDefaultPrevented()) return

//     this.isShown = false

//     this.escape()

//     $(document).off('focusin.bs.modal')

//     this.$element
//       .removeClass('in')
//       .attr('aria-hidden', true)
//       .off('click.dismiss.modal')

//     $.support.transition && this.$element.hasClass('fade') ?
//       this.$element
//         .one($.support.transition.end, $.proxy(this.hideModal, this))
//         .emulateTransitionEnd(300) :
//       this.hideModal()
//   }

//   Modal.prototype.enforceFocus = function () {
//     $(document)
//       .off('focusin.bs.modal') // guard against infinite focus loop
//       .on('focusin.bs.modal', $.proxy(function (e) {
//         if (this.$element[0] !== e.target && !this.$element.has(e.target).length) {
//           this.$element.focus()
//         }
//       }, this))
//   }

//   Modal.prototype.escape = function () {
//     if (this.isShown && this.options.keyboard) {
//       this.$element.on('keyup.dismiss.bs.modal', $.proxy(function (e) {
//         e.which == 27 && this.hide()
//       }, this))
//     } else if (!this.isShown) {
//       this.$element.off('keyup.dismiss.bs.modal')
//     }
//   }

//   Modal.prototype.hideModal = function () {
//     var that = this
//     this.$element.hide()
//     this.backdrop(function () {
//       that.removeBackdrop()
//       that.$element.trigger('hidden.bs.modal')
//     })
//   }

//   Modal.prototype.removeBackdrop = function () {
//     this.$backdrop && this.$backdrop.remove()
//     this.$backdrop = null
//   }

//   Modal.prototype.backdrop = function (callback) {
//     var that    = this
//     var animate = this.$element.hasClass('fade') ? 'fade' : ''

//     if (this.isShown && this.options.backdrop) {
//       var doAnimate = $.support.transition && animate

//       this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
//         .appendTo(document.body)

//       this.$element.on('click.dismiss.modal', $.proxy(function (e) {
//         if (e.target !== e.currentTarget) return
//         this.options.backdrop == 'static'
//           ? this.$element[0].focus.call(this.$element[0])
//           : this.hide.call(this)
//       }, this))

//       if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

//       this.$backdrop.addClass('in')

//       if (!callback) return

//       doAnimate ?
//         this.$backdrop
//           .one($.support.transition.end, callback)
//           .emulateTransitionEnd(150) :
//         callback()

//     } else if (!this.isShown && this.$backdrop) {
//       this.$backdrop.removeClass('in')

//       $.support.transition && this.$element.hasClass('fade')?
//         this.$backdrop
//           .one($.support.transition.end, callback)
//           .emulateTransitionEnd(150) :
//         callback()

//     } else if (callback) {
//       callback()
//     }
//   }


//   // MODAL PLUGIN DEFINITION
//   // =======================

//   var old = $.fn.modal

//   $.fn.modal = function (option, _relatedTarget) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.modal')
//       var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)

//       if (!data) $this.data('bs.modal', (data = new Modal(this, options)))
//       if (typeof option == 'string') data[option](_relatedTarget)
//       else if (options.show) data.show(_relatedTarget)
//     })
//   }

//   $.fn.modal.Constructor = Modal


//   // MODAL NO CONFLICT
//   // =================

//   $.fn.modal.noConflict = function () {
//     $.fn.modal = old
//     return this
//   }


//   // MODAL DATA-API
//   // ==============

//   $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
//     var $this   = $(this)
//     var href    = $this.attr('href')
//     var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
//     var option  = $target.data('modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

//     e.preventDefault()

//     $target
//       .modal(option, this)
//       .one('hide', function () {
//         $this.is(':visible') && $this.focus()
//       })
//   })

//   $(document)
//     .on('show.bs.modal',  '.modal', function () { $(document.body).addClass('modal-open') })
//     .on('hidden.bs.modal', '.modal', function () { $(document.body).removeClass('modal-open') })

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: tooltip.js v3.0.2
//  * http://getbootstrap.com/javascript/#tooltip
//  * Inspired by the original jQuery.tipsy by Jason Frame
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // TOOLTIP PUBLIC CLASS DEFINITION
//   // ===============================

//   var Tooltip = function (element, options) {
//     this.type       =
//     this.options    =
//     this.enabled    =
//     this.timeout    =
//     this.hoverState =
//     this.$element   = null

//     this.init('tooltip', element, options)
//   }

//   Tooltip.DEFAULTS = {
//     animation: true
//   , placement: 'top'
//   , selector: false
//   , template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
//   , trigger: 'hover focus'
//   , title: ''
//   , delay: 0
//   , html: false
//   , container: false
//   }

//   Tooltip.prototype.init = function (type, element, options) {
//     this.enabled  = true
//     this.type     = type
//     this.$element = $(element)
//     this.options  = this.getOptions(options)

//     var triggers = this.options.trigger.split(' ')

//     for (var i = triggers.length; i--;) {
//       var trigger = triggers[i]

//       if (trigger == 'click') {
//         this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
//       } else if (trigger != 'manual') {
//         var eventIn  = trigger == 'hover' ? 'mouseenter' : 'focus'
//         var eventOut = trigger == 'hover' ? 'mouseleave' : 'blur'

//         this.$element.on(eventIn  + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
//         this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
//       }
//     }

//     this.options.selector ?
//       (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
//       this.fixTitle()
//   }

//   Tooltip.prototype.getDefaults = function () {
//     return Tooltip.DEFAULTS
//   }

//   Tooltip.prototype.getOptions = function (options) {
//     options = $.extend({}, this.getDefaults(), this.$element.data(), options)

//     if (options.delay && typeof options.delay == 'number') {
//       options.delay = {
//         show: options.delay
//       , hide: options.delay
//       }
//     }

//     return options
//   }

//   Tooltip.prototype.getDelegateOptions = function () {
//     var options  = {}
//     var defaults = this.getDefaults()

//     this._options && $.each(this._options, function (key, value) {
//       if (defaults[key] != value) options[key] = value
//     })

//     return options
//   }

//   Tooltip.prototype.enter = function (obj) {
//     var self = obj instanceof this.constructor ?
//       obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)

//     clearTimeout(self.timeout)

//     self.hoverState = 'in'

//     if (!self.options.delay || !self.options.delay.show) return self.show()

//     self.timeout = setTimeout(function () {
//       if (self.hoverState == 'in') self.show()
//     }, self.options.delay.show)
//   }

//   Tooltip.prototype.leave = function (obj) {
//     var self = obj instanceof this.constructor ?
//       obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)

//     clearTimeout(self.timeout)

//     self.hoverState = 'out'

//     if (!self.options.delay || !self.options.delay.hide) return self.hide()

//     self.timeout = setTimeout(function () {
//       if (self.hoverState == 'out') self.hide()
//     }, self.options.delay.hide)
//   }

//   Tooltip.prototype.show = function () {
//     var e = $.Event('show.bs.'+ this.type)

//     if (this.hasContent() && this.enabled) {
//       this.$element.trigger(e)

//       if (e.isDefaultPrevented()) return

//       var $tip = this.tip()

//       this.setContent()

//       if (this.options.animation) $tip.addClass('fade')

//       var placement = typeof this.options.placement == 'function' ?
//         this.options.placement.call(this, $tip[0], this.$element[0]) :
//         this.options.placement

//       var autoToken = /\s?auto?\s?/i
//       var autoPlace = autoToken.test(placement)
//       if (autoPlace) placement = placement.replace(autoToken, '') || 'top'

//       $tip
//         .detach()
//         .css({ top: 0, left: 0, display: 'block' })
//         .addClass(placement)

//       this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)

//       var pos          = this.getPosition()
//       var actualWidth  = $tip[0].offsetWidth
//       var actualHeight = $tip[0].offsetHeight

//       if (autoPlace) {
//         var $parent = this.$element.parent()

//         var orgPlacement = placement
//         var docScroll    = document.documentElement.scrollTop || document.body.scrollTop
//         var parentWidth  = this.options.container == 'body' ? window.innerWidth  : $parent.outerWidth()
//         var parentHeight = this.options.container == 'body' ? window.innerHeight : $parent.outerHeight()
//         var parentLeft   = this.options.container == 'body' ? 0 : $parent.offset().left

//         placement = placement == 'bottom' && pos.top   + pos.height  + actualHeight - docScroll > parentHeight  ? 'top'    :
//                     placement == 'top'    && pos.top   - docScroll   - actualHeight < 0                         ? 'bottom' :
//                     placement == 'right'  && pos.right + actualWidth > parentWidth                              ? 'left'   :
//                     placement == 'left'   && pos.left  - actualWidth < parentLeft                               ? 'right'  :
//                     placement

//         $tip
//           .removeClass(orgPlacement)
//           .addClass(placement)
//       }

//       var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

//       this.applyPlacement(calculatedOffset, placement)
//       this.$element.trigger('shown.bs.' + this.type)
//     }
//   }

//   Tooltip.prototype.applyPlacement = function(offset, placement) {
//     var replace
//     var $tip   = this.tip()
//     var width  = $tip[0].offsetWidth
//     var height = $tip[0].offsetHeight

//     // manually read margins because getBoundingClientRect includes difference
//     var marginTop = parseInt($tip.css('margin-top'), 10)
//     var marginLeft = parseInt($tip.css('margin-left'), 10)

//     // we must check for NaN for ie 8/9
//     if (isNaN(marginTop))  marginTop  = 0
//     if (isNaN(marginLeft)) marginLeft = 0

//     offset.top  = offset.top  + marginTop
//     offset.left = offset.left + marginLeft

//     $tip
//       .offset(offset)
//       .addClass('in')

//     // check to see if placing tip in new offset caused the tip to resize itself
//     var actualWidth  = $tip[0].offsetWidth
//     var actualHeight = $tip[0].offsetHeight

//     if (placement == 'top' && actualHeight != height) {
//       replace = true
//       offset.top = offset.top + height - actualHeight
//     }

//     if (/bottom|top/.test(placement)) {
//       var delta = 0

//       if (offset.left < 0) {
//         delta       = offset.left * -2
//         offset.left = 0

//         $tip.offset(offset)

//         actualWidth  = $tip[0].offsetWidth
//         actualHeight = $tip[0].offsetHeight
//       }

//       this.replaceArrow(delta - width + actualWidth, actualWidth, 'left')
//     } else {
//       this.replaceArrow(actualHeight - height, actualHeight, 'top')
//     }

//     if (replace) $tip.offset(offset)
//   }

//   Tooltip.prototype.replaceArrow = function(delta, dimension, position) {
//     this.arrow().css(position, delta ? (50 * (1 - delta / dimension) + "%") : '')
//   }

//   Tooltip.prototype.setContent = function () {
//     var $tip  = this.tip()
//     var title = this.getTitle()

//     $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
//     $tip.removeClass('fade in top bottom left right')
//   }

//   Tooltip.prototype.hide = function () {
//     var that = this
//     var $tip = this.tip()
//     var e    = $.Event('hide.bs.' + this.type)

//     function complete() {
//       if (that.hoverState != 'in') $tip.detach()
//     }

//     this.$element.trigger(e)

//     if (e.isDefaultPrevented()) return

//     $tip.removeClass('in')

//     $.support.transition && this.$tip.hasClass('fade') ?
//       $tip
//         .one($.support.transition.end, complete)
//         .emulateTransitionEnd(150) :
//       complete()

//     this.$element.trigger('hidden.bs.' + this.type)

//     return this
//   }

//   Tooltip.prototype.fixTitle = function () {
//     var $e = this.$element
//     if ($e.attr('title') || typeof($e.attr('data-original-title')) != 'string') {
//       $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
//     }
//   }

//   Tooltip.prototype.hasContent = function () {
//     return this.getTitle()
//   }

//   Tooltip.prototype.getPosition = function () {
//     var el = this.$element[0]
//     return $.extend({}, (typeof el.getBoundingClientRect == 'function') ? el.getBoundingClientRect() : {
//       width: el.offsetWidth
//     , height: el.offsetHeight
//     }, this.$element.offset())
//   }

//   Tooltip.prototype.getCalculatedOffset = function (placement, pos, actualWidth, actualHeight) {
//     return placement == 'bottom' ? { top: pos.top + pos.height,   left: pos.left + pos.width / 2 - actualWidth / 2  } :
//            placement == 'top'    ? { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2  } :
//            placement == 'left'   ? { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth } :
//         /* placement == 'right' */ { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width   }
//   }

//   Tooltip.prototype.getTitle = function () {
//     var title
//     var $e = this.$element
//     var o  = this.options

//     title = $e.attr('data-original-title')
//       || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

//     return title
//   }

//   Tooltip.prototype.tip = function () {
//     return this.$tip = this.$tip || $(this.options.template)
//   }

//   Tooltip.prototype.arrow = function () {
//     return this.$arrow = this.$arrow || this.tip().find('.tooltip-arrow')
//   }

//   Tooltip.prototype.validate = function () {
//     if (!this.$element[0].parentNode) {
//       this.hide()
//       this.$element = null
//       this.options  = null
//     }
//   }

//   Tooltip.prototype.enable = function () {
//     this.enabled = true
//   }

//   Tooltip.prototype.disable = function () {
//     this.enabled = false
//   }

//   Tooltip.prototype.toggleEnabled = function () {
//     this.enabled = !this.enabled
//   }

//   Tooltip.prototype.toggle = function (e) {
//     var self = e ? $(e.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type) : this
//     self.tip().hasClass('in') ? self.leave(self) : self.enter(self)
//   }

//   Tooltip.prototype.destroy = function () {
//     this.hide().$element.off('.' + this.type).removeData('bs.' + this.type)
//   }


//   // TOOLTIP PLUGIN DEFINITION
//   // =========================

//   var old = $.fn.tooltip

//   $.fn.tooltip = function (option) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.tooltip')
//       var options = typeof option == 'object' && option

//       if (!data) $this.data('bs.tooltip', (data = new Tooltip(this, options)))
//       if (typeof option == 'string') data[option]()
//     })
//   }

//   $.fn.tooltip.Constructor = Tooltip


//   // TOOLTIP NO CONFLICT
//   // ===================

//   $.fn.tooltip.noConflict = function () {
//     $.fn.tooltip = old
//     return this
//   }

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: popover.js v3.0.2
//  * http://getbootstrap.com/javascript/#popovers
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // POPOVER PUBLIC CLASS DEFINITION
//   // ===============================

//   var Popover = function (element, options) {
//     this.init('popover', element, options)
//   }

//   if (!$.fn.tooltip) throw new Error('Popover requires tooltip.js')

//   Popover.DEFAULTS = $.extend({} , $.fn.tooltip.Constructor.DEFAULTS, {
//     placement: 'right'
//   , trigger: 'click'
//   , content: ''
//   , template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
//   })


//   // NOTE: POPOVER EXTENDS tooltip.js
//   // ================================

//   Popover.prototype = $.extend({}, $.fn.tooltip.Constructor.prototype)

//   Popover.prototype.constructor = Popover

//   Popover.prototype.getDefaults = function () {
//     return Popover.DEFAULTS
//   }

//   Popover.prototype.setContent = function () {
//     var $tip    = this.tip()
//     var title   = this.getTitle()
//     var content = this.getContent()

//     $tip.find('.popover-title')[this.options.html ? 'html' : 'text'](title)
//     $tip.find('.popover-content')[this.options.html ? 'html' : 'text'](content)

//     $tip.removeClass('fade top bottom left right in')

//     // IE8 doesn't accept hiding via the `:empty` pseudo selector, we have to do
//     // this manually by checking the contents.
//     if (!$tip.find('.popover-title').html()) $tip.find('.popover-title').hide()
//   }

//   Popover.prototype.hasContent = function () {
//     return this.getTitle() || this.getContent()
//   }

//   Popover.prototype.getContent = function () {
//     var $e = this.$element
//     var o  = this.options

//     return $e.attr('data-content')
//       || (typeof o.content == 'function' ?
//             o.content.call($e[0]) :
//             o.content)
//   }

//   Popover.prototype.arrow = function () {
//     return this.$arrow = this.$arrow || this.tip().find('.arrow')
//   }

//   Popover.prototype.tip = function () {
//     if (!this.$tip) this.$tip = $(this.options.template)
//     return this.$tip
//   }


//   // POPOVER PLUGIN DEFINITION
//   // =========================

//   var old = $.fn.popover

//   $.fn.popover = function (option) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.popover')
//       var options = typeof option == 'object' && option

//       if (!data) $this.data('bs.popover', (data = new Popover(this, options)))
//       if (typeof option == 'string') data[option]()
//     })
//   }

//   $.fn.popover.Constructor = Popover


//   // POPOVER NO CONFLICT
//   // ===================

//   $.fn.popover.noConflict = function () {
//     $.fn.popover = old
//     return this
//   }

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: scrollspy.js v3.0.2
//  * http://getbootstrap.com/javascript/#scrollspy
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // SCROLLSPY CLASS DEFINITION
//   // ==========================

//   function ScrollSpy(element, options) {
//     var href
//     var process  = $.proxy(this.process, this)

//     this.$element       = $(element).is('body') ? $(window) : $(element)
//     this.$body          = $('body')
//     this.$scrollElement = this.$element.on('scroll.bs.scroll-spy.data-api', process)
//     this.options        = $.extend({}, ScrollSpy.DEFAULTS, options)
//     this.selector       = (this.options.target
//       || ((href = $(element).attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
//       || '') + ' .nav li > a'
//     this.offsets        = $([])
//     this.targets        = $([])
//     this.activeTarget   = null

//     this.refresh()
//     this.process()
//   }

//   ScrollSpy.DEFAULTS = {
//     offset: 10
//   }

//   ScrollSpy.prototype.refresh = function () {
//     var offsetMethod = this.$element[0] == window ? 'offset' : 'position'

//     this.offsets = $([])
//     this.targets = $([])

//     var self     = this
//     var $targets = this.$body
//       .find(this.selector)
//       .map(function () {
//         var $el   = $(this)
//         var href  = $el.data('target') || $el.attr('href')
//         var $href = /^#\w/.test(href) && $(href)

//         return ($href
//           && $href.length
//           && [[ $href[offsetMethod]().top + (!$.isWindow(self.$scrollElement.get(0)) && self.$scrollElement.scrollTop()), href ]]) || null
//       })
//       .sort(function (a, b) { return a[0] - b[0] })
//       .each(function () {
//         self.offsets.push(this[0])
//         self.targets.push(this[1])
//       })
//   }

//   ScrollSpy.prototype.process = function () {
//     var scrollTop    = this.$scrollElement.scrollTop() + this.options.offset
//     var scrollHeight = this.$scrollElement[0].scrollHeight || this.$body[0].scrollHeight
//     var maxScroll    = scrollHeight - this.$scrollElement.height()
//     var offsets      = this.offsets
//     var targets      = this.targets
//     var activeTarget = this.activeTarget
//     var i

//     if (scrollTop >= maxScroll) {
//       return activeTarget != (i = targets.last()[0]) && this.activate(i)
//     }

//     for (i = offsets.length; i--;) {
//       activeTarget != targets[i]
//         && scrollTop >= offsets[i]
//         && (!offsets[i + 1] || scrollTop <= offsets[i + 1])
//         && this.activate( targets[i] )
//     }
//   }

//   ScrollSpy.prototype.activate = function (target) {
//     this.activeTarget = target

//     $(this.selector)
//       .parents('.active')
//       .removeClass('active')

//     var selector = this.selector
//       + '[data-target="' + target + '"],'
//       + this.selector + '[href="' + target + '"]'

//     var active = $(selector)
//       .parents('li')
//       .addClass('active')

//     if (active.parent('.dropdown-menu').length)  {
//       active = active
//         .closest('li.dropdown')
//         .addClass('active')
//     }

//     active.trigger('activate')
//   }


//   // SCROLLSPY PLUGIN DEFINITION
//   // ===========================

//   var old = $.fn.scrollspy

//   $.fn.scrollspy = function (option) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.scrollspy')
//       var options = typeof option == 'object' && option

//       if (!data) $this.data('bs.scrollspy', (data = new ScrollSpy(this, options)))
//       if (typeof option == 'string') data[option]()
//     })
//   }

//   $.fn.scrollspy.Constructor = ScrollSpy


//   // SCROLLSPY NO CONFLICT
//   // =====================

//   $.fn.scrollspy.noConflict = function () {
//     $.fn.scrollspy = old
//     return this
//   }


//   // SCROLLSPY DATA-API
//   // ==================

//   $(window).on('load', function () {
//     $('[data-spy="scroll"]').each(function () {
//       var $spy = $(this)
//       $spy.scrollspy($spy.data())
//     })
//   })

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: tab.js v3.0.2
//  * http://getbootstrap.com/javascript/#tabs
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // TAB CLASS DEFINITION
//   // ====================

//   var Tab = function (element) {
//     this.element = $(element)
//   }

//   Tab.prototype.show = function () {
//     var $this    = this.element
//     var $ul      = $this.closest('ul:not(.dropdown-menu)')
//     var selector = $this.data('target')

//     if (!selector) {
//       selector = $this.attr('href')
//       selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
//     }

//     if ($this.parent('li').hasClass('active')) return

//     var previous = $ul.find('.active:last a')[0]
//     var e        = $.Event('show.bs.tab', {
//       relatedTarget: previous
//     })

//     $this.trigger(e)

//     if (e.isDefaultPrevented()) return

//     var $target = $(selector)

//     this.activate($this.parent('li'), $ul)
//     this.activate($target, $target.parent(), function () {
//       $this.trigger({
//         type: 'shown.bs.tab'
//       , relatedTarget: previous
//       })
//     })
//   }

//   Tab.prototype.activate = function (element, container, callback) {
//     var $active    = container.find('> .active')
//     var transition = callback
//       && $.support.transition
//       && $active.hasClass('fade')

//     function next() {
//       $active
//         .removeClass('active')
//         .find('> .dropdown-menu > .active')
//         .removeClass('active')

//       element.addClass('active')

//       if (transition) {
//         element[0].offsetWidth // reflow for transition
//         element.addClass('in')
//       } else {
//         element.removeClass('fade')
//       }

//       if (element.parent('.dropdown-menu')) {
//         element.closest('li.dropdown').addClass('active')
//       }

//       callback && callback()
//     }

//     transition ?
//       $active
//         .one($.support.transition.end, next)
//         .emulateTransitionEnd(150) :
//       next()

//     $active.removeClass('in')
//   }


//   // TAB PLUGIN DEFINITION
//   // =====================

//   var old = $.fn.tab

//   $.fn.tab = function ( option ) {
//     return this.each(function () {
//       var $this = $(this)
//       var data  = $this.data('bs.tab')

//       if (!data) $this.data('bs.tab', (data = new Tab(this)))
//       if (typeof option == 'string') data[option]()
//     })
//   }

//   $.fn.tab.Constructor = Tab


//   // TAB NO CONFLICT
//   // ===============

//   $.fn.tab.noConflict = function () {
//     $.fn.tab = old
//     return this
//   }


//   // TAB DATA-API
//   // ============

//   $(document).on('click.bs.tab.data-api', '[data-toggle="tab"], [data-toggle="pill"]', function (e) {
//     e.preventDefault()
//     $(this).tab('show')
//   })

// }(jQuery);

// /* ========================================================================
//  * Bootstrap: affix.js v3.0.2
//  * http://getbootstrap.com/javascript/#affix
//  * ========================================================================
//  * Copyright 2013 Twitter, Inc.
//  *
//  * Licensed under the Apache License, Version 2.0 (the "License");
//  * you may not use this file except in compliance with the License.
//  * You may obtain a copy of the License at
//  *
//  * http://www.apache.org/licenses/LICENSE-2.0
//  *
//  * Unless required by applicable law or agreed to in writing, software
//  * distributed under the License is distributed on an "AS IS" BASIS,
//  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  * See the License for the specific language governing permissions and
//  * limitations under the License.
//  * ======================================================================== */


// +function ($) { "use strict";

//   // AFFIX CLASS DEFINITION
//   // ======================

//   var Affix = function (element, options) {
//     this.options = $.extend({}, Affix.DEFAULTS, options)
//     this.$window = $(window)
//       .on('scroll.bs.affix.data-api', $.proxy(this.checkPosition, this))
//       .on('click.bs.affix.data-api',  $.proxy(this.checkPositionWithEventLoop, this))

//     this.$element = $(element)
//     this.affixed  =
//     this.unpin    = null

//     this.checkPosition()
//   }

//   Affix.RESET = 'affix affix-top affix-bottom'

//   Affix.DEFAULTS = {
//     offset: 0
//   }

//   Affix.prototype.checkPositionWithEventLoop = function () {
//     setTimeout($.proxy(this.checkPosition, this), 1)
//   }

//   Affix.prototype.checkPosition = function () {
//     if (!this.$element.is(':visible')) return

//     var scrollHeight = $(document).height()
//     var scrollTop    = this.$window.scrollTop()
//     var position     = this.$element.offset()
//     var offset       = this.options.offset
//     var offsetTop    = offset.top
//     var offsetBottom = offset.bottom

//     if (typeof offset != 'object')         offsetBottom = offsetTop = offset
//     if (typeof offsetTop == 'function')    offsetTop    = offset.top()
//     if (typeof offsetBottom == 'function') offsetBottom = offset.bottom()

//     var affix = this.unpin   != null && (scrollTop + this.unpin <= position.top) ? false :
//                 offsetBottom != null && (position.top + this.$element.height() >= scrollHeight - offsetBottom) ? 'bottom' :
//                 offsetTop    != null && (scrollTop <= offsetTop) ? 'top' : false

//     if (this.affixed === affix) return
//     if (this.unpin) this.$element.css('top', '')

//     this.affixed = affix
//     this.unpin   = affix == 'bottom' ? position.top - scrollTop : null

//     this.$element.removeClass(Affix.RESET).addClass('affix' + (affix ? '-' + affix : ''))

//     if (affix == 'bottom') {
//       this.$element.offset({ top: document.body.offsetHeight - offsetBottom - this.$element.height() })
//     }
//   }


//   // AFFIX PLUGIN DEFINITION
//   // =======================

//   var old = $.fn.affix

//   $.fn.affix = function (option) {
//     return this.each(function () {
//       var $this   = $(this)
//       var data    = $this.data('bs.affix')
//       var options = typeof option == 'object' && option

//       if (!data) $this.data('bs.affix', (data = new Affix(this, options)))
//       if (typeof option == 'string') data[option]()
//     })
//   }

//   $.fn.affix.Constructor = Affix


//   // AFFIX NO CONFLICT
//   // =================

//   $.fn.affix.noConflict = function () {
//     $.fn.affix = old
//     return this
//   }


//   // AFFIX DATA-API
//   // ==============

//   $(window).on('load', function () {
//     $('[data-spy="affix"]').each(function () {
//       var $spy = $(this)
//       var data = $spy.data()

//       data.offset = data.offset || {}

//       if (data.offsetBottom) data.offset.bottom = data.offsetBottom
//       if (data.offsetTop)    data.offset.top    = data.offsetTop

//       $spy.affix(data)
//     })
//   })

// }(jQuery);
