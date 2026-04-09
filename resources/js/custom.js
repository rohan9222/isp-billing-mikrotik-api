"use strict";

var _excluded = ["endValue"];

function _objectWithoutProperties(e, t) {
    if (null == e) return {};
    var o, r, i = _objectWithoutPropertiesLoose(e, t);
    if (Object.getOwnPropertySymbols) {
        var s = Object.getOwnPropertySymbols(e);
        for (r = 0; r < s.length; r++) o = s[r], t.includes(o) || {}.propertyIsEnumerable.call(e, o) && (i[o] = e[o]);
    }
    return i;
}

function _objectWithoutPropertiesLoose(r, e) {
    if (null == r) return {};
    var t = {};
    for (var n in r)
        if ({}.hasOwnProperty.call(r, n)) {
            if (e.includes(n)) continue;
            t[n] = r[n];
        }
    return t;
}

function _toConsumableArray(r) {
    return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread();
}

function _nonIterableSpread() {
    throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

function _unsupportedIterableToArray(r, a) {
    if (r) {
        if ("string" == typeof r) return _arrayLikeToArray(r, a);
        var t = {}.toString.call(r).slice(8, -1);
        return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0;
    }
}

function _iterableToArray(r) {
    if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r);
}

function _arrayWithoutHoles(r) {
    if (Array.isArray(r)) return _arrayLikeToArray(r);
}

function _arrayLikeToArray(r, a) {
    (null == a || a > r.length) && (a = r.length);
    for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
    return n;
}

function ownKeys(e, r) {
    var t = Object.keys(e);
    if (Object.getOwnPropertySymbols) {
        var o = Object.getOwnPropertySymbols(e);
        r && (o = o.filter(function(r) {
            return Object.getOwnPropertyDescriptor(e, r).enumerable;
        })), t.push.apply(t, o);
    }
    return t;
}

function _objectSpread(e) {
    for (var r = 1; r < arguments.length; r++) {
        var t = null != arguments[r] ? arguments[r] : {};
        r % 2 ? ownKeys(Object(t), !0).forEach(function(r) {
            _defineProperty(e, r, t[r]);
        }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function(r) {
            Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r));
        });
    }
    return e;
}

function _defineProperty(e, r, t) {
    return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, {
        value: t,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[r] = t, e;
}

function _typeof(o) {
    "@babel/helpers - typeof";
    return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(o) {
        return typeof o;
    } : function(o) {
        return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
    }, _typeof(o);
}

function _classCallCheck(a, n) {
    if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}

function _defineProperties(e, r) {
    for (var t = 0; t < r.length; t++) {
        var o = r[t];
        o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o);
    }
}

function _createClass(e, r, t) {
    return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
        writable: !1
    }), e;
}

function _toPropertyKey(t) {
    var i = _toPrimitive(t, "string");
    return "symbol" == _typeof(i) ? i : i + "";
}

function _toPrimitive(t, r) {
    if ("object" != _typeof(t) || !t) return t;
    var e = t[Symbol.toPrimitive];
    if (void 0 !== e) {
        var i = e.call(t, r || "default");
        if ("object" != _typeof(i)) return i;
        throw new TypeError("@@toPrimitive must return a primitive value.");
    }
    return ("string" === r ? String : Number)(t);
}
/* -------------------------------------------------------------------------- */
/*                                    Utils                                   */
/* -------------------------------------------------------------------------- */
var docReady = function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        setTimeout(fn, 1);
    }
};
var resize = function resize(fn) {
    return window.addEventListener('resize', fn);
};
var isIterableArray = function isIterableArray(array) {
    return Array.isArray(array) && !!array.length;
};
var camelize = function camelize(str) {
    var text = str.replace(/[-_\s.]+(.)?/g, function(match, capture) {
        if (capture) {
            return capture.toUpperCase();
        }
        return '';
    });
    return "".concat(text.substr(0, 1).toLowerCase()).concat(text.substr(1));
};
var getData = function getData(el, data) {
    try {
        return JSON.parse(el.dataset[camelize(data)]);
    } catch (e) {
        return el.dataset[camelize(data)];
    }
};

/* ----------------------------- Colors function ---------------------------- */

var hexToRgb = function hexToRgb(hexValue) {
    var hex;
    hexValue.indexOf('#') === 0 ? hex = hexValue.substring(1) : hex = hexValue;
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    }));
    return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : null;
};
var rgbaColor = function rgbaColor() {
    var color = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '#fff';
    var alpha = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0.5;
    return "rgba(".concat(hexToRgb(color), ", ").concat(alpha, ")");
};

/* --------------------------------- Colors --------------------------------- */

var getColor = function getColor(name) {
    var dom = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : document.documentElement;
    return getComputedStyle(dom).getPropertyValue("--cp-".concat(name)).trim();
};
var getColors = function getColors(dom) {
    return {
        primary: getColor('primary', dom),
        secondary: getColor('secondary', dom),
        success: getColor('success', dom),
        info: getColor('info', dom),
        warning: getColor('warning', dom),
        danger: getColor('danger', dom),
        light: getColor('light', dom),
        dark: getColor('dark', dom),
        white: getColor('white', dom),
        black: getColor('black', dom),
        emphasis: getColor('emphasis-color', dom)
    };
};
var getSubtleColors = function getSubtleColors(dom) {
    return {
        primary: getColor('primary-bg-subtle', dom),
        secondary: getColor('secondary-bg-subtle', dom),
        success: getColor('success-bg-subtle', dom),
        info: getColor('info-bg-subtle', dom),
        warning: getColor('warning-bg-subtle', dom),
        danger: getColor('danger-bg-subtle', dom),
        light: getColor('light-bg-subtle', dom),
        dark: getColor('dark-bg-subtle', dom)
    };
};
var getGrays = function getGrays(dom) {
    return {
        100: getColor('gray-100', dom),
        200: getColor('gray-200', dom),
        300: getColor('gray-300', dom),
        400: getColor('gray-400', dom),
        500: getColor('gray-500', dom),
        600: getColor('gray-600', dom),
        700: getColor('gray-700', dom),
        800: getColor('gray-800', dom),
        900: getColor('gray-900', dom),
        1000: getColor('gray-1000', dom),
        1100: getColor('gray-1100', dom)
    };
};
var hasClass = function hasClass(el, className) {
    !el && false;
    return el.classList.value.includes(className);
};
var addClass = function addClass(el, className) {
    el.classList.add(className);
};
var removeClass = function removeClass(el, className) {
    el.classList.remove(className);
};
var getOffset = function getOffset(el) {
    var rect = el.getBoundingClientRect();
    var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return {
        top: rect.top + scrollTop,
        left: rect.left + scrollLeft
    };
};

function isScrolledIntoView(el) {
    var rect = el.getBoundingClientRect();
    var windowHeight = window.innerHeight || document.documentElement.clientHeight;
    var windowWidth = window.innerWidth || document.documentElement.clientWidth;
    var vertInView = rect.top <= windowHeight && rect.top + rect.height >= 0;
    var horInView = rect.left <= windowWidth && rect.left + rect.width >= 0;
    return vertInView && horInView;
}
var breakpoints = {
    xs: 0,
    sm: 576,
    md: 768,
    lg: 992,
    xl: 1200,
    xxl: 1540
};
var getBreakpoint = function getBreakpoint(el) {
    var classes = el && el.classList.value;
    var breakpoint;
    if (classes) {
        breakpoint = breakpoints[classes.split(' ').filter(function(cls) {
            return cls.includes('navbar-expand-');
        }).pop().split('-').pop()];
    }
    return breakpoint;
};
var getSystemTheme = function getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};
var isDark = function isDark() {
    return localStorage.getItem('theme') === 'auto' ? getSystemTheme() : localStorage.getItem('theme');
};

/* --------------------------------- Cookie --------------------------------- */

var setCookie = function setCookie(name, value, expire) {
    var expires = new Date();
    expires.setTime(expires.getTime() + expire);
    document.cookie = "".concat(name, "=").concat(value, ";expires=").concat(expires.toUTCString());
};
var getCookie = function getCookie(name) {
    var keyValue = document.cookie.match("(^|;) ?".concat(name, "=([^;]*)(;|$)"));
    return keyValue ? keyValue[2] : keyValue;
};
var settings = {
    tinymce: {
        theme: 'oxide'
    },
    chart: {
        borderColor: 'rgba(255, 255, 255, 0.8)'
    }
};


/* ---------------------------------- Store --------------------------------- */

var getItemFromStore = function getItemFromStore(key, defaultValue) {
    var store = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : localStorage;
    try {
        return JSON.parse(store.getItem(key)) || defaultValue;
    } catch (_unused) {
        return store.getItem(key) || defaultValue;
    }
};

var setItemToStore = function setItemToStore(key, payload) {
    var store = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : localStorage;
    return store.setItem(key, payload);
};
var getStoreSpace = function getStoreSpace() {
    var store = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : localStorage;
    return parseFloat((escape(encodeURIComponent(JSON.stringify(store))).length / (1024 * 1024)).toFixed(2));
};

/* get Dates between */

var getDates = function getDates(startDate, endDate) {
    var interval = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1000 * 60 * 60 * 24;
    var duration = endDate - startDate;
    var steps = duration / interval;
    return Array.from({
        length: steps + 1
    }, function(v, i) {
        return new Date(startDate.valueOf() + interval * i);
    });
};
var getPastDates = function getPastDates(duration) {
    var days;
    switch (duration) {
        case 'week':
            days = 7;
            break;
        case 'month':
            days = 30;
            break;
        case 'year':
            days = 365;
            break;
        default:
            days = duration;
    }
    var date = new Date();
    var endDate = date;
    var startDate = new Date(new Date().setDate(date.getDate() - (days - 1)));
    return getDates(startDate, endDate);
};

/* Get Random Number */
var getRandomNumber = function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
};

var utils = {
    docReady: docReady,
    breakpoints: breakpoints,
    resize: resize,
    isIterableArray: isIterableArray,
    camelize: camelize,
    getData: getData,
    hasClass: hasClass,
    addClass: addClass,
    hexToRgb: hexToRgb,
    rgbaColor: rgbaColor,
    getColor: getColor,
    getColors: getColors,
    getSubtleColors: getSubtleColors,
    getGrays: getGrays,
    getOffset: getOffset,
    isScrolledIntoView: isScrolledIntoView,
    getBreakpoint: getBreakpoint,
    setCookie: setCookie,
    getCookie: getCookie,
    settings: settings,
    getItemFromStore: getItemFromStore,
    setItemToStore: setItemToStore,
    getStoreSpace: getStoreSpace,
    getDates: getDates,
    getPastDates: getPastDates,
    getRandomNumber: getRandomNumber,
    removeClass: removeClass,
    getSystemTheme: getSystemTheme,
    isDark: isDark
};



// Reference
// https://github.com/twbs/bootstrap/issues/11037#issuecomment-274870381

/* -------------------------------------------------------------------------- */
/*                           Open dropdown on hover                           */
/* -------------------------------------------------------------------------- */

var dropdownOnHover = function dropdownOnHover() {
    var navbarArea = document.querySelector('[data-top-nav-dropdowns]');
    if (navbarArea) {
        navbarArea.addEventListener('mouseover', function(e) {
            if (e.target.className.includes('dropdown-toggle') && window.innerWidth > 992) {
                var dropdownInstance = new window.bootstrap.Dropdown(e.target);
                dropdownInstance._element.classList.add('show');
                dropdownInstance._menu.classList.add('show');
                dropdownInstance._menu.setAttribute('data-bs-popper', 'none');
                e.target.parentNode.addEventListener('mouseleave', function() {
                    dropdownInstance.hide();
                });
            }
        });
    }
};

/*-----------------------------------------------
|   Dropzone
-----------------------------------------------*/

window.Dropzone ? window.Dropzone.autoDiscover = false : '';
var dropzoneInit = function dropzoneInit() {
    var merge = window._.merge;
    var Selector = {
        DROPZONE: '[data-dropzone]',
        DZ_ERROR_MESSAGE: '.dz-error-message',
        DZ_PREVIEW: '.dz-preview',
        DZ_PROGRESS: '.dz-preview .dz-preview-cover .dz-progress',
        DZ_PREVIEW_COVER: '.dz-preview .dz-preview-cover'
    };
    var ClassName = {
        DZ_FILE_PROCESSING: 'dz-file-processing',
        DZ_FILE_COMPLETE: 'dz-file-complete',
        DZ_COMPLETE: 'dz-complete',
        DZ_PROCESSING: 'dz-processing'
    };
    var DATA_KEY = {
        OPTIONS: 'options'
    };
    var Events = {
        ADDED_FILE: 'addedfile',
        REMOVED_FILE: 'removedfile',
        COMPLETE: 'complete'
    };
    var dropzones = document.querySelectorAll(Selector.DROPZONE);
    !!dropzones.length && dropzones.forEach(function(item) {
        var userOptions = utils.getData(item, DATA_KEY.OPTIONS);
        userOptions = userOptions || {};
        var data = userOptions.data ? userOptions.data : {};
        var options = merge({
            url: '/assets/php/',
            addRemoveLinks: false,
            previewsContainer: item.querySelector(Selector.DZ_PREVIEW),
            previewTemplate: item.querySelector(Selector.DZ_PREVIEW).innerHTML,
            thumbnailWidth: null,
            thumbnailHeight: null,
            maxFilesize: 20,
            autoProcessQueue: false,
            filesizeBase: 1000,
            init: function init() {
                var thisDropzone = this;
                if (data.length) {
                    data.forEach(function(v) {
                        var mockFile = {
                            name: v.name,
                            size: v.size
                        };
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "".concat(v.url, "/").concat(v.name));
                    });
                }
                thisDropzone.on(Events.ADDED_FILE, function addedfile() {
                    if ('maxFiles' in userOptions) {
                        if (userOptions.maxFiles === 1 && item.querySelectorAll(Selector.DZ_PREVIEW_COVER).length > 1) {
                            item.querySelector(Selector.DZ_PREVIEW_COVER).remove();
                        }
                        if (userOptions.maxFiles === 1 && this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    }
                });
            },
            error: function error(file, message) {
                if (file.previewElement) {
                    file.previewElement.classList.add('dz-error');
                    if (typeof message !== 'string' && message.error) {
                        message = message.error;
                    }
                    var errorNodes = Array.from(file.previewElement.querySelectorAll('[data-dz-errormessage]'));
                    errorNodes.forEach(function(node) {
                        node.textContent = message;
                    });
                }
            }
        }, userOptions);
        item.querySelector(Selector.DZ_PREVIEW).innerHTML = '';
        var dropzone = new window.Dropzone(item, options);
        dropzone.on(Events.ADDED_FILE, function() {
            if (item.querySelector(Selector.DZ_PREVIEW_COVER)) {
                item.querySelector(Selector.DZ_PREVIEW_COVER).classList.remove(ClassName.DZ_FILE_COMPLETE);
            }
            item.classList.add(ClassName.DZ_FILE_PROCESSING);
        });
        dropzone.on(Events.REMOVED_FILE, function() {
            if (item.querySelector(Selector.DZ_PREVIEW_COVER)) {
                item.querySelector(Selector.DZ_PREVIEW_COVER).classList.remove(ClassName.DZ_PROCESSING);
            }
            item.classList.add(ClassName.DZ_FILE_COMPLETE);
        });
        dropzone.on(Events.COMPLETE, function() {
            if (item.querySelector(Selector.DZ_PREVIEW_COVER)) {
                item.querySelector(Selector.DZ_PREVIEW_COVER).classList.remove(ClassName.DZ_PROCESSING);
            }
            item.classList.add(ClassName.DZ_FILE_COMPLETE);
        });
    });
};



/* -------------------------------------------------------------------------- */
/*                             Navbar Combo Layout                            */
/* -------------------------------------------------------------------------- */

var navbarComboInit = function navbarComboInit() {
    var Selector = {
        NAVBAR_VERTICAL: '.navbar-vertical',
        NAVBAR_TOP_COMBO: '[data-navbar-top="combo"]',
        COLLAPSE: '.collapse',
        DATA_MOVE_CONTAINER: '[data-move-container]',
        NAVBAR_NAV: '.navbar-nav',
        NAVBAR_VERTICAL_DIVIDER: '.navbar-vertical-divider'
    };
    var ClassName = {
        FLEX_COLUMN: 'flex-column'
    };
    var navbarVertical = document.querySelector(Selector.NAVBAR_VERTICAL);
    var navbarTopCombo = document.querySelector(Selector.NAVBAR_TOP_COMBO);
    var moveNavContent = function moveNavContent(windowWidth) {
        var navbarVerticalBreakpoint = utils.getBreakpoint(navbarVertical);
        var navbarTopBreakpoint = utils.getBreakpoint(navbarTopCombo);
        if (windowWidth < navbarTopBreakpoint) {
            var navbarCollapse = navbarTopCombo.querySelector(Selector.COLLAPSE);
            var navbarTopContent = navbarCollapse.innerHTML;
            if (navbarTopContent) {
                var targetID = utils.getData(navbarTopCombo, 'move-target');
                var targetElement = document.querySelector(targetID);
                navbarCollapse.innerHTML = '';
                targetElement.insertAdjacentHTML('afterend', "\n            <div data-move-container>\n              <div class='navbar-vertical-divider'>\n                <hr class='navbar-vertical-hr' />\n              </div>\n              ".concat(navbarTopContent, "\n            </div>\n          "));
                if (navbarVerticalBreakpoint < navbarTopBreakpoint) {
                    var navbarNav = document.querySelector(Selector.DATA_MOVE_CONTAINER).querySelector(Selector.NAVBAR_NAV);
                    utils.addClass(navbarNav, ClassName.FLEX_COLUMN);
                }
            }
        } else {
            var moveableContainer = document.querySelector(Selector.DATA_MOVE_CONTAINER);
            if (moveableContainer) {
                var _navbarNav = moveableContainer.querySelector(Selector.NAVBAR_NAV);
                utils.hasClass(_navbarNav, ClassName.FLEX_COLUMN) && _navbarNav.classList.remove(ClassName.FLEX_COLUMN);
                moveableContainer.querySelector(Selector.NAVBAR_VERTICAL_DIVIDER).remove();
                navbarTopCombo.querySelector(Selector.COLLAPSE).innerHTML = moveableContainer.innerHTML;
                moveableContainer.remove();
            }
        }
    };
    moveNavContent(window.innerWidth);
    utils.resize(function() {
        return moveNavContent(window.innerWidth);
    });
};

/* -------------------------------------------------------------------------- */
/*                         Navbar Darken on scroll                        */
/* -------------------------------------------------------------------------- */
var navbarDarkenOnScroll = function navbarDarkenOnScroll() {
    var Selector = {
        NAVBAR: '[data-navbar-darken-on-scroll]',
        NAVBAR_COLLAPSE: '.navbar-collapse',
        NAVBAR_TOGGLER: '.navbar-toggler'
    };
    var ClassNames = {
        COLLAPSED: 'collapsed'
    };
    var Events = {
        SCROLL: 'scroll',
        SHOW_BS_COLLAPSE: 'show.bs.collapse',
        HIDE_BS_COLLAPSE: 'hide.bs.collapse',
        HIDDEN_BS_COLLAPSE: 'hidden.bs.collapse'
    };
    var DataKey = {
        NAVBAR_DARKEN_ON_SCROLL: 'navbar-darken-on-scroll'
    };
    var navbar = document.querySelector(Selector.NAVBAR);

    function removeNavbarBgClass() {
        navbar.classList.remove('bg-dark');
        navbar.classList.remove('bg-100');
    }
    var toggleThemeClass = function toggleThemeClass(theme) {
        if (theme === 'dark') {
            navbar.classList.remove('navbar-dark');
            navbar.classList.add('navbar-light');
        } else {
            navbar.classList.remove('navbar-light');
            navbar.classList.add('navbar-dark');
        }
    };

    function getBgClassName(name, defaultColorName) {
        var parent = document.documentElement;
        var allColors = _objectSpread(_objectSpread({}, utils.getColors(parent)), utils.getGrays(parent));
        var colorName = Object.keys(allColors).includes(name) ? name : defaultColorName;
        var color = allColors[colorName];
        var bgClassName = "bg-".concat(colorName);
        return {
            color: color,
            bgClassName: bgClassName
        };
    }
    if (navbar) {
        var theme = utils.isDark();
        var defaultColorName = theme === 'dark' ? '100' : 'dark';
        var name = utils.getData(navbar, DataKey.NAVBAR_DARKEN_ON_SCROLL);
        toggleThemeClass(theme);
        var themeController = document.body;
        themeController.addEventListener('clickControl', function(_ref11) {
            var _ref11$detail = _ref11.detail,
                control = _ref11$detail.control,
                value = _ref11$detail.value;
            if (control === 'theme') {
                toggleThemeClass(value);
                defaultColorName = value === 'dark' ? '100' : 'dark';
                if (navbar.classList.contains('bg-dark') || navbar.classList.contains('bg-100')) {
                    removeNavbarBgClass();
                    navbar.classList.add(getBgClassName(name, defaultColorName).bgClassName);
                }
            }
        });
        var windowHeight = window.innerHeight;
        var html = document.documentElement;
        var navbarCollapse = navbar.querySelector(Selector.NAVBAR_COLLAPSE);
        var colorRgb = utils.hexToRgb(getBgClassName(name, defaultColorName).color);
        var _window$getComputedSt = window.getComputedStyle(navbar),
            backgroundImage = _window$getComputedSt.backgroundImage;
        var transition = 'background-color 0.35s ease';
        navbar.style.backgroundImage = 'none';
        // Change navbar background color on scroll
        window.addEventListener(Events.SCROLL, function() {
            var scrollTop = html.scrollTop;
            var alpha = scrollTop / windowHeight * 2;
            alpha >= 1 && (alpha = 1);
            navbar.style.backgroundColor = "rgba(".concat(colorRgb[0], ", ").concat(colorRgb[1], ", ").concat(colorRgb[2], ", ").concat(alpha, ")");
            navbar.style.backgroundImage = alpha > 0 || utils.hasClass(navbarCollapse, 'show') ? backgroundImage : 'none';
        });
        window.addEventListener('resize', function() {
            if (navbarCollapse.classList.contains('show')) {
                navbar.classList.add(getBgClassName(name, defaultColorName).bgClassName);
            }
        });

        // Toggle bg class on window resize
        utils.resize(function() {
            var breakPoint = utils.getBreakpoint(navbar);
            if (window.innerWidth > breakPoint) {
                removeNavbarBgClass();
                navbar.style.backgroundImage = html.scrollTop ? backgroundImage : 'none';
                navbar.style.transition = 'none';
            } else if (utils.hasClass(navbar.querySelector(Selector.NAVBAR_TOGGLER), ClassNames.COLLAPSED)) {
                removeNavbarBgClass();
                navbar.style.backgroundImage = backgroundImage;
            }
            if (window.innerWidth <= breakPoint) {
                navbar.style.transition = utils.hasClass(navbarCollapse, 'show') ? transition : 'none';
            }
        });
        navbarCollapse.addEventListener(Events.SHOW_BS_COLLAPSE, function() {
            navbar.classList.add(getBgClassName(name, defaultColorName).bgClassName);
            navbar.style.backgroundImage = backgroundImage;
            navbar.style.transition = transition;
        });
        navbarCollapse.addEventListener(Events.HIDE_BS_COLLAPSE, function() {
            removeNavbarBgClass();
            !html.scrollTop && (navbar.style.backgroundImage = 'none');
        });
        navbarCollapse.addEventListener(Events.HIDDEN_BS_COLLAPSE, function() {
            navbar.style.transition = 'none';
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                                 Navbar Top                                 */
/* -------------------------------------------------------------------------- */

var navbarTopDropShadow = function navbarTopDropShadow() {
    var Selector = {
        NAVBAR: '.navbar:not(.navbar-vertical)',
        NAVBAR_VERTICAL: '.navbar-vertical',
        NAVBAR_VERTICAL_CONTENT: '.navbar-vertical-content',
        NAVBAR_VERTICAL_COLLAPSE: 'navbarVerticalCollapse'
    };
    var ClassNames = {
        NAVBAR_GLASS_SHADOW: 'navbar-glass-shadow',
        SHOW: 'show'
    };
    var Events = {
        SCROLL: 'scroll',
        SHOW_BS_COLLAPSE: 'show.bs.collapse',
        HIDDEN_BS_COLLAPSE: 'hidden.bs.collapse'
    };
    var navDropShadowFlag = true;
    var $navbar = document.querySelector(Selector.NAVBAR);
    var $navbarVertical = document.querySelector(Selector.NAVBAR_VERTICAL);
    var $navbarVerticalContent = document.querySelector(Selector.NAVBAR_VERTICAL_CONTENT);
    var $navbarVerticalCollapse = document.getElementById(Selector.NAVBAR_VERTICAL_COLLAPSE);
    var html = document.documentElement;
    var breakPoint = utils.getBreakpoint($navbarVertical);
    var setDropShadow = function setDropShadow($elem) {
        if ($elem.scrollTop > 0 && navDropShadowFlag) {
            $navbar && $navbar.classList.add(ClassNames.NAVBAR_GLASS_SHADOW);
        } else {
            $navbar && $navbar.classList.remove(ClassNames.NAVBAR_GLASS_SHADOW);
        }
    };
    setDropShadow(html);
    window.addEventListener(Events.SCROLL, function() {
        setDropShadow(html);
    });
    if ($navbarVerticalContent) {
        $navbarVerticalContent.addEventListener(Events.SCROLL, function() {
            if (window.outerWidth < breakPoint) {
                navDropShadowFlag = true;
                setDropShadow($navbarVerticalContent);
            }
        });
    }
    if ($navbarVerticalCollapse) {
        $navbarVerticalCollapse.addEventListener(Events.SHOW_BS_COLLAPSE, function() {
            if (window.outerWidth < breakPoint) {
                navDropShadowFlag = false;
                setDropShadow(html);
            }
        });
    }
    if ($navbarVerticalCollapse) {
        $navbarVerticalCollapse.addEventListener(Events.HIDDEN_BS_COLLAPSE, function() {
            if (utils.hasClass($navbarVerticalCollapse, ClassNames.SHOW) && window.outerWidth < breakPoint) {
                navDropShadowFlag = false;
            } else {
                navDropShadowFlag = true;
            }
            setDropShadow(html);
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                               Navbar Vertical                              */
/* -------------------------------------------------------------------------- */

var handleNavbarVerticalCollapsed = function handleNavbarVerticalCollapsed() {
    var Selector = {
        HTML: 'body',
        NAVBAR_VERTICAL_TOGGLE: '.navbar-vertical-toggle',
        NAVBAR_VERTICAL_COLLAPSE: '.navbar-vertical .navbar-collapse',
        ECHART_RESPONSIVE: '[data-echart-responsive]'
    };
    var Events = {
        CLICK: 'click',
        MOUSE_OVER: 'mouseover',
        MOUSE_LEAVE: 'mouseleave',
        NAVBAR_VERTICAL_TOGGLE: 'navbar.vertical.toggle'
    };
    var ClassNames = {
        NAVBAR_VERTICAL_COLLAPSED: 'navbar-vertical-collapsed',
        NAVBAR_VERTICAL_COLLAPSED_HOVER: 'navbar-vertical-collapsed-hover'
    };
    var navbarVerticalToggle = document.querySelector(Selector.NAVBAR_VERTICAL_TOGGLE);
    var html = document.querySelector(Selector.HTML);
    var navbarVerticalCollapse = document.querySelector(Selector.NAVBAR_VERTICAL_COLLAPSE);
    if (navbarVerticalToggle) {
        navbarVerticalToggle.addEventListener(Events.CLICK, function(e) {
            navbarVerticalToggle.blur();
            html.classList.toggle(ClassNames.NAVBAR_VERTICAL_COLLAPSED);

            // Set collapse state on localStorage
            var isNavbarVerticalCollapsed = utils.getItemFromStore('isNavbarVerticalCollapsed');
            utils.setItemToStore('isNavbarVerticalCollapsed', !isNavbarVerticalCollapsed);
            var event = new CustomEvent(Events.NAVBAR_VERTICAL_TOGGLE);
            e.currentTarget.dispatchEvent(event);
        });
    }
    if (navbarVerticalCollapse) {
        navbarVerticalCollapse.addEventListener(Events.MOUSE_OVER, function() {
            if (utils.hasClass(html, ClassNames.NAVBAR_VERTICAL_COLLAPSED)) {
                html.classList.add(ClassNames.NAVBAR_VERTICAL_COLLAPSED_HOVER);
            }
        });
        navbarVerticalCollapse.addEventListener(Events.MOUSE_LEAVE, function() {
            if (utils.hasClass(html, ClassNames.NAVBAR_VERTICAL_COLLAPSED_HOVER)) {
                html.classList.remove(ClassNames.NAVBAR_VERTICAL_COLLAPSED_HOVER);
            }
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                                   Popover                                  */
/* -------------------------------------------------------------------------- */

var popoverInit = function popoverInit() {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new window.bootstrap.Popover(popoverTriggerEl);
    });
};

/* -------------------------------------------------------------------------- */
/*                         Bootstrap Animated Progress                        */
/* -------------------------------------------------------------------------- */

var progressAnimationToggle = function progressAnimationToggle() {
    var animatedProgress = document.querySelectorAll('[data-progress-animation]');
    animatedProgress.forEach(function(progress) {
        progress.addEventListener('click', function(e) {
            var progressID = utils.getData(e.currentTarget, 'progressAnimation');
            var $progress = document.getElementById(progressID);
            $progress.classList.toggle('progress-bar-animated');
        });
    });
};


/* -------------------------------------------------------------------------- */
/*                                 Scrollbars                                 */
/* -------------------------------------------------------------------------- */
// import utils from './utils';

var scrollInit = function scrollInit() {
    var dropdownElements = Array.from(document.querySelectorAll('[data-hide-on-body-scroll]'));
    if (window.innerWidth < 1200) {
        window.addEventListener('scroll', function() {
            dropdownElements.forEach(function(dropdownElement) {
                var instanceEl = window.bootstrap.Dropdown.getInstance(dropdownElement);
                instanceEl && instanceEl.hide();
            });
        });
    }
};




/*-----------------------------------------------
|   DomNode
-----------------------------------------------*/
var DomNode = /*#__PURE__*/ function() {
    function DomNode(node) {
        _classCallCheck(this, DomNode);
        this.node = node;
    }
    return _createClass(DomNode, [{
        key: "addClass",
        value: function addClass(className) {
            this.isValidNode() && this.node.classList.add(className);
        }
    }, {
        key: "removeClass",
        value: function removeClass(className) {
            this.isValidNode() && this.node.classList.remove(className);
        }
    }, {
        key: "toggleClass",
        value: function toggleClass(className) {
            this.isValidNode() && this.node.classList.toggle(className);
        }
    }, {
        key: "hasClass",
        value: function hasClass(className) {
            this.isValidNode() && this.node.classList.contains(className);
        }
    }, {
        key: "data",
        value: function data(key) {
            if (this.isValidNode()) {
                try {
                    return JSON.parse(this.node.dataset[this.camelize(key)]);
                } catch (e) {
                    return this.node.dataset[this.camelize(key)];
                }
            }
            return null;
        }
    }, {
        key: "attr",
        value: function attr(name) {
            return this.isValidNode() && this.node[name];
        }
    }, {
        key: "setAttribute",
        value: function setAttribute(name, value) {
            this.isValidNode() && this.node.setAttribute(name, value);
        }
    }, {
        key: "removeAttribute",
        value: function removeAttribute(name) {
            this.isValidNode() && this.node.removeAttribute(name);
        }
    }, {
        key: "setProp",
        value: function setProp(name, value) {
            this.isValidNode() && (this.node[name] = value);
        }
    }, {
        key: "on",
        value: function on(event, cb) {
            this.isValidNode() && this.node.addEventListener(event, cb);
        }
    }, {
        key: "isValidNode",
        value: function isValidNode() {
            return !!this.node;
        }

        // eslint-disable-next-line class-methods-use-this
    }, {
        key: "camelize",
        value: function camelize(str) {
            var text = str.replace(/[-_\s.]+(.)?/g, function(_, c) {
                return c ? c.toUpperCase() : '';
            });
            return "".concat(text.substr(0, 1).toLowerCase()).concat(text.substr(1));
        }
    }]);
}();


/* -------------------------------------------------------------------------- */
/*                                   Treeview                                  */
/* -------------------------------------------------------------------------- */
var treeviewInit = function treeviewInit() {
    var Events = {
        CHANGE: 'change',
        SHOW_BS_COLLAPSE: 'show.bs.collapse',
        HIDE_BS_COLLAPSE: 'hide.bs.collapse'
    };
    var Selector = {
        TREEVIEW_ROW: '.treeview > li > .treeview-row,.treeview-list.collapse-show > li > .treeview-row',
        TREEVIEW: '.treeview',
        TREEVIEW_LIST: '.treeview-list',
        TOGGLE_ELEMENT: "[data-bs-toggle='collapse']",
        INPUT: 'input',
        TREEVIEW_LIST_ITEM: '.treeview-list-item',
        CHILD_SELECTOR: ':scope > li > .collapse.collapse-show'
    };
    var ClassName = {
        TREEVIEW: 'treeview',
        TREEVIEW_LIST: 'treeview-list',
        TREEVIEW_BORDER: 'treeview-border',
        TREEVIEW_BORDER_TRANSPARENT: 'treeview-border-transparent',
        COLLAPSE_SHOW: 'collapse-show',
        COLLAPSE_HIDDEN: 'collapse-hidden',
        TREEVIEW_ROW: 'treeview-row',
        TREEVIEW_ROW_ODD: 'treeview-row-odd',
        TREEVIEW_ROW_EVEN: 'treeview-row-even'
    };
    var treeviews = document.querySelectorAll(Selector.TREEVIEW);
    var makeStriped = function makeStriped(treeview) {
        var tags = Array.from(treeview.querySelectorAll(Selector.TREEVIEW_ROW));
        var uTags = tags.filter(function(tag) {
            var result = true;
            while (tag.parentElement) {
                if (tag.parentElement.classList.contains(ClassName.COLLAPSE_HIDDEN)) {
                    result = false;
                    break;
                }
                tag = tag.parentElement;
            }
            return result;
        });
        uTags.forEach(function(tag, index) {
            if (index % 2 === 0) {
                tag.classList.add(ClassName.TREEVIEW_ROW_EVEN);
                tag.classList.remove(ClassName.TREEVIEW_ROW_ODD);
            } else {
                tag.classList.add(ClassName.TREEVIEW_ROW_ODD);
                tag.classList.remove(ClassName.TREEVIEW_ROW_EVEN);
            }
        });
    };
    if (treeviews.length) {
        treeviews.forEach(function(treeview) {
            var options = utils.getData(treeview, 'options');
            var striped = options === null || options === void 0 ? void 0 : options.striped;
            var select = options === null || options === void 0 ? void 0 : options.select;
            if (striped) {
                makeStriped(treeview);
            }
            var collapseElementList = Array.from(treeview.querySelectorAll(Selector.TREEVIEW_LIST));
            var collapseListItem = Array.from(treeview.querySelectorAll(Selector.TREEVIEW_LIST_ITEM));
            collapseListItem.forEach(function(item) {
                var wholeRow = document.createElement('div');
                wholeRow.setAttribute('class', ClassName.TREEVIEW_ROW);
                item.prepend(wholeRow);
            });
            collapseElementList.forEach(function(collapse) {
                var collapseId = collapse.id;
                if (!striped) {
                    collapse.classList.add(ClassName.TREEVIEW_BORDER);
                }
                collapse.addEventListener(Events.SHOW_BS_COLLAPSE, function(e) {
                    e.target.classList.remove(ClassName.COLLAPSE_HIDDEN);
                    e.target.classList.add(ClassName.COLLAPSE_SHOW);
                    if (striped) {
                        makeStriped(treeview);
                    } else {
                        e.composedPath()[2].classList.add(ClassName.TREEVIEW_BORDER_TRANSPARENT);
                    }
                });
                collapse.addEventListener(Events.HIDE_BS_COLLAPSE, function(e) {
                    e.target.classList.add(ClassName.COLLAPSE_HIDDEN);
                    e.target.classList.remove(ClassName.COLLAPSE_SHOW);
                    if (striped) {
                        makeStriped(treeview);
                    } else {
                        var childs = e.composedPath()[2].querySelectorAll(Selector.CHILD_SELECTOR);
                        if (!e.composedPath()[2].classList.contains(ClassName.TREEVIEW) && childs.length === 0) {
                            e.composedPath()[2].classList.remove(ClassName.TREEVIEW_BORDER_TRANSPARENT);
                        }
                    }
                });
                if (collapse.dataset.show === 'true') {
                    var parents = [collapse];
                    while (collapse.parentElement) {
                        if (collapse.parentElement.classList.contains(ClassName.TREEVIEW_LIST)) {
                            parents.unshift(collapse.parentElement);
                        }
                        collapse = collapse.parentElement;
                    }
                    parents.forEach(function(collapseEl) {
                        // eslint-disable-next-line no-new
                        new window.bootstrap.Collapse(collapseEl, {
                            show: true
                        });
                    });
                }
                if (select) {
                    var inputElement = treeview.querySelector("input[data-target='#".concat(collapseId, "']"));
                    inputElement.addEventListener(Events.CHANGE, function(e) {
                        var childInputElements = Array.from(treeview.querySelector("#".concat(collapseId)).querySelectorAll(Selector.INPUT));
                        childInputElements.forEach(function(input) {
                            input.checked = e.target.checked;
                        });
                    });
                }
            });
        });
    }
};

/* -------------------------------------------------------------------------- */

/*                               Ratings                               */

/* -------------------------------------------------------------------------- */

var ratingInit = function ratingInit() {
    var raters = document.querySelectorAll('[data-rater]');
    raters.forEach(function(rater) {
        var options = _objectSpread({
            reverse: utils.getItemFromStore('isRTL'),
            starSize: 32,
            step: 0.5,
            element: rater,
            rateCallback: function rateCallback(rating, done) {
                this.setRating(rating);
                done();
            }
        }, utils.getData(rater, 'rater'));
        return window.raterJs(options);
    });
};

// var isRTL = JSON.parse(localStorage.getItem('isRTL'));
//     if (isRTL) {
//         var linkDefault = document.getElementById('style-default');
//         var userLinkDefault = document.getElementById('user-style-default');
//         linkDefault.setAttribute('disabled', true);
//         userLinkDefault.setAttribute('disabled', true);
//         document.querySelector('html').setAttribute('dir', 'rtl');
//     } else {
//         var linkRTL = document.getElementById('style-rtl');
//         var userLinkRTL = document.getElementById('user-style-rtl');
//         linkRTL.setAttribute('disabled', true);
//         userLinkRTL.setAttribute('disabled', true);
//     }

/* -------------------------------------------------------------------------- */
/*                              Config                                        */
/* -------------------------------------------------------------------------- */
var CONFIG = {
    isNavbarVerticalCollapsed: true,
    theme: 'auto',
    isRTL: false,
    isFluid: true,
    navbarStyle: 'transparent',
    navbarPosition: 'vertical'
};
Object.keys(CONFIG).forEach(function(key) {
    if (localStorage.getItem(key) === null) {
        localStorage.setItem(key, CONFIG[key]);
    }
});

// if (JSON.parse(localStorage.getItem('isNavbarVerticalCollapsed'))) {
//     document.documentElement.classList.add('navbar-vertical-collapsed');
// }
// if (localStorage.getItem('theme') === 'dark') {
//     document.documentElement.setAttribute('data-bs-theme', 'dark');
// } else if (localStorage.getItem('theme') === 'auto') {
//     document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
// }
function applyLayoutFromLocalStorage() {
  if (localStorage.getItem('isNavbarVerticalCollapsed') === 'true') {
    document.documentElement.classList.add('navbar-vertical-collapsed');
  } else {
    document.documentElement.classList.remove('navbar-vertical-collapsed');
  }

  const theme = localStorage.getItem('theme');
  if (theme === 'dark') {
    document.documentElement.setAttribute('data-bs-theme', 'dark');
  } else if (theme === 'auto') {
    document.documentElement.setAttribute(
      'data-bs-theme',
      window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    );
  } else {
    document.documentElement.setAttribute('data-bs-theme', 'light');
  }
}


/* -------------------------------------------------------------------------- */
/*                                Theme Control                               */
/* -------------------------------------------------------------------------- */
var initialDomSetup = function initialDomSetup(element) {
    if (!element) return;
    var dataUrlDom = element.querySelector('[data-theme-control = "navbarPosition"]');
    var hasDataUrl = dataUrlDom ? getData(dataUrlDom, 'page-url') : null;
    element.querySelectorAll('[data-theme-control]').forEach(function(el) {
        var inputDataAttributeValue = getData(el, 'theme-control');
        var localStorageValue = getItemFromStore(inputDataAttributeValue);
        if (inputDataAttributeValue === 'navbarStyle' && !hasDataUrl && (getItemFromStore('navbarPosition') === 'top' || getItemFromStore('navbarPosition') === 'double-top')) {
            el.setAttribute('disabled', true);
        }
        if (el.type === 'select-one' && inputDataAttributeValue === 'navbarPosition') {
            el.value = localStorageValue;
        }
        if (el.type === 'checkbox') {
            if (inputDataAttributeValue === 'theme') {
                if (localStorageValue === 'auto' ? getSystemTheme() === 'dark' : localStorageValue === 'dark') {
                    el.setAttribute('checked', true);
                }
            } else {
                localStorageValue && el.setAttribute('checked', true);
            }
        } else if (el.type === 'radio') {
            var isChecked = localStorageValue === el.value;
            isChecked && el.setAttribute('checked', true);
        } else {
            var isActive = localStorageValue === el.value;
            isActive && el.classList.add('active');
        }
    });
};

var changeTheme = function changeTheme(element) {
    element.querySelectorAll('[data-theme-control = "theme"]').forEach(function(el) {
        var inputDataAttributeValue = getData(el, 'theme-control');
        var localStorageValue = getItemFromStore(inputDataAttributeValue);
        if (el.type === 'checkbox') {
            if (localStorageValue === 'auto') {
                getSystemTheme() === 'dark' ? el.checked = true : el.checked = false;
            } else {
                localStorageValue === 'dark' ? el.checked = true : el.checked = false;
            }
        } else if (el.type === 'radio') {
            localStorageValue === el.value ? el.checked = true : el.checked = false;
        } else {
            localStorageValue === el.value ? el.classList.add('active') : el.classList.remove('active');
        }
    });
};
var handleThemeDropdownIcon = function handleThemeDropdownIcon(value) {
    document.querySelectorAll('[data-theme-dropdown-toggle-icon]').forEach(function(el) {
        el.classList.toggle('d-none', value !== getData(el, 'theme-dropdown-toggle-icon'));
    });
};

handleThemeDropdownIcon(getItemFromStore('theme'));
var themeControl = function themeControl() {
    var themeController = new DomNode(document.body);
    var navbarVertical = document.querySelector('.navbar-vertical');
    initialDomSetup(themeController.node);
    themeController.on('click', function(e) {
        var target = new DomNode(e.target);
        if (target.data('theme-control')) {
            var control = target.data('theme-control');
            var value = e.target[e.target.type === 'checkbox' ? 'checked' : 'value'];
            if (control === 'theme') {
                typeof value === 'boolean' && (value = value ? 'dark' : 'light');
            }
            if (control !== 'navbarPosition') {
                Object.prototype.hasOwnProperty.call(CONFIG, control) && setItemToStore(control, value);
                switch (control) {
                    case 'theme':
                        {
                            document.documentElement.setAttribute('data-bs-theme', value === 'auto' ? getSystemTheme() : value);
                            var clickControl = new CustomEvent('clickControl', {
                                detail: {
                                    control: control,
                                    value: value
                                }
                            });
                            e.currentTarget.dispatchEvent(clickControl);
                            changeTheme(themeController.node);
                            break;
                        }
                    case 'navbarStyle':
                        {
                            navbarVertical.classList.remove('navbar-card');
                            navbarVertical.classList.remove('navbar-inverted');
                            navbarVertical.classList.remove('navbar-vibrant');
                            if (value !== 'transparent') {
                                navbarVertical.classList.add("navbar-".concat(value));
                            }
                            break;
                        }
                    case 'reset':
                        {
                            Object.keys(CONFIG).forEach(function(key) {
                                localStorage.setItem(key, CONFIG[key]);
                            });
                            window.location.reload();
                            break;
                        }
                    default:
                        window.location.reload();
                }
            }
        }
    });

    // control navbar position
    themeController.on('change', function(e) {
        var target = new DomNode(e.target);
        if (target.data('theme-control') === 'navbarPosition') {
            Object.prototype.hasOwnProperty.call(CONFIG, 'navbarPosition') && setItemToStore('navbarPosition', e.target.value);
            var pageUrl = getData(target.node.selectedOptions[0], 'page-url');
            pageUrl ? window.location.replace(pageUrl) : window.location.replace(window.location.href.split('#')[0]);
        }
    });
    themeController.on('clickControl', function(_ref13) {
        var _ref13$detail = _ref13.detail,
            control = _ref13$detail.control,
            value = _ref13$detail.value;
        if (control === 'theme') {
            handleThemeDropdownIcon(value);
        }
    });
};


/* -------------------------------------------------------------------------- */
/*                            Theme Initialization                            */
/* -------------------------------------------------------------------------- */

docReady(handleNavbarVerticalCollapsed);
docReady(navbarTopDropShadow);
docReady(popoverInit);
docReady(progressAnimationToggle);
docReady(navbarDarkenOnScroll);
docReady(navbarComboInit);
docReady(dropdownOnHover);
docReady(initialDomSetup);
docReady(themeControl);
docReady(ratingInit);
docReady(applyLayoutFromLocalStorage);

function runAllInits() {
    handleNavbarVerticalCollapsed();
    navbarTopDropShadow();
    popoverInit();
    progressAnimationToggle();
    navbarDarkenOnScroll();
    navbarComboInit();
    dropdownOnHover();
    themeControl();
    initialDomSetup();
    ratingInit();
    applyLayoutFromLocalStorage();
}


document.addEventListener('DOMContentLoaded', runAllInits);

// Livewire navigate 
document.addEventListener('livewire:navigated', runAllInits);
