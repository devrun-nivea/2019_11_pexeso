(function ($) {

    // @todo old version, delete
    var Application = {
        config: {
            'cardsPreserve3DAnimation': 'animated flipInX',
            'cardsNoPreserve3DAnimation': 'animated zoomInDown',

            'textInAnimation': 'animated flipInY',
            'textOutAnimation': 'animated flipOutY',
            'wellAnimation': 'animated bounce',
            'resultAnimation': 'animated bounceIn',

            'swapTextAnimation': 'animated zoomInRight',
            'howBoxAnimation': 'animated zoomInLeft',

            'liveFormWait': false, // 500
            'gtmCardSendRandomly': false,
            'debug': false
        },

        init: function (config) {
            $.extend(this.config, config);

            this.initAlertFade();
            this.initSelectDecorate();
            this.initSmoothScroll();

            this.browserDetect.init();
            $(this.selectorApp).addClass(this.browserDetect.browser + " " + this.browserDetect.browser + "-" + this.browserDetect.version);

            LiveForm.setOptions({
                messageErrorPrefix: '',
                wait: this.config['liveFormWait']
            });

            if (this.config['debug']) console.log("jQuery version " + jQuery.fn.jquery);

            $.nette.init();
        },

        initAlertFade: function () {
            $(".alert").fadeTo(6000, 500).slideUp(500, function () {
                $(this).slideUp(500);
                $(this).alert('close');
            });
        },

        initSelectDecorate: function () {
            var self = this;

            $(this.selectorApp).find('select').select2({
                placeholder: function () {
                    return "";
                }
                //allowClear: true
            });

            $.nette.ext('select', {
                success: function (payload) {
                    $(self.selectorApp).find('select').select2({
                        placeholder: function () {
                            return "";
                        }
                        //allowClear: true
                    });
                }
            });
        },

        initSmoothScroll: function () {
            // $('article a.cta').smoothScroll();

            $(document).on('click', 'article a.cta', function (e) {
                e.preventDefault();
                $.smoothScroll({ scrollTarget: '#soutez' });
            });
        },

        show: function () {
            $(this.selectorApp).addClass('in');
        },

        showAnimationsAfterLoad: function () {
            $(this.selectorSwapText).addClass(this.config['swapTextAnimation']);
            $(this.selectorHowBox).addClass(this.config['howBoxAnimation']);
        },

        cardsAnimation: function () {
            var cards = $(this.selectorCards);
            var animationType = Modernizr.preserve3d ? this.config['cardsPreserve3DAnimation'] : this.config['cardsNoPreserve3DAnimation'];

            $(cards).addClass(animationType).one(this.selectorAnimationEnd, function() {
                $(this).removeClass(animationType).removeClass('animation-index-delay');
            });
        },

        cardsClick: function (element) {

            var wellClass = this.config['wellAnimation'];
            var h2InClass = this.config['textInAnimation'];
            var h2OutClass = this.config['textOutAnimation'];
            var resultClass = this.config['resultAnimation'];
            var selectorAnimationEnd = this.selectorAnimationEnd;

            var pairNames = {
                'a': 'first pair',
                'b': 'second pair',
                'c': 'third pair',
                'd': 'fourth pair',
                'e': 'fifth pair',
                'f': 'sixth pair'
            };

            var pairIndexNames = {
                0: 'first pair',
                1: 'second pair',
                2: 'third pair',
                3: 'fourth pair',
                4: 'fifth pair',
                5: 'sixth pair'
            };

            var name = $(element).data('name');
            var selects = $('.card-container.select');
            var selected = $('.card-container.selected');
            var self = this;
            var indexSelected = selected.length / 2;

            if(selects.length > 0) {
                var equal = false;

                // console.log(selected.length);
                // console.log(indexSelected);
                // console.log(selects);

                $.each(selects, function () {
                    var selectName = $(this).data('name');
                    var $this = this;

                    if (name == selectName) {

                        $(this).removeClass('select').addClass('selected').addClass(wellClass).one(selectorAnimationEnd, function() {
                            $(this).removeClass(wellClass);
                        });
                        equal = true;

                        if (self.config['gtmCardSendRandomly']) {
                            dataLayer.push({ 'event': 'ga_event', 'eventLabel': 'pexeso', 'eventCategory': 'pexeso pair', 'eventAction': pairNames[name] });
                            if(self.config['debug']) console.log({ 'event': 'ga_event', 'eventLabel': 'pexeso', 'eventCategory': 'pexeso pair', 'eventAction': pairNames[name] });

                        } else {
                            dataLayer.push({ 'event': 'ga_event', 'eventLabel': 'pexeso', 'eventCategory': 'pexeso pair', 'eventAction': pairIndexNames[indexSelected] });
                            if(self.config['debug']) console.log({ 'event': 'ga_event', 'eventLabel': 'pexeso', 'eventCategory': 'pexeso pair', 'eventAction': pairIndexNames[indexSelected] });
                        }

                    } else {

                        setTimeout(function(){

                            $($this).removeClass('select').data('select', 0);
                            $(element).removeClass('select').data('select', 0);
                            $('#cards').addClass('allow-click');

                            self.allowSelect = true;

                        }, 1000);

                        $(element).addClass('select').data('select', 2);

                        $('#cards').removeClass('allow-click');
                        self.allowSelect = false;

                    }
                });



                if (equal) {
                    var selectedTotalCount = 12;

                    $(element).addClass('selected');
                    selected = $('.card-container.selected');

                    $('.part-footer .benefit').removeClass('animated flipInX');
                    $('.part-footer .benefit-' + name).addClass('animated flipInX').one(selectorAnimationEnd, function() {
                        // $(element).removeClass('animated bounceIn');
                    });

                    if (selected.length == 2) {
                        $('p.index-0').addClass(h2OutClass).one(selectorAnimationEnd, function() {
                            $(this).removeClass(h2OutClass).addClass('hidden');

                        });
                        $('.how-help').addClass('animated fadeOut').one(selectorAnimationEnd, function() {
                            $(this).removeClass('animated fadeOut').addClass('hidden');
                        });
                        $('h2.index-0').addClass(h2OutClass).one(selectorAnimationEnd, function() {
                            $(this).removeClass(h2OutClass).addClass('hidden');

                            $('h2.index-1').removeClass('hidden').addClass(h2InClass).one(selectorAnimationEnd, function() {
                                $(this).removeClass(h2InClass);

                            });
                            $('.part-footer .result').removeClass('hidden').addClass(resultClass).one(selectorAnimationEnd, function() {
                                $(this).removeClass(resultClass);

                            });


                        });


                    } else if (selected.length == selectedTotalCount) {
                        $('h2.index-1').addClass('animated rotateOut').one(selectorAnimationEnd, function() {
                            $(this).removeClass('animated rotateOut').addClass('hidden');

                            $('h2.index-2').removeClass('hidden').addClass('animated rotateIn').one(selectorAnimationEnd, function() {
                                $(this).removeClass('animated rotateIn').addClass('animated rubberBand');

                                // $('#play-frame').addClass('blur');

                            });

                        });
                    }

                    $(element).addClass(wellClass).one(selectorAnimationEnd, function(e) {
                        $(this).removeClass(wellClass);

                        var selected = $('.card-container.selected');
                        if (selected.length == 2) {
                            $('#play-frame').addClass('index-1');

                        } else if (selected.length == selectedTotalCount) {

                            setTimeout(function(){
                                $('#play-frame').addClass('blur');
                                self.ajaxFormPage(e);

                            }, 2300);


                            // event to next page
                            // $(selected).one(selectorAnimationEnd, function() {
                            // $(element).removeClass(outClass);


                            // });


                            // $('#play-frame').addClass('blur');
                        }
                    });
                }

            } else {
                $(element).addClass('select');
            }

        },

        ajaxFormPage: function (e) {
            var selector = $(this.selectorApp);
            var selectorAnimationEnd = this.selectorAnimationEnd;

            var proxy = selector.data('pf-proxy-url');
            var proxyBaseUrl = selector.data('pf-proxy-base-url');

            var historyUrl = $('#play-frame').data('href');
            var url = proxy ? proxy + "-" + $('#play-frame').data('ajax-url') : $('#play-frame').data('ajax-url');




            /*
             console.log("historyUrl ",historyUrl);
             console.log("proxy ", proxy);
             console.log("url ",url);
             console.log("proxyBase ", proxyBaseUrl);
             console.log("----------------");
             */


            setTimeout(function(){

                $.nette.ajax({
                    url: url,
                    href: historyUrl,
                    type: "get",
                    data: {
                        proxyUrl: proxy,
                        proxyBaseUrl: proxyBaseUrl
                    },
                    start: function() {
                        // $.nette.ext('fade').autoComplete = false;
                    },
                    success: function(payload) {
                        // reset selected, browser bug!
                        // $('.card-container').removeClass('selected');

                        var html = payload.html ? payload.html : payload;
                        var images = $(html).find('.img-preload img');
                        var loaded = 0;

                        $(images).each(function(){
                            // var newImg = $('<img/>').attr('src', this.src);
                            // var newImg = (new Image()).src = this.src;
                            var newImg = new Image();

                            newImg.onload = function() {
                                loaded++;
                            };

                            newImg.src = this.src;
                        });


                        $(selector).removeClass('in');


                        $('.homepage').addClass('animated bounceOutLeft').one(selectorAnimationEnd, function() {
                            $(this).removeClass('animated bounceOutLeft');
                        });


                        setTimeout(function(){

                            $('#play-frame').removeClass('blur');
                            $('#snippet--content').html(html);



                            for (var i = 0; i < document.forms.length; i++) {
                                Nette.initForm(document.forms[i]);
                            }

                            $(".main-wrapper .form-horizontal").find('select').select2({
                                placeholder: function() { return ""; }
                                //allowClear: true
                            });


                            var waiting = function (a) {
                                if (loaded < images.length) {
                                    setTimeout(waiting, 10, "works");
                                } else {
                                    $(selector).removeClass().addClass(payload.class).addClass('in');
                                    $.smoothScroll({ scrollTarget: '#soutez' });
                                }
                            };


                            if (loaded < images.length) {
                                setTimeout(waiting, 10, "works");
                            } else {
                                $(selector).removeClass().addClass(payload.class).addClass('in');
                                $.smoothScroll({ scrollTarget: '#soutez' });
                            }


                        }, 850);


                    }
                }, this , e);

            }, 1500);


            /*
             $(selector).removeClass('_in').addClass('_animated _fadeOut').one(selectorAnimationEnd, function() {


             });
             */
        },

        /** not use yet */
        preloadImages: function () {
            var images = $('.img-preload img');

            $(images).each(function(){
                var newImg = $('<img/>').attr('src', this.src);
                $('.profile').append( $(newImg) );

                // Alternatively you could use:
                // (new Image()).src = this.src;
            });
        },

        browserDetect: {
            init: function () {
                this.browser = this.searchString(this.dataBrowser) || "Other";
                this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "Unknown";
            },
            searchString: function (data) {
                for (var i = 0; i < data.length; i++) {
                    var dataString = data[i].string;
                    this.versionSearchString = data[i].subString;

                    if (dataString.indexOf(data[i].subString) !== -1) {
                        return data[i].identity;
                    }
                }
            },
            searchVersion: function (dataString) {
                var index = dataString.indexOf(this.versionSearchString);
                if (index === -1) {
                    return;
                }

                var rv = dataString.indexOf("rv:");
                if (this.versionSearchString === "Trident" && rv !== -1) {
                    return parseFloat(dataString.substring(rv + 3));
                } else {
                    return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
                }
            },

            dataBrowser: [
                {string: navigator.userAgent, subString: "Edge", identity: "MS-Edge"},
                {string: navigator.userAgent, subString: "MSIE", identity: "Explorer"},
                {string: navigator.userAgent, subString: "Trident", identity: "Explorer"},
                {string: navigator.userAgent, subString: "Firefox", identity: "Firefox"},
                {string: navigator.userAgent, subString: "Opera", identity: "Opera"},
                {string: navigator.userAgent, subString: "OPR", identity: "Opera"},

                {string: navigator.userAgent, subString: "Chrome", identity: "Chrome"},
                {string: navigator.userAgent, subString: "Safari", identity: "Safari"}
            ]

        },

        selectorAnimationEnd: 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
        selectorApp: '.main-wrapper',
        selectorCards: '.card-container',
        selectorSwapText: '#swap-text',
        selectorHowBox: '#top-help .how-box',
        allowSelect: true
    };


    Application.init();


    /**
     * application load
     */
    $(window).on('load', function () {
        Application.show();
        Application.cardsAnimation();
        Application.showAnimationsAfterLoad();
    });


    if (Modernizr.touchevents) {
        $(Application.selectorCards).click(function(e){
            var counter = ($(this).data('select') || 0) + 1;

            if (Application.allowSelect && counter == 1) {
                $(this).data('select', counter);
                Application.cardsClick(this);
            }
        });

    } else {
        $(document).on('click', '.allow-click .card-container:not(.select):not(.selected)', function (e) {
            Application.cardsClick(this);
        });
    }

})(jQuery);