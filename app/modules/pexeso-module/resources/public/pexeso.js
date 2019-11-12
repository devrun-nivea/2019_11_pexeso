(function (window, $, undefined) {

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
            'debug': false,
            selectedTotalCount: 6,
            pairIndexNames: {},
            pairNames: {},
            gtmBenefitNames: {},
            gtmBenefitDescriptions: {}

        },

        init: function (config) {
            this.cmsPage = $('#CmsPage').length > 0;

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

            if (!window.Nette) {
                $.nette.init();
            }

        },

        onApplicationEvent: function() {
            var event;

            if ( typeof window.CustomEvent !== "function" ) {
                // IE 11
                event = document.createEvent("CustomEvent");
                event.initCustomEvent('onApplication', false, false, {
                    application: Application
                });

            } else {
                // modern browser
                // Create the event
                event = new CustomEvent("onApplication", {"detail": {application: Application},});
            }

            // Trigger onApplication event
            document.dispatchEvent(event);
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
            this.removeAnimationCards();
        },

        showAnimationsAfterLoad: function () {
            $(this.selectorSwapText).addClass(this.config['swapTextAnimation']);
            $(this.selectorHowBox).addClass(this.config['howBoxAnimation']);
        },

        animationAfterPageLoad: function () {
            var animation = 'slit-in-horizontal animated';

            $("#soutez section").addClass(animation).one(this.selectorAnimationEnd, function() {
                $(this).removeClass(animation);
            });

            this.removeAnimationCards();
        },

        removeAnimationCards: function() {
            $('.slide-in-blurred-tl').one(this.selectorAnimationEnd, function() {
                $(this).removeClass('slide-in-blurred-tl').removeClass('animation-index-delay');
                $(this).css('animation-delay', '');
            });
        },

        invalidCacheFormPage: function () {
            var formUrl = $('#soutez').data('form-page');
            Page.invalidPage(formUrl);
        },



        cardsAnimation: function () {
            var cards = $(this.selectorCards);
            var animationType = Modernizr.preserve3d ? this.config['cardsPreserve3DAnimation'] : this.config['cardsNoPreserve3DAnimation'];

            $(cards).addClass(animationType).one(this.selectorAnimationEnd, function() {
                $(this).removeClass(animationType).removeClass('animation-index-delay');
            });
        },

        sendGTMOpenCard: function ($packName, $id, $name, $benefitName, $benefitDescription, $pairName) {
            /*
            dataLayer.push({
                'event': 'otevreniKarty',
                'eventName': 'Otevreni karty',
                'IdKarty': $id, //'a1',
                'IdTypKarty': $name, // a
                'Balicek': $packName,
                'NazevKarty': $benefitName,
                'PopisKarty': $benefitDescription
            });
            */
        },

        sendGTMMatchCards: function ($packName, $name, $benefitName, $benefitDescription, $pairName) {
            dataLayer.push({
                'eventType': 'event',
                'eventName': 'pexeso',
                'eventAction': $pairName,
                'eventCategory': 'pexeso_pair',
                'eventDescription': $benefitName,
                'pageView': 'event',
                'pageName': '/',
                'id': $name, // a
            });

            /*
            dataLayer.push({
                'event': 'Shoda',
                'eventName': 'Shoda karet',
                'IdTypKarty': $name, // a
                'Balicek': $packName,
                'NazevKarty': $benefitName,
                'PopisKarty': $benefitDescription
            });
            */
        },

        sendGTMWinCards: function ($packName) {
            /*
            dataLayer.push({
                'event': 'PexesoDohrano',
                'eventName': 'Pexeso Dohrano',
                'Balicek': $packName
            });
            */
        },

        cardsClick: function (element) {

            var wellClass = this.config['wellAnimation'];
            var h2InClass = this.config['textInAnimation'];
            var h2OutClass = this.config['textOutAnimation'];
            var resultClass = this.config['resultAnimation'];
            var pairIndexNames = this.config['pairIndexNames'];
            var gtmBenefitNames = this.config['gtmBenefitNames'];
            var gtmBenefitDescriptions = this.config['gtmBenefitDescriptions'];
            var pairNames = this.config['pairNames'];
            var selectorAnimationEnd = this.selectorAnimationEnd;


            var id = $(element).data('id');
            var name = $(element).data('name');
            var selects = $('.card-container.select');
            var selected = $('.card-container.selected');
            var packageName = $('[data-package]').data('package');
            var self = this;
            var indexSelected = selected.length / 2;
            self.listPosition++;

            // console.log(pairNames);
            // console.log(pairIndexNames);
            // console.log(packageName);
            // console.log(name);
            // console.log(selects);
            // console.log(selected);
            // console.log(element);

            this.sendGTMOpenCard(packageName, id, name, gtmBenefitNames[name], gtmBenefitDescriptions[name], pairNames[name] );

            if(selects.length > 0) {
                var equal = false;

                // console.log(selected.length);
                // console.log(indexSelected);
                // console.log(selects);

                $.each(selects, function () {
                    var selectName = $(this).data('name');
                    var $this = this;

                    // self.sendGTM(selectName, name, packageName );

                    if (name === selectName) {
                        self.sendGTMMatchCards(packageName, name, gtmBenefitNames[name], gtmBenefitDescriptions[name], pairNames[name] );

                        $(this).removeClass('select').addClass('selected').addClass(wellClass).one(selectorAnimationEnd, function() {
                            $(this).removeClass(wellClass);
                        });
                        equal = true;

                    } else {

                        setTimeout(function(){

                            $($this).removeClass('select').data('select', 0).off('blur');
                            $(element).removeClass('select').data('select', 0).off('blur');
                            $('#cards').addClass('allow-click');

                            self.allowSelect = true;

                        }, 1000);

                        $(element).addClass('select').data('select', 2);

                        $('#cards').removeClass('allow-click');
                        self.allowSelect = false;

                    }
                });



                if (equal) {
                    var selectedTotalCount = this.config['selectedTotalCount'];
                    // selectedTotalCount = 6; // @todo debug

                    $(element).addClass('selected');
                    selected = $('.card-container.selected');

                    $('.part-footer .benefit').removeClass('animated flipInX');
                    $('.part-footer .benefit-' + name).addClass('animated flipInX').one(selectorAnimationEnd, function() {
                        // $(element).removeClass('animated bounceIn');
                    });

                    if (selected.length === 2) {
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


                    } else if (selected.length === selectedTotalCount) {
                        self.sendGTMWinCards(packageName);

                        $('h2.index-1').addClass('animated rotateOut').one(selectorAnimationEnd, function() {
                            $(this).removeClass('animated rotateOut').addClass('hidden');

                            $('h2.index-2').removeClass('hidden').addClass('animated rotateIn').one(selectorAnimationEnd, function() {
                                $(this).removeClass('animated rotateIn').addClass('animated rubberBand').one(selectorAnimationEnd, function() {
                                    $(this).removeClass('animated rubberBand').off(self.selectorAnimationEnd);

                                    // $('#play-frame').addClass('blur');

                                });


                                // $('#play-frame').addClass('blur');

                            });
                        });
                    }

                    $(element).addClass(wellClass).one(selectorAnimationEnd, function(e) {
                        $(this).removeClass(wellClass).off(self.selectorAnimationEnd);

                        var selected = $('.card-container.selected');
                        if (selected.length === 2) {
                            $('#play-frame').addClass('index-1');

                        } else if (selected.length === selectedTotalCount) {

                            setTimeout(function(){
                                // $('#play-frame').addClass('blur');
                                self.ajaxFormPage(e);

                            }, 500);


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
            /*
             only not cms page
             */
            if ($('#CmsPage').length > 0) return;

            setTimeout(function(){
                Page.switchPage($('#play-frame'), e, 800);

            }, 1500);
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
        cmsPage: false,
        allowSelect: true,
        listPosition: 0
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


    /**
     * back button
     */
    $(window).on('popstate', function() {
        if (Application.config.debug) console.log('Back button was pressed.');

        Application.invalidCacheFormPage();
        window.location.href = window.location.href;
    });


    // bind callbacks after page load
    Page.callbacksAfterPageLoad.push(function () {
        $.smoothScroll({ scrollTarget: '#soutez' });
        // Application.initCheckedForm();
        // Application.invalidCacheFormPage();
        Application.animationAfterPageLoad();
        Application.onApplicationEvent();

        // Application.cardsAnimation();
        // Application.resize();
    });


    if (Modernizr.touchevents) {
        $(Application.selectorCards).click(function(e){
            var counter = ($(this).data('select') || 0) + 1;

            if (Application.allowSelect && counter === 1) {
                $(this).data('select', counter);
                Application.cardsClick(this);
            }
        });

    } else {
        $(document).on('click', '.allow-click .card-container:not(.select):not(.selected)', function (e) {
            Application.cardsClick(this);
        });
    }

    // Leak Application namespace
    window.Application = Application;
    Application.onApplicationEvent();


})(window, jQuery);