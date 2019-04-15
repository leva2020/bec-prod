// PARALLAX	
// http://designers.hubspot.com/docs/snippets/design/implement-a-parallax-effect																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																												 
!function(n){n.fn.parallax=function(o){var t=n(window).height(),e=n.extend({speed:.15},o);return this.each(function(){var o=n(this);n(document).scroll(function(){var r=n(window).scrollTop(),i=o.offset().top,c=o.outerHeight();if(!(r>=i+c||i>=r+t)){var a=Math.round((i-r)*e.speed);o.css("background-position","center "+a+"px")}})})}}(jQuery);																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																												 


// RESPONSIVE TABS
/* 
* http://openam.github.io/bootstrap-responsive-tabs/
*/
$(function () {
    if (fakewaffle === undefined) {
        var fakewaffle = {};
    }

    fakewaffle.responsiveTabs = function (collapseDisplayed) {
        "use strict";
        fakewaffle.currentPosition = 'tabs';

        var tabGroups = $('.nav-tabs.responsive'),
            hidden = '',
            visible = '';

        if (collapseDisplayed === undefined) {
            collapseDisplayed = ['xs', 'sm'];
        }

        $.each(collapseDisplayed, function () {
            hidden += ' hidden-' + this;
            visible += ' visible-' + this;
        });

        $.each(tabGroups, function () {
            var $tabGroup = $(this),
                tabs = $tabGroup.find('li a'),
                collapseDiv = $("<div></div>", {
                    "class": "panel-group responsive" + visible,
                    "id": 'collapse-' + $tabGroup.attr('id')
                });

            $.each(tabs, function () {
                var $this = $(this),
                    active = '',
                    oldLinkClass = $this.attr('class') === undefined ? '' : $this.attr('class'),
                    newLinkClass = 'accordion-toggle',
                    oldParentClass = $this.parent().attr('class') === undefined ? '' : $this.parent().attr('class'),
                    newParentClass = 'panel panel-default';

                if (oldLinkClass.length > 0) {
                    newLinkClass += ' ' + oldLinkClass;
                };

                if (oldParentClass.length > 0) {
                    oldParentClass = oldParentClass.replace(/\bactive\b/g, '');
                    newParentClass += ' ' + oldParentClass;
                    newParentClass = newParentClass.replace(/\s{2,}/g, ' ');
                    newParentClass = newParentClass.replace(/^\s+|\s+$/g, '');
                };

                if ($this.parent().hasClass('active')) {
                    active = ' in';
                }
                $this.click(function (e) {
                    e.preventDefault()
                    $this.tab('show')
                });

                collapseDiv.append(
                    $('<div>').attr('class', newParentClass).html(
                        $('<div>').attr('class', 'panel-heading').html(
                            $('<h4>').attr('class', 'panel-title').html(
                                $('<a>', {
                                    'class': newLinkClass,
                                    'data-toggle': 'collapse',
                                    'data-parent': '#collapse-' + $tabGroup.attr('id'),
                                    'href': '#collapse-' + $this.attr('href').replace(/#/g, ''),
                                    'html': $this.html()
                                })
                            )
                        )
                    ).append(
                        $('<div>', {
                            'id': 'collapse-' + $this.attr('href').replace(/#/g, ''),
                            'class': 'panel-collapse collapse' + active
                        }).html(
                            $('<div>').attr('class', 'panel-body').html('')
                        )
                    )
                );
            });

            $tabGroup.next().after(collapseDiv);
            $tabGroup.addClass(hidden);
            $('.tab-content.responsive').addClass(hidden);
        });

        fakewaffle.checkResize();
        fakewaffle.bindTabToCollapse();
    };

    fakewaffle.checkResize = function () {
        "use strict";
        if ($(".panel-group.responsive").is(":visible") === true && fakewaffle.currentPosition === 'tabs') {
            fakewaffle.toggleResponsiveTabContent();
            fakewaffle.currentPosition = 'panel';
        } else if ($(".panel-group.responsive").is(":visible") === false && fakewaffle.currentPosition === 'panel') {
            fakewaffle.toggleResponsiveTabContent();
            fakewaffle.currentPosition = 'tabs';
        }

    };

    fakewaffle.toggleResponsiveTabContent = function () {
        "use strict";
        var tabGroups = $('.tabbed .nav.responsive');

        $.each(tabGroups, function () {
            var tabs = $(this).find('li a');

            $.each(tabs, function () {
                var href = $(this).attr('href').replace(/#/g, ''),
                    tabId = "#" + href,
                    panelId = "#collapse-" + href,
                    tabContent = $(tabId).html(),
                    panelContent = $(panelId + " div:first-child").html();

                $(tabId).html(panelContent);
                $(panelId + " div:first-child").html(tabContent);
            });

        });
    };

    fakewaffle.bindTabToCollapse = function () {
        "use strict";
        var tabs = $('.tabbed .nav.responsive').find('li a'),
            collapse = $(".panel-group.responsive").find('.panel-collapse');

        tabs.on('shown.bs.tab', function (e) {
            var $current = $($(e.target)[0].hash.replace(/#/, '#collapse-'));
            $current.collapse('show');

            if (e.relatedTarget) {
                var $previous = $($(e.relatedTarget)[0].hash.replace(/#/, '#collapse-'));
                $previous.collapse('hide');
            }
        });

        collapse.on('show.bs.collapse', function (e) {
            var current = $(e.target).context.id.replace(/collapse-/g, '#');

            $('a[href="' + current + '"]').tab('show');
        });
    }

    $(window).resize(function () {
        "use strict";
        fakewaffle.checkResize();
    });
    (function ($) {
        fakewaffle.responsiveTabs(['xs', 'sm']);
    })(jQuery);
});


// LIGHTBOX 
/*
Lightbox for Bootstrap 3 by @ashleydw
https://github.com/ashleydw/lightbox

License: https://github.com/ashleydw/lightbox/blob/master/LICENSE
*/
(function () {
    "use strict";
    var $, EkkoLightbox;

    $ = jQuery;

    EkkoLightbox = function (element, options) {
        var content, footer, header,
          _this = this;
        this.options = $.extend({
            title: null,
            footer: null,
            remote: null
        }, $.fn.ekkoLightbox.defaults, options || {});
        this.$element = $(element);
        content = '';
        this.modal_id = this.options.modal_id ? this.options.modal_id : 'ekkoLightbox-' + Math.floor((Math.random() * 1000) + 1);
        header = '<div class="modal-header"' + (this.options.title || this.options.always_show_close ? '' : ' style="display:none"') + '><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button><h4 class="modal-title">' + (this.options.title || "&nbsp;") + '</h4></div>';
        footer = '<div class="modal-footer"' + (this.options.footer ? '' : ' style="display:none"') + '>' + this.options.footer + '</div>';
        $(document.body).append('<div id="' + this.modal_id + '" class="ekko-lightbox modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content">' + header + '<div class="modal-body"><div class="ekko-lightbox-container"><div></div></div></div>' + footer + '</div></div></div>');
        this.modal = $('#' + this.modal_id);
        this.modal_dialog = this.modal.find('.modal-dialog').first();
        this.modal_content = this.modal.find('.modal-content').first();
        this.modal_body = this.modal.find('.modal-body').first();
        this.lightbox_container = this.modal_body.find('.ekko-lightbox-container').first();
        this.lightbox_body = this.lightbox_container.find('> div:first-child').first();
        this.showLoading();
        this.modal_arrows = null;
        this.border = {
            top: parseFloat(this.modal_dialog.css('border-top-width')) + parseFloat(this.modal_content.css('border-top-width')) + parseFloat(this.modal_body.css('border-top-width')),
            right: parseFloat(this.modal_dialog.css('border-right-width')) + parseFloat(this.modal_content.css('border-right-width')) + parseFloat(this.modal_body.css('border-right-width')),
            bottom: parseFloat(this.modal_dialog.css('border-bottom-width')) + parseFloat(this.modal_content.css('border-bottom-width')) + parseFloat(this.modal_body.css('border-bottom-width')),
            left: parseFloat(this.modal_dialog.css('border-left-width')) + parseFloat(this.modal_content.css('border-left-width')) + parseFloat(this.modal_body.css('border-left-width'))
        };
        this.padding = {
            top: parseFloat(this.modal_dialog.css('padding-top')) + parseFloat(this.modal_content.css('padding-top')) + parseFloat(this.modal_body.css('padding-top')),
            right: parseFloat(this.modal_dialog.css('padding-right')) + parseFloat(this.modal_content.css('padding-right')) + parseFloat(this.modal_body.css('padding-right')),
            bottom: parseFloat(this.modal_dialog.css('padding-bottom')) + parseFloat(this.modal_content.css('padding-bottom')) + parseFloat(this.modal_body.css('padding-bottom')),
            left: parseFloat(this.modal_dialog.css('padding-left')) + parseFloat(this.modal_content.css('padding-left')) + parseFloat(this.modal_body.css('padding-left'))
        };
        this.modal.on('show.bs.modal', this.options.onShow.bind(this)).on('shown.bs.modal', function () {
            _this.modal_shown();
            return _this.options.onShown.call(_this);
        }).on('hide.bs.modal', this.options.onHide.bind(this)).on('hidden.bs.modal', function () {
            if (_this.gallery) {
                $(document).off('keydown.ekkoLightbox');
            }
            _this.modal.remove();
            return _this.options.onHidden.call(_this);
        }).modal('show', options);
        return this.modal;
    };

    EkkoLightbox.prototype = {
        modal_shown: function () {
            var video_id,
              _this = this;
            if (!this.options.remote) {
                return this.error('No remote target given');
            } else {
                this.gallery = this.$element.data('gallery');
                if (this.gallery) {
                    if (this.options.gallery_parent_selector === 'document.body' || this.options.gallery_parent_selector === '') {
                        this.gallery_items = $(document.body).find('*[data-toggle="lightbox"][data-gallery="' + this.gallery + '"]');
                    } else {
                        this.gallery_items = this.$element.parents(this.options.gallery_parent_selector).first().find('*[data-toggle="lightbox"][data-gallery="' + this.gallery + '"]');
                    }
                    this.gallery_index = this.gallery_items.index(this.$element);
                    $(document).on('keydown.ekkoLightbox', this.navigate.bind(this));
                    if (this.options.directional_arrows && this.gallery_items.length > 1) {
                        this.lightbox_container.append('<div class="ekko-lightbox-nav-overlay"><a href="#" class="' + this.strip_stops(this.options.left_arrow_class) + '"></a><a href="#" class="' + this.strip_stops(this.options.right_arrow_class) + '"></a></div>');
                        this.modal_arrows = this.lightbox_container.find('div.ekko-lightbox-nav-overlay').first();
                        this.lightbox_container.find('a' + this.strip_spaces(this.options.left_arrow_class)).on('click', function (event) {
                            event.preventDefault();
                            return _this.navigate_left();
                        });
                        this.lightbox_container.find('a' + this.strip_spaces(this.options.right_arrow_class)).on('click', function (event) {
                            event.preventDefault();
                            return _this.navigate_right();
                        });
                    }
                }
                if (this.options.type) {
                    if (this.options.type === 'image') {
                        return this.preloadImage(this.options.remote, true);
                    } else if (this.options.type === 'youtube' && (video_id = this.getYoutubeId(this.options.remote))) {
                        return this.showYoutubeVideo(video_id);
                    } else if (this.options.type === 'vimeo') {
                        return this.showVimeoVideo(this.options.remote);
                    } else if (this.options.type === 'instagram') {
                        return this.showInstagramVideo(this.options.remote);
                    } else if (this.options.type === 'url') {
                        return this.loadRemoteContent(this.options.remote);
                    } else if (this.options.type === 'video') {
                        return this.showVideoIframe(this.options.remote);
                    } else {
                        return this.error("Could not detect remote target type. Force the type using data-type=\"image|youtube|vimeo|instagram|url|video\"");
                    }
                } else {
                    return this.detectRemoteType(this.options.remote);
                }
            }
        },
        strip_stops: function (str) {
            return str.replace(/\./g, '');
        },
        strip_spaces: function (str) {
            return str.replace(/\s/g, '');
        },
        isImage: function (str) {
            return str.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i);
        },
        isSwf: function (str) {
            return str.match(/\.(swf)((\?|#).*)?$/i);
        },
        getYoutubeId: function (str) {
            var match;
            match = str.match(/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/);
            if (match && match[2].length === 11) {
                return match[2];
            } else {
                return false;
            }
        },
        getVimeoId: function (str) {
            if (str.indexOf('vimeo') > 0) {
                return str;
            } else {
                return false;
            }
        },
        getInstagramId: function (str) {
            if (str.indexOf('instagram') > 0) {
                return str;
            } else {
                return false;
            }
        },
        navigate: function (event) {
            event = event || window.event;
            if (event.keyCode === 39 || event.keyCode === 37) {
                if (event.keyCode === 39) {
                    return this.navigate_right();
                } else if (event.keyCode === 37) {
                    return this.navigate_left();
                }
            }
        },
        navigateTo: function (index) {
            var next, src;
            if (index < 0 || index > this.gallery_items.length - 1) {
                return this;
            }
            this.showLoading();
            this.gallery_index = index;
            this.$element = $(this.gallery_items.get(this.gallery_index));
            this.updateTitleAndFooter();
            src = this.$element.attr('data-remote') || this.$element.attr('href');
            this.detectRemoteType(src, this.$element.attr('data-type') || false);
            if (this.gallery_index + 1 < this.gallery_items.length) {
                next = $(this.gallery_items.get(this.gallery_index + 1), false);
                src = next.attr('data-remote') || next.attr('href');
                if (next.attr('data-type') === 'image' || this.isImage(src)) {
                    return this.preloadImage(src, false);
                }
            }
        },
        navigate_left: function () {
            if (this.gallery_items.length === 1) {
                return;
            }
            if (this.gallery_index === 0) {
                this.gallery_index = this.gallery_items.length - 1;
            } else {
                this.gallery_index--;
            }
            this.options.onNavigate.call(this, 'left', this.gallery_index);
            return this.navigateTo(this.gallery_index);
        },
        navigate_right: function () {
            if (this.gallery_items.length === 1) {
                return;
            }
            if (this.gallery_index === this.gallery_items.length - 1) {
                this.gallery_index = 0;
            } else {
                this.gallery_index++;
            }
            this.options.onNavigate.call(this, 'right', this.gallery_index);
            return this.navigateTo(this.gallery_index);
        },
        detectRemoteType: function (src, type) {
            var video_id;
            type = type || false;
            if (type === 'image' || this.isImage(src)) {
                this.options.type = 'image';
                return this.preloadImage(src, true);
            } else if (type === 'youtube' || (video_id = this.getYoutubeId(src))) {
                this.options.type = 'youtube';
                return this.showYoutubeVideo(video_id);
            } else if (type === 'vimeo' || (video_id = this.getVimeoId(src))) {
                this.options.type = 'vimeo';
                return this.showVimeoVideo(video_id);
            } else if (type === 'instagram' || (video_id = this.getInstagramId(src))) {
                this.options.type = 'instagram';
                return this.showInstagramVideo(video_id);
            } else if (type === 'video') {
                this.options.type = 'video';
                return this.showVideoIframe(video_id);
            } else {
                this.options.type = 'url';
                return this.loadRemoteContent(src);
            }
        },
        updateTitleAndFooter: function () {
            var caption, footer, header, title;
            header = this.modal_content.find('.modal-header');
            footer = this.modal_content.find('.modal-footer');
            title = this.$element.data('title') || "";
            caption = this.$element.data('footer') || "";
            if (title || this.options.always_show_close) {
                header.css('display', '').find('.modal-title').html(title || "&nbsp;");
            } else {
                header.css('display', 'none');
            }
            if (caption) {
                footer.css('display', '').html(caption);
            } else {
                footer.css('display', 'none');
            }
            return this;
        },
        showLoading: function () {
            this.lightbox_body.html('<div class="modal-loading">' + this.options.loadingMessage + '</div>');
            return this;
        },
        showYoutubeVideo: function (id) {
            var height, width;
            width = this.checkDimensions(this.$element.data('width') || 560);
            height = width / (560 / 315);
            return this.showVideoIframe('//www.youtube.com/embed/' + id + '?badge=0&autoplay=1&html5=1', width, height);
        },
        showVimeoVideo: function (id) {
            var height, width;
            width = this.checkDimensions(this.$element.data('width') || 560);
            height = width / (500 / 281);
            return this.showVideoIframe(id + '?autoplay=1', width, height);
        },
        showInstagramVideo: function (id) {
            var height, width;
            width = this.checkDimensions(this.$element.data('width') || 612);
            this.resize(width);
            height = width + 80;
            this.lightbox_body.html('<iframe width="' + width + '" height="' + height + '" src="' + this.addTrailingSlash(id) + 'embed/" frameborder="0" allowfullscreen></iframe>');
            this.options.onContentLoaded.call(this);
            if (this.modal_arrows) {
                return this.modal_arrows.css('display', 'none');
            }
        },
        showVideoIframe: function (url, width, height) {
            height = height || width;
            this.resize(width);
            this.lightbox_body.html('<div class="embed-responsive embed-responsive-16by9"><iframe width="' + width + '" height="' + height + '" src="' + url + '" frameborder="0" allowfullscreenclass="embed-responsive-item"></iframe></div>');
            this.options.onContentLoaded.call(this);
            if (this.modal_arrows) {
                this.modal_arrows.css('display', 'none');
            }
            return this;
        },
        loadRemoteContent: function (url) {
            var disableExternalCheck, width,
              _this = this;
            width = this.$element.data('width') || 560;
            this.resize(width);
            disableExternalCheck = this.$element.data('disableExternalCheck') || false;
            if (!disableExternalCheck && !this.isExternal(url)) {
                this.lightbox_body.load(url, $.proxy(function () {
                    return _this.$element.trigger('loaded.bs.modal');
                }));
            } else {
                this.lightbox_body.html('<iframe width="' + width + '" height="' + width + '" src="' + url + '" frameborder="0" allowfullscreen></iframe>');
                this.options.onContentLoaded.call(this);
            }
            if (this.modal_arrows) {
                this.modal_arrows.css('display', 'none');
            }
            return this;
        },
        isExternal: function (url) {
            var match;
            match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);
            if (typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol) {
                return true;
            }
            if (typeof match[2] === "string" && match[2].length > 0 && match[2].replace(new RegExp(":(" + {
              "http:": 80,
              "https:": 443
            }[location.protocol] + ")?$"), "") !== location.host) {
                return true;
            }
            return false;
        },
        error: function (message) {
            this.lightbox_body.html(message);
            return this;
        },
        preloadImage: function (src, onLoadShowImage) {
            var img,
              _this = this;
            img = new Image();
            if ((onLoadShowImage == null) || onLoadShowImage === true) {
                img.onload = function () {
                    var image;
                    var maxh = $(window).height() * 0.8; //% of screen height
                    image = $('<img />');
                    image.attr('src', img.src);
                    image.attr('style', "max-height: " + maxh + "px");
                    image.addClass('img-responsive');
                    _this.lightbox_body.html(image);
                    if (_this.modal_arrows) {
                        _this.modal_arrows.css('display', 'block');
                    }
                    _this.resize(Math.min(maxh / img.height, 1) * img.width);
                    return _this.options.onContentLoaded.call(_this);
                };
                img.onerror = function () {
                    return _this.error('Failed to load image: ' + src);
                };
            }
            img.src = src;
            return img;
        },
        resize: function (width) {
            var width_total;
            width_total = width + this.border.left + this.padding.left + this.padding.right + this.border.right;
            this.modal_dialog.css('width', 'auto').css('max-width', width_total);
            this.lightbox_container.find('a').css('line-height', function () {
                return $(this).parent().height() + 'px';
            });
            return this;
        },
        checkDimensions: function (width) {
            var body_width, width_total;
            width_total = width + this.border.left + this.padding.left + this.padding.right + this.border.right;
            body_width = document.body.clientWidth;
            if (width_total > body_width) {
                width = this.modal_body.width();
            }
            return width;
        },
        close: function () {
            return this.modal.modal('hide');
        },
        addTrailingSlash: function (url) {
            if (url.substr(-1) !== '/') {
                url += '/';
            }
            return url;
        }
    };

    $.fn.ekkoLightbox = function (options) {
        return this.each(function () {
            var $this;
            $this = $(this);
            options = $.extend({
                remote: $this.attr('data-remote') || $this.attr('href'),
                gallery_parent_selector: $this.attr('data-parent'),
                type: $this.attr('data-type')
            }, options, $this.data());
            new EkkoLightbox(this, options);
            return this;
        });
    };

    $.fn.ekkoLightbox.defaults = {
        gallery_parent_selector: 'document.body',
        left_arrow_class: '.glyphicon .glyphicon-chevron-left',
        right_arrow_class: '.glyphicon .glyphicon-chevron-right',
        directional_arrows: true,
        type: null,
        always_show_close: true,
        loadingMessage: 'Loading...',
        onShow: function () { },
        onShown: function () { },
        onHide: function () { },
        onHidden: function () { },
        onNavigate: function () { },
        onContentLoaded: function () { }

    };

}).call(this);


/*
    This valing jQuery plug-in is dual licensed under the MIT and GPL licenses.
    @link        -
    @docs        -
    @version     Version 0.0.2
*/
(function (c) {
    function e(b) { var a = c('<div style="float:none;display:none;font-size:1em;margin:0em;padding:0em;height:auto;line-height:1em;border:0em none;">a</div>').appendTo(c(b)); b = a.height(); a.remove(); return b } function f(b, a) { var d; switch (b.valignTo) { case "middle": d = Math.ceil((c(a).parent().height() - c(a).outerHeight()) / 2); break; case "bottom": d = Math.ceil(c(a).parent().height() - c(a).outerHeight()) } "em" == b.unit && (d = (d / e(a)).toFixed(4) + "em"); return d } c.fn.valign = function (b) {
        var a = this.selector; b = c.extend({
            unit: "px",
            valignTo: "middle"
        }, b); this.each(function () { var d = b; c(a).css({ "margin-top": f(d, a) }) }); return this
    }
})(jQuery);


/* Placeholders.js v3.0.2 */
(function (t) { "use strict"; function e(t, e, r) { return t.addEventListener ? t.addEventListener(e, r, !1) : t.attachEvent ? t.attachEvent("on" + e, r) : void 0 } function r(t, e) { var r, n; for (r = 0, n = t.length; n > r; r++) if (t[r] === e) return !0; return !1 } function n(t, e) { var r; t.createTextRange ? (r = t.createTextRange(), r.move("character", e), r.select()) : t.selectionStart && (t.focus(), t.setSelectionRange(e, e)) } function a(t, e) { try { return t.type = e, !0 } catch (r) { return !1 } } t.Placeholders = { Utils: { addEventListener: e, inArray: r, moveCaret: n, changeType: a } } })(this), function (t) { "use strict"; function e() { } function r() { try { return document.activeElement } catch (t) { } } function n(t, e) { var r, n, a = !!e && t.value !== e, u = t.value === t.getAttribute(V); return (a || u) && "true" === t.getAttribute(D) ? (t.removeAttribute(D), t.value = t.value.replace(t.getAttribute(V), ""), t.className = t.className.replace(R, ""), n = t.getAttribute(F), parseInt(n, 10) >= 0 && (t.setAttribute("maxLength", n), t.removeAttribute(F)), r = t.getAttribute(P), r && (t.type = r), !0) : !1 } function a(t) { var e, r, n = t.getAttribute(V); return "" === t.value && n ? (t.setAttribute(D, "true"), t.value = n, t.className += " " + I, r = t.getAttribute(F), r || (t.setAttribute(F, t.maxLength), t.removeAttribute("maxLength")), e = t.getAttribute(P), e ? t.type = "text" : "password" === t.type && M.changeType(t, "text") && t.setAttribute(P, "password"), !0) : !1 } function u(t, e) { var r, n, a, u, i, l, o; if (t && t.getAttribute(V)) e(t); else for (a = t ? t.getElementsByTagName("input") : b, u = t ? t.getElementsByTagName("textarea") : f, r = a ? a.length : 0, n = u ? u.length : 0, o = 0, l = r + n; l > o; o++) i = r > o ? a[o] : u[o - r], e(i) } function i(t) { u(t, n) } function l(t) { u(t, a) } function o(t) { return function () { m && t.value === t.getAttribute(V) && "true" === t.getAttribute(D) ? M.moveCaret(t, 0) : n(t) } } function c(t) { return function () { a(t) } } function s(t) { return function (e) { return A = t.value, "true" === t.getAttribute(D) && A === t.getAttribute(V) && M.inArray(C, e.keyCode) ? (e.preventDefault && e.preventDefault(), !1) : void 0 } } function d(t) { return function () { n(t, A), "" === t.value && (t.blur(), M.moveCaret(t, 0)) } } function g(t) { return function () { t === r() && t.value === t.getAttribute(V) && "true" === t.getAttribute(D) && M.moveCaret(t, 0) } } function v(t) { return function () { i(t) } } function p(t) { t.form && (T = t.form, "string" == typeof T && (T = document.getElementById(T)), T.getAttribute(U) || (M.addEventListener(T, "submit", v(T)), T.setAttribute(U, "true"))), M.addEventListener(t, "focus", o(t)), M.addEventListener(t, "blur", c(t)), m && (M.addEventListener(t, "keydown", s(t)), M.addEventListener(t, "keyup", d(t)), M.addEventListener(t, "click", g(t))), t.setAttribute(j, "true"), t.setAttribute(V, x), (m || t !== r()) && a(t) } var b, f, m, h, A, y, E, x, L, T, N, S, w, B = ["text", "search", "url", "tel", "email", "password", "number", "textarea"], C = [27, 33, 34, 35, 36, 37, 38, 39, 40, 8, 46], k = "#ccc", I = "placeholdersjs", R = RegExp("(?:^|\\s)" + I + "(?!\\S)"), V = "data-placeholder-value", D = "data-placeholder-active", P = "data-placeholder-type", U = "data-placeholder-submit", j = "data-placeholder-bound", q = "data-placeholder-focus", z = "data-placeholder-live", F = "data-placeholder-maxlength", G = document.createElement("input"), H = document.getElementsByTagName("head")[0], J = document.documentElement, K = t.Placeholders, M = K.Utils; if (K.nativeSupport = void 0 !== G.placeholder, !K.nativeSupport) { for (b = document.getElementsByTagName("input"), f = document.getElementsByTagName("textarea"), m = "false" === J.getAttribute(q), h = "false" !== J.getAttribute(z), y = document.createElement("style"), y.type = "text/css", E = document.createTextNode("." + I + " { color:" + k + "; }"), y.styleSheet ? y.styleSheet.cssText = E.nodeValue : y.appendChild(E), H.insertBefore(y, H.firstChild), w = 0, S = b.length + f.length; S > w; w++) N = b.length > w ? b[w] : f[w - b.length], x = N.attributes.placeholder, x && (x = x.nodeValue, x && M.inArray(B, N.type) && p(N)); L = setInterval(function () { for (w = 0, S = b.length + f.length; S > w; w++) N = b.length > w ? b[w] : f[w - b.length], x = N.attributes.placeholder, x ? (x = x.nodeValue, x && M.inArray(B, N.type) && (N.getAttribute(j) || p(N), (x !== N.getAttribute(V) || "password" === N.type && !N.getAttribute(P)) && ("password" === N.type && !N.getAttribute(P) && M.changeType(N, "text") && N.setAttribute(P, "password"), N.value === N.getAttribute(V) && (N.value = x), N.setAttribute(V, x)))) : N.getAttribute(D) && (n(N), N.removeAttribute(V)); h || clearInterval(L) }, 100) } M.addEventListener(t, "beforeunload", function () { K.disable() }), K.disable = K.nativeSupport ? e : i, K.enable = K.nativeSupport ? e : l }(this);


// WAYPOINTS
// Generated by CoffeeScript 1.6.2
/*!
jQuery Waypoints - v2.0.5
Copyright (c) 2011-2014 Caleb Troughton
Licensed under the MIT license.
https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
*/
(function () { var t = [].indexOf || function (t) { for (var e = 0, n = this.length; e < n; e++) { if (e in this && this[e] === t) return e } return -1 }, e = [].slice; (function (t, e) { if (typeof define === "function" && define.amd) { return define("waypoints", ["jquery"], function (n) { return e(n, t) }) } else { return e(t.jQuery, t) } })(window, function (n, r) { var i, o, l, s, f, u, c, a, h, d, p, y, v, w, g, m; i = n(r); a = t.call(r, "ontouchstart") >= 0; s = { horizontal: {}, vertical: {} }; f = 1; c = {}; u = "waypoints-context-id"; p = "resize.waypoints"; y = "scroll.waypoints"; v = 1; w = "waypoints-waypoint-ids"; g = "waypoint"; m = "waypoints"; o = function () { function t(t) { var e = this; this.$element = t; this.element = t[0]; this.didResize = false; this.didScroll = false; this.id = "context" + f++; this.oldScroll = { x: t.scrollLeft(), y: t.scrollTop() }; this.waypoints = { horizontal: {}, vertical: {} }; this.element[u] = this.id; c[this.id] = this; t.bind(y, function () { var t; if (!(e.didScroll || a)) { e.didScroll = true; t = function () { e.doScroll(); return e.didScroll = false }; return r.setTimeout(t, n[m].settings.scrollThrottle) } }); t.bind(p, function () { var t; if (!e.didResize) { e.didResize = true; t = function () { n[m]("refresh"); return e.didResize = false }; return r.setTimeout(t, n[m].settings.resizeThrottle) } }) } t.prototype.doScroll = function () { var t, e = this; t = { horizontal: { newScroll: this.$element.scrollLeft(), oldScroll: this.oldScroll.x, forward: "right", backward: "left" }, vertical: { newScroll: this.$element.scrollTop(), oldScroll: this.oldScroll.y, forward: "down", backward: "up" } }; if (a && (!t.vertical.oldScroll || !t.vertical.newScroll)) { n[m]("refresh") } n.each(t, function (t, r) { var i, o, l; l = []; o = r.newScroll > r.oldScroll; i = o ? r.forward : r.backward; n.each(e.waypoints[t], function (t, e) { var n, i; if (r.oldScroll < (n = e.offset) && n <= r.newScroll) { return l.push(e) } else if (r.newScroll < (i = e.offset) && i <= r.oldScroll) { return l.push(e) } }); l.sort(function (t, e) { return t.offset - e.offset }); if (!o) { l.reverse() } return n.each(l, function (t, e) { if (e.options.continuous || t === l.length - 1) { return e.trigger([i]) } }) }); return this.oldScroll = { x: t.horizontal.newScroll, y: t.vertical.newScroll } }; t.prototype.refresh = function () { var t, e, r, i = this; r = n.isWindow(this.element); e = this.$element.offset(); this.doScroll(); t = { horizontal: { contextOffset: r ? 0 : e.left, contextScroll: r ? 0 : this.oldScroll.x, contextDimension: this.$element.width(), oldScroll: this.oldScroll.x, forward: "right", backward: "left", offsetProp: "left" }, vertical: { contextOffset: r ? 0 : e.top, contextScroll: r ? 0 : this.oldScroll.y, contextDimension: r ? n[m]("viewportHeight") : this.$element.height(), oldScroll: this.oldScroll.y, forward: "down", backward: "up", offsetProp: "top" } }; return n.each(t, function (t, e) { return n.each(i.waypoints[t], function (t, r) { var i, o, l, s, f; i = r.options.offset; l = r.offset; o = n.isWindow(r.element) ? 0 : r.$element.offset()[e.offsetProp]; if (n.isFunction(i)) { i = i.apply(r.element) } else if (typeof i === "string") { i = parseFloat(i); if (r.options.offset.indexOf("%") > -1) { i = Math.ceil(e.contextDimension * i / 100) } } r.offset = o - e.contextOffset + e.contextScroll - i; if (r.options.onlyOnScroll && l != null || !r.enabled) { return } if (l !== null && l < (s = e.oldScroll) && s <= r.offset) { return r.trigger([e.backward]) } else if (l !== null && l > (f = e.oldScroll) && f >= r.offset) { return r.trigger([e.forward]) } else if (l === null && e.oldScroll >= r.offset) { return r.trigger([e.forward]) } }) }) }; t.prototype.checkEmpty = function () { if (n.isEmptyObject(this.waypoints.horizontal) && n.isEmptyObject(this.waypoints.vertical)) { this.$element.unbind([p, y].join(" ")); return delete c[this.id] } }; return t }(); l = function () { function t(t, e, r) { var i, o; if (r.offset === "bottom-in-view") { r.offset = function () { var t; t = n[m]("viewportHeight"); if (!n.isWindow(e.element)) { t = e.$element.height() } return t - n(this).outerHeight() } } this.$element = t; this.element = t[0]; this.axis = r.horizontal ? "horizontal" : "vertical"; this.callback = r.handler; this.context = e; this.enabled = r.enabled; this.id = "waypoints" + v++; this.offset = null; this.options = r; e.waypoints[this.axis][this.id] = this; s[this.axis][this.id] = this; i = (o = this.element[w]) != null ? o : []; i.push(this.id); this.element[w] = i } t.prototype.trigger = function (t) { if (!this.enabled) { return } if (this.callback != null) { this.callback.apply(this.element, t) } if (this.options.triggerOnce) { return this.destroy() } }; t.prototype.disable = function () { return this.enabled = false }; t.prototype.enable = function () { this.context.refresh(); return this.enabled = true }; t.prototype.destroy = function () { delete s[this.axis][this.id]; delete this.context.waypoints[this.axis][this.id]; return this.context.checkEmpty() }; t.getWaypointsByElement = function (t) { var e, r; r = t[w]; if (!r) { return [] } e = n.extend({}, s.horizontal, s.vertical); return n.map(r, function (t) { return e[t] }) }; return t }(); d = { init: function (t, e) { var r; e = n.extend({}, n.fn[g].defaults, e); if ((r = e.handler) == null) { e.handler = t } this.each(function () { var t, r, i, s; t = n(this); i = (s = e.context) != null ? s : n.fn[g].defaults.context; if (!n.isWindow(i)) { i = t.closest(i) } i = n(i); r = c[i[0][u]]; if (!r) { r = new o(i) } return new l(t, r, e) }); n[m]("refresh"); return this }, disable: function () { return d._invoke.call(this, "disable") }, enable: function () { return d._invoke.call(this, "enable") }, destroy: function () { return d._invoke.call(this, "destroy") }, prev: function (t, e) { return d._traverse.call(this, t, e, function (t, e, n) { if (e > 0) { return t.push(n[e - 1]) } }) }, next: function (t, e) { return d._traverse.call(this, t, e, function (t, e, n) { if (e < n.length - 1) { return t.push(n[e + 1]) } }) }, _traverse: function (t, e, i) { var o, l; if (t == null) { t = "vertical" } if (e == null) { e = r } l = h.aggregate(e); o = []; this.each(function () { var e; e = n.inArray(this, l[t]); return i(o, e, l[t]) }); return this.pushStack(o) }, _invoke: function (t) { this.each(function () { var e; e = l.getWaypointsByElement(this); return n.each(e, function (e, n) { n[t](); return true }) }); return this } }; n.fn[g] = function () { var t, r; r = arguments[0], t = 2 <= arguments.length ? e.call(arguments, 1) : []; if (d[r]) { return d[r].apply(this, t) } else if (n.isFunction(r)) { return d.init.apply(this, arguments) } else if (n.isPlainObject(r)) { return d.init.apply(this, [null, r]) } else if (!r) { return n.error("jQuery Waypoints needs a callback function or handler option.") } else { return n.error("The " + r + " method does not exist in jQuery Waypoints.") } }; n.fn[g].defaults = { context: r, continuous: true, enabled: true, horizontal: false, offset: 0, triggerOnce: false }; h = { refresh: function () { return n.each(c, function (t, e) { return e.refresh() }) }, viewportHeight: function () { var t; return (t = r.innerHeight) != null ? t : i.height() }, aggregate: function (t) { var e, r, i; e = s; if (t) { e = (i = c[n(t)[0][u]]) != null ? i.waypoints : void 0 } if (!e) { return [] } r = { horizontal: [], vertical: [] }; n.each(r, function (t, i) { n.each(e[t], function (t, e) { return i.push(e) }); i.sort(function (t, e) { return t.offset - e.offset }); r[t] = n.map(i, function (t) { return t.element }); return r[t] = n.unique(r[t]) }); return r }, above: function (t) { if (t == null) { t = r } return h._filter(t, "vertical", function (t, e) { return e.offset <= t.oldScroll.y }) }, below: function (t) { if (t == null) { t = r } return h._filter(t, "vertical", function (t, e) { return e.offset > t.oldScroll.y }) }, left: function (t) { if (t == null) { t = r } return h._filter(t, "horizontal", function (t, e) { return e.offset <= t.oldScroll.x }) }, right: function (t) { if (t == null) { t = r } return h._filter(t, "horizontal", function (t, e) { return e.offset > t.oldScroll.x }) }, enable: function () { return h._invoke("enable") }, disable: function () { return h._invoke("disable") }, destroy: function () { return h._invoke("destroy") }, extendFn: function (t, e) { return d[t] = e }, _invoke: function (t) { var e; e = n.extend({}, s.vertical, s.horizontal); return n.each(e, function (e, n) { n[t](); return true }) }, _filter: function (t, e, r) { var i, o; i = c[n(t)[0][u]]; if (!i) { return [] } o = []; n.each(i.waypoints[e], function (t, e) { if (r(i, e)) { return o.push(e) } }); o.sort(function (t, e) { return t.offset - e.offset }); return n.map(o, function (t) { return t.element }) } }; n[m] = function () { var t, n; n = arguments[0], t = 2 <= arguments.length ? e.call(arguments, 1) : []; if (h[n]) { return h[n].apply(null, t) } else { return h.aggregate.call(null, n) } }; n[m].settings = { resizeThrottle: 100, scrollThrottle: 30 }; return i.on("load.waypoints", function () { return n[m]("refresh") }) }) }).call(this);

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright Ã‚Â© 2001 Robert Penner
 * All rights reserved.
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright Ã‚Â© 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/
jQuery.easing.jswing = jQuery.easing.swing; jQuery.extend(jQuery.easing, { def: "easeOutQuad", swing: function (e, f, a, h, g) { return jQuery.easing[jQuery.easing.def](e, f, a, h, g) }, easeInQuad: function (e, f, a, h, g) { return h * (f /= g) * f + a }, easeOutQuad: function (e, f, a, h, g) { return -h * (f /= g) * (f - 2) + a }, easeInOutQuad: function (e, f, a, h, g) { if ((f /= g / 2) < 1) { return h / 2 * f * f + a } return -h / 2 * ((--f) * (f - 2) - 1) + a }, easeInCubic: function (e, f, a, h, g) { return h * (f /= g) * f * f + a }, easeOutCubic: function (e, f, a, h, g) { return h * ((f = f / g - 1) * f * f + 1) + a }, easeInOutCubic: function (e, f, a, h, g) { if ((f /= g / 2) < 1) { return h / 2 * f * f * f + a } return h / 2 * ((f -= 2) * f * f + 2) + a }, easeInQuart: function (e, f, a, h, g) { return h * (f /= g) * f * f * f + a }, easeOutQuart: function (e, f, a, h, g) { return -h * ((f = f / g - 1) * f * f * f - 1) + a }, easeInOutQuart: function (e, f, a, h, g) { if ((f /= g / 2) < 1) { return h / 2 * f * f * f * f + a } return -h / 2 * ((f -= 2) * f * f * f - 2) + a }, easeInQuint: function (e, f, a, h, g) { return h * (f /= g) * f * f * f * f + a }, easeOutQuint: function (e, f, a, h, g) { return h * ((f = f / g - 1) * f * f * f * f + 1) + a }, easeInOutQuint: function (e, f, a, h, g) { if ((f /= g / 2) < 1) { return h / 2 * f * f * f * f * f + a } return h / 2 * ((f -= 2) * f * f * f * f + 2) + a }, easeInSine: function (e, f, a, h, g) { return -h * Math.cos(f / g * (Math.PI / 2)) + h + a }, easeOutSine: function (e, f, a, h, g) { return h * Math.sin(f / g * (Math.PI / 2)) + a }, easeInOutSine: function (e, f, a, h, g) { return -h / 2 * (Math.cos(Math.PI * f / g) - 1) + a }, easeInExpo: function (e, f, a, h, g) { return (f == 0) ? a : h * Math.pow(2, 10 * (f / g - 1)) + a }, easeOutExpo: function (e, f, a, h, g) { return (f == g) ? a + h : h * (-Math.pow(2, -10 * f / g) + 1) + a }, easeInOutExpo: function (e, f, a, h, g) { if (f == 0) { return a } if (f == g) { return a + h } if ((f /= g / 2) < 1) { return h / 2 * Math.pow(2, 10 * (f - 1)) + a } return h / 2 * (-Math.pow(2, -10 * --f) + 2) + a }, easeInCirc: function (e, f, a, h, g) { return -h * (Math.sqrt(1 - (f /= g) * f) - 1) + a }, easeOutCirc: function (e, f, a, h, g) { return h * Math.sqrt(1 - (f = f / g - 1) * f) + a }, easeInOutCirc: function (e, f, a, h, g) { if ((f /= g / 2) < 1) { return -h / 2 * (Math.sqrt(1 - f * f) - 1) + a } return h / 2 * (Math.sqrt(1 - (f -= 2) * f) + 1) + a }, easeInElastic: function (f, h, e, l, k) { var i = 1.70158; var j = 0; var g = l; if (h == 0) { return e } if ((h /= k) == 1) { return e + l } if (!j) { j = k * 0.3 } if (g < Math.abs(l)) { g = l; var i = j / 4 } else { var i = j / (2 * Math.PI) * Math.asin(l / g) } return -(g * Math.pow(2, 10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j)) + e }, easeOutElastic: function (f, h, e, l, k) { var i = 1.70158; var j = 0; var g = l; if (h == 0) { return e } if ((h /= k) == 1) { return e + l } if (!j) { j = k * 0.3 } if (g < Math.abs(l)) { g = l; var i = j / 4 } else { var i = j / (2 * Math.PI) * Math.asin(l / g) } return g * Math.pow(2, -10 * h) * Math.sin((h * k - i) * (2 * Math.PI) / j) + l + e }, easeInOutElastic: function (f, h, e, l, k) { var i = 1.70158; var j = 0; var g = l; if (h == 0) { return e } if ((h /= k / 2) == 2) { return e + l } if (!j) { j = k * (0.3 * 1.5) } if (g < Math.abs(l)) { g = l; var i = j / 4 } else { var i = j / (2 * Math.PI) * Math.asin(l / g) } if (h < 1) { return -0.5 * (g * Math.pow(2, 10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j)) + e } return g * Math.pow(2, -10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j) * 0.5 + l + e }, easeInBack: function (e, f, a, i, h, g) { if (g == undefined) { g = 1.70158 } return i * (f /= h) * f * ((g + 1) * f - g) + a }, easeOutBack: function (e, f, a, i, h, g) { if (g == undefined) { g = 1.70158 } return i * ((f = f / h - 1) * f * ((g + 1) * f + g) + 1) + a }, easeInOutBack: function (e, f, a, i, h, g) { if (g == undefined) { g = 1.70158 } if ((f /= h / 2) < 1) { return i / 2 * (f * f * (((g *= (1.525)) + 1) * f - g)) + a } return i / 2 * ((f -= 2) * f * (((g *= (1.525)) + 1) * f + g) + 2) + a }, easeInBounce: function (e, f, a, h, g) { return h - jQuery.easing.easeOutBounce(e, g - f, 0, h, g) + a }, easeOutBounce: function (e, f, a, h, g) { if ((f /= g) < (1 / 2.75)) { return h * (7.5625 * f * f) + a } else { if (f < (2 / 2.75)) { return h * (7.5625 * (f -= (1.5 / 2.75)) * f + 0.75) + a } else { if (f < (2.5 / 2.75)) { return h * (7.5625 * (f -= (2.25 / 2.75)) * f + 0.9375) + a } else { return h * (7.5625 * (f -= (2.625 / 2.75)) * f + 0.984375) + a } } } }, easeInOutBounce: function (e, f, a, h, g) { if (f < g / 2) { return jQuery.easing.easeInBounce(e, f * 2, 0, h, g) * 0.5 + a } return jQuery.easing.easeOutBounce(e, f * 2 - g, 0, h, g) * 0.5 + h * 0.5 + a } });
