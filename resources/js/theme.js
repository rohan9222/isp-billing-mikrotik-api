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
    return getComputedStyle(dom).getPropertyValue("--falcon-".concat(name)).trim();
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

/* -------------------------- Chart Initialization -------------------------- */

var newChart = function newChart(chart, config) {
    var ctx = chart.getContext('2d');
    return new window.Chart(ctx, config);
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
    newChart: newChart,
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

var advanceAjaxTableInit = function advanceAjaxTableInit() {
    var togglePaginationButtonDisable = function togglePaginationButtonDisable(button, disabled) {
        var updatedButton = button;
        updatedButton.disabled = disabled;
        updatedButton.classList[disabled ? 'add' : 'remove']('disabled');
    };
    // Selectors
    var table = document.getElementById('advanceAjaxTable');
    if (table) {
        var options = {
            page: 10,
            pagination: {
                item: "<li><button class='page' type='button'></button></li>"
            },
            item: function item(values) {
                var orderId = values.orderId,
                    id = values.id,
                    name = values.name,
                    email = values.email,
                    date = values.date,
                    address = values.address,
                    shippingType = values.shippingType,
                    status = values.status,
                    badge = values.badge,
                    amount = values.amount;
                return "\n          <tr class=\"btn-reveal-trigger\">\n            <td class=\"order py-2 align-middle white-space-nowrap\">\n              <a href=\"https://prium.github.io/falcon/v3.16.0/app/e-commerce/orders/order-details.html\">\n                <strong>".concat(orderId, "</strong>\n              </a>\n              by\n              <strong>").concat(name, "</strong>\n              <br />\n              <a href=\"mailto:").concat(email, "\">").concat(email, "</a>\n            </td>\n            <td class=\"py-2 align-middle\">\n              ").concat(date, "\n            </td>\n            <td class=\"py-2 align-middle white-space-nowrap\">\n              ").concat(address, "\n              <p class=\"mb-0 text-500\">").concat(shippingType, "</p>\n            </td>\n            <td class=\"py-2 align-middle text-center fs-9 white-space-nowrap\">\n              <span class=\"badge rounded-pill d-block badge-subtle-").concat(badge.type, "\">\n                ").concat(status, "\n                <span class=\"ms-1 ").concat(badge.icon, "\" data-fa-transform=\"shrink-2\"></span>\n              </span>\n            </td>\n            <td class=\"py-2 align-middle text-end fs-9 fw-medium\">\n              ").concat(amount, "\n            </td>\n            <td class=\"py-2 align-middle white-space-nowrap text-end\">\n              <div class=\"dropstart font-sans-serif position-static d-inline-block\">\n                <button class=\"btn btn-link text-600 btn-sm dropdown-toggle btn-reveal\" type='button' id=\"order-dropdown-").concat(id, "\" data-bs-toggle=\"dropdown\" data-boundary=\"window\" aria-haspopup=\"true\" aria-expanded=\"false\" data-bs-reference=\"parent\">\n                  <span class=\"fas fa-ellipsis-h fs-10\"></span>\n                </button>\n                <div class=\"dropdown-menu dropdown-menu-end border py-2\" aria-labelledby=\"order-dropdown-").concat(id, "\">\n                  <a href=\"#!\" class=\"dropdown-item\">View</a>\n                  <a href=\"#!\" class=\"dropdown-item\">Edit</a>\n                  <a href=\"#!\" class=\"dropdown-item\">Refund</a>\n                  <div class\"dropdown-divider\"></div>\n                  <a href=\"#!\" class=\"dropdown-item text-warning\">Archive</a>\n                  <a href=\"#!\" class=\"dropdown-item text-warning\">Archive</a>\n                </div>\n              </div>\n            </td>\n          </tr>\n        ");
            }
        };
        var paginationButtonNext = table.querySelector('[data-list-pagination="next"]');
        var paginationButtonPrev = table.querySelector('[data-list-pagination="prev"]');
        var viewAll = table.querySelector('[data-list-view="*"]');
        var viewLess = table.querySelector('[data-list-view="less"]');
        var listInfo = table.querySelector('[data-list-info]');
        var listFilter = document.querySelector('[data-list-filter]');
        var orderList = new window.List(table, options, orders);

        // Fallback
        orderList.on('updated', function(item) {
            var fallback = table.querySelector('.fallback') || document.getElementById(options.fallback);
            if (fallback) {
                if (item.matchingItems.length === 0) {
                    fallback.classList.remove('d-none');
                } else {
                    fallback.classList.add('d-none');
                }
            }
        });
        var totalItem = orderList.items.length;
        var itemsPerPage = orderList.page;
        var btnDropdownClose = orderList.listContainer.querySelector('.btn-close');
        var pageQuantity = Math.ceil(totalItem / itemsPerPage);
        var numberOfcurrentItems = orderList.visibleItems.length;
        var pageCount = 1;
        btnDropdownClose && btnDropdownClose.addEventListener('search.close', function() {
            return orderList.fuzzySearch('');
        });
        var updateListControls = function updateListControls() {
            listInfo && (listInfo.innerHTML = "".concat(orderList.i, " to ").concat(numberOfcurrentItems, " of ").concat(totalItem));
            paginationButtonPrev && togglePaginationButtonDisable(paginationButtonPrev, pageCount === 1);
            if (paginationButtonNext) {
                togglePaginationButtonDisable(paginationButtonNext, pageCount === pageQuantity);
            }
            if (pageCount > 1 && pageCount < pageQuantity) {
                togglePaginationButtonDisable(paginationButtonNext, false);
                togglePaginationButtonDisable(paginationButtonPrev, false);
            }
        };
        updateListControls();
        if (paginationButtonNext) {
            paginationButtonNext.addEventListener('click', function(e) {
                e.preventDefault();
                pageCount += 1;
                var nextInitialIndex = orderList.i + itemsPerPage;
                nextInitialIndex <= orderList.size() && orderList.show(nextInitialIndex, itemsPerPage);
                numberOfcurrentItems += orderList.visibleItems.length;
                updateListControls();
            });
        }
        if (paginationButtonPrev) {
            paginationButtonPrev.addEventListener('click', function(e) {
                e.preventDefault();
                pageCount -= 1;
                numberOfcurrentItems -= orderList.visibleItems.length;
                var prevItem = orderList.i - itemsPerPage;
                prevItem > 0 && orderList.show(prevItem, itemsPerPage);
                updateListControls();
            });
        }
        var toggleViewBtn = function toggleViewBtn() {
            viewLess.classList.toggle('d-none');
            viewAll.classList.toggle('d-none');
        };
        if (viewAll) {
            viewAll.addEventListener('click', function() {
                orderList.show(1, totalItem);
                pageQuantity = 1;
                pageCount = 1;
                numberOfcurrentItems = totalItem;
                updateListControls();
                toggleViewBtn();
            });
        }
        if (viewLess) {
            viewLess.addEventListener('click', function() {
                orderList.show(1, itemsPerPage);
                pageQuantity = Math.ceil(totalItem / itemsPerPage);
                pageCount = 1;
                numberOfcurrentItems = orderList.visibleItems.length;
                updateListControls();
                toggleViewBtn();
            });
        }
        if (options.pagination) {
            table.querySelector('.pagination').addEventListener('click', function(e) {
                if (e.target.classList[0] === 'page') {
                    pageCount = Number(e.target.innerText);
                    updateListControls();
                }
            });
        }
        if (options.filter) {
            var key = options.filter.key;
            listFilter.addEventListener('change', function(e) {
                orderList.filter(function(item) {
                    if (e.target.value === '') {
                        return true;
                    }
                    return item.values()[key].toLowerCase().includes(e.target.value.toLowerCase());
                });
            });
        }
    }
};

/* -------------------------------------------------------------------------- */
/*                                  Anchor JS                                 */
/* -------------------------------------------------------------------------- */

// var anchors = new window.AnchorJS();
// anchors.options = {
//     icon: '#'
// };
// anchors.add('[data-anchor]');

/*-----------------------------------------------
|   Bottom Bar Control
-----------------------------------------------*/

var bottomBarInit = function bottomBarInit() {
    var bottomBars = document.querySelectorAll('[data-bottom-bar]');
    var navbarButtons = [document.querySelector('[data-bs-target="#navbarVerticalCollapse"]'), document.querySelector('[data-bs-target="#navbarStandard"]')];
    var isElementInViewport = function isElementInViewport(el) {
        var offsetTop = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
        var rect = el.getBoundingClientRect();
        return rect.bottom > 0 && rect.top > offsetTop && rect.right > 0 && rect.left < (window.innerWidth || document.documentElement.clientWidth) && rect.top < (window.innerHeight || document.documentElement.clientHeight);
    };
    if (bottomBars.length) {
        bottomBars.forEach(function(bar) {
            // get options
            var barOptions = utils.getData(bar, 'bottom-bar');
            var defaultOptions = {
                target: '#bottom-bar-target',
                offsetTop: 0,
                breakPoint: 'lg'
            };
            var _window$_$merge = window._.merge(defaultOptions, barOptions),
                target = _window$_$merge.target,
                offsetTop = _window$_$merge.offsetTop,
                breakPoint = _window$_$merge.breakPoint;

            // select target
            var targetEl = document.getElementById(target);

            // handle Bottombar
            var toggleBottomBar = function toggleBottomBar() {
                if (window.matchMedia("(max-width: ".concat(utils.breakpoints[breakPoint], "px)")).matches) {
                    if (!isElementInViewport(targetEl, offsetTop)) {
                        utils.removeClass(bar, 'hide');
                    } else {
                        utils.addClass(bar, 'hide');
                    }
                }
            };
            window.addEventListener('scroll', toggleBottomBar);
            var toggleBottomBarOnNavbarCollapse = function toggleBottomBarOnNavbarCollapse(el) {
                if (!utils.hasClass(el, 'collapsed')) {
                    utils.addClass(bar, 'hide');
                } else if (!isElementInViewport(targetEl, offsetTop)) {
                    utils.removeClass(bar, 'hide');
                }
            };
            navbarButtons.forEach(function(btn) {
                return btn && btn.addEventListener('click', function() {
                    return toggleBottomBarOnNavbarCollapse(btn);
                });
            });
        });
    }
};

/*-----------------------------------------------
|   Bulk Select
-----------------------------------------------*/

var elementMap = new Map();
var BulkSelect = /*#__PURE__*/ function() {
    function BulkSelect(element, option) {
        _classCallCheck(this, BulkSelect);
        this.element = element;
        this.option = _objectSpread({
            displayNoneClassName: 'd-none'
        }, option);
        elementMap.set(this.element, this);
    }

    // Static
    return _createClass(BulkSelect, [{
        key: "init",
        value: function init() {
            this.attachNodes();
            this.clickBulkCheckbox();
            this.clickRowCheckbox();
        }
    }, {
        key: "getSelectedRows",
        value: function getSelectedRows() {
            return Array.from(this.bulkSelectRows).filter(function(row) {
                return row.checked;
            }).map(function(row) {
                return utils.getData(row, 'bulk-select-row');
            });
        }
    }, {
        key: "attachNodes",
        value: function attachNodes() {
            var _utils$getData = utils.getData(this.element, 'bulk-select'),
                body = _utils$getData.body,
                actions = _utils$getData.actions,
                replacedElement = _utils$getData.replacedElement;
            this.actions = new DomNode(document.getElementById(actions));
            this.replacedElement = new DomNode(document.getElementById(replacedElement));
            this.bulkSelectRows = document.getElementById(body).querySelectorAll('[data-bulk-select-row]');
        }
    }, {
        key: "attachRowNodes",
        value: function attachRowNodes(elms) {
            this.bulkSelectRows = elms;
        }
    }, {
        key: "clickBulkCheckbox",
        value: function clickBulkCheckbox() {
            var _this = this;
            // Handle click event in bulk checkbox
            this.element.addEventListener('click', function() {
                if (_this.element.indeterminate === 'indeterminate') {
                    _this.actions.addClass(_this.option.displayNoneClassName);
                    _this.replacedElement.removeClass(_this.option.displayNoneClassName);
                    _this.removeBulkCheck();
                    _this.bulkSelectRows.forEach(function(el) {
                        var rowCheck = new DomNode(el);
                        rowCheck.setProp('checked', false);
                        rowCheck.setAttribute('checked', false);
                    });
                    return;
                }
                _this.toggleDisplay();
                _this.bulkSelectRows.forEach(function(el) {
                    el.checked = _this.element.checked;
                });
                if (_this.element.checked) {
                    _this.actions.removeClass(_this.option.displayNoneClassName);
                    _this.replacedElement.addClass(_this.option.displayNoneClassName);
                } else {
                    _this.actions.addClass(_this.option.displayNoneClassName);
                    _this.replacedElement.removeClass(_this.option.displayNoneClassName);
                }
            });
        }
    }, {
        key: "clickRowCheckbox",
        value: function clickRowCheckbox() {
            var _this2 = this;
            // Handle click event in checkbox of each row
            this.bulkSelectRows.forEach(function(el) {
                var rowCheck = new DomNode(el);
                rowCheck.on('click', function() {
                    if (_this2.element.indeterminate !== 'indeterminate') {
                        _this2.element.indeterminate = true;
                        _this2.element.setAttribute('indeterminate', 'indeterminate');
                        _this2.element.checked = true;
                        _this2.element.setAttribute('checked', true);
                        _this2.actions.removeClass(_this2.option.displayNoneClassName);
                        _this2.replacedElement.addClass(_this2.option.displayNoneClassName);
                    }
                    if (_toConsumableArray(_this2.bulkSelectRows).every(function(element) {
                            return element.checked;
                        })) {
                        _this2.element.indeterminate = false;
                        _this2.element.setAttribute('indeterminate', false);
                    }
                    if (_toConsumableArray(_this2.bulkSelectRows).every(function(element) {
                            return !element.checked;
                        })) {
                        _this2.removeBulkCheck();
                        _this2.toggleDisplay();
                    }
                });
            });
        }
    }, {
        key: "removeBulkCheck",
        value: function removeBulkCheck() {
            this.element.indeterminate = false;
            this.element.removeAttribute('indeterminate');
            this.element.checked = false;
            this.element.setAttribute('checked', false);
        }
    }, {
        key: "toggleDisplay",
        value: function toggleDisplay() {
            this.actions.toggleClass(this.option.displayNoneClassName);
            this.replacedElement.toggleClass(this.option.displayNoneClassName);
        }
    }], [{
        key: "getInstance",
        value: function getInstance(element) {
            if (elementMap.has(element)) {
                return elementMap.get(element);
            }
            return null;
        }
    }]);
}();

function bulkSelectInit() {
    var bulkSelects = document.querySelectorAll('[data-bulk-select');
    if (bulkSelects.length) {
        bulkSelects.forEach(function(el) {
            var bulkSelect = new BulkSelect(el);
            bulkSelect.init();
        });
    }
    var selectedRowsBtn = document.querySelector('[data-selected-rows]');
    var selectedRows = document.getElementById('selectedRows');
    if (selectedRowsBtn) {
        var bulkSelectEl = document.getElementById('bulk-select-example');
        var bulkSelectInstance = window.BulkSelect.getInstance(bulkSelectEl);
        selectedRowsBtn.addEventListener('click', function() {
            // console.log(bulkSelectInstance);
            selectedRows.innerHTML = JSON.stringify(bulkSelectInstance.getSelectedRows(), undefined, 2);
        });
    }
}
window.BulkSelect = BulkSelect;

/*-----------------------------------------------
|   Chat
-----------------------------------------------*/
var chatInit = function chatInit() {
    var Events = {
        CLICK: 'click',
        SHOWN_BS_TAB: 'shown.bs.tab',
        KEYUP: 'keyup',
        EMOJI: 'emoji'
    };
    var Selector = {
        CHAT_SIDEBAR: '.chat-sidebar',
        CHAT_CONTACT: '.chat-contact',
        CHAT_CONTENT_SCROLL_AREA: '.chat-content-scroll-area',
        CHAT_CONTENT_SCROLL_AREA_ACTIVE: '.card-chat-pane.active .chat-content-scroll-area',
        CHAT_EMOJIAREA: '.chat-editor-area .emojiarea-editor',
        BTN_SEND: '.btn-send',
        EMOJIEAREA_EDITOR: '.emojiarea-editor',
        BTN_INFO: '.btn-chat-info',
        CONVERSATION_INFO: '.conversation-info',
        CONTACTS_LIST_SHOW: '.contacts-list-show'
    };
    var ClassName = {
        UNREAD_MESSAGE: 'unread-message',
        TEXT_PRIMARY: 'text-primary',
        SHOW: 'show'
    };
    var DATA_KEY = {
        INDEX: 'index'
    };
    var $chatSidebar = document.querySelector(Selector.CHAT_SIDEBAR);
    var $chatContact = document.querySelectorAll(Selector.CHAT_CONTACT);
    var $chatEmojiarea = document.querySelector(Selector.CHAT_EMOJIAREA);
    var $btnSend = document.querySelector(Selector.BTN_SEND);
    var $currentChatArea = document.querySelector(Selector.CHAT_CONTENT_SCROLL_AREA);

    // Set scrollbar position
    var setScrollbarPosition = function setScrollbarPosition($chatArea) {
        if ($chatArea) {
            var scrollArea = $chatArea;
            scrollArea.scrollTop = $chatArea.scrollHeight;
        }
    };
    setTimeout(function() {
        setScrollbarPosition($currentChatArea);
    }, 700);
    document.querySelectorAll(Selector.CHAT_CONTACT).forEach(function(el) {
        el.addEventListener(Events.CLICK, function(e) {
            var $this = e.currentTarget;
            $this.classList.add('active');
            // Hide contact list sidebar on responsive
            window.innerWidth < 768 && !e.target.classList.contains('hover-actions') && ($chatSidebar.style.left = '-100%');

            // Remove unread-message class when read
            $this.classList.contains(ClassName.UNREAD_MESSAGE) && $this.classList.remove(ClassName.UNREAD_MESSAGE);
        });
    });
    $chatContact.forEach(function(el) {
        el.addEventListener(Events.SHOWN_BS_TAB, function() {
            $chatEmojiarea.innerHTML = '';
            $btnSend.classList.remove(ClassName.TEXT_PRIMARY);
            var TargetChatArea = document.querySelector(Selector.CHAT_CONTENT_SCROLL_AREA_ACTIVE);
            setScrollbarPosition(TargetChatArea);
        });
    });

    // change send button color on

    if ($chatEmojiarea) {
        $chatEmojiarea.setAttribute('placeholder', 'Type your message');
        $chatEmojiarea.addEventListener(Events.KEYUP, function(e) {
            if (e.target.textContent.length <= 0) {
                $btnSend.classList.remove(ClassName.TEXT_PRIMARY);
                if (e.target.innerHTML === '<br>') {
                    e.target.innerHTML = '';
                }
            } else {
                $btnSend.classList.add(ClassName.TEXT_PRIMARY);
            }
            var TargetChatArea = document.querySelector(Selector.CHAT_CONTENT_SCROLL_AREA_ACTIVE);
            setScrollbarPosition(TargetChatArea);
        });
    }
    // Open conversation info sidebar
    $chatEmojiarea && document.querySelectorAll(Selector.BTN_INFO).forEach(function(el) {
        el.addEventListener(Events.CLICK, function(e) {
            var $this = e.currentTarget;
            var dataIndex = utils.getData($this, DATA_KEY.INDEX);
            var $info = document.querySelector("".concat(Selector.CONVERSATION_INFO, "[data-").concat(DATA_KEY.INDEX, "='").concat(dataIndex, "']"));
            $info.classList.toggle(ClassName.SHOW);
        });
    });

    // Show contact list sidebar on responsive
    document.querySelectorAll(Selector.CONTACTS_LIST_SHOW).forEach(function(el) {
        el.addEventListener(Events.CLICK, function() {
            $chatSidebar.style.left = 0;
        });
    });

    // Set scrollbar area height on resize
    utils.resize(function() {
        var TargetChatArea = document.querySelector(Selector.CHAT_CONTENT_SCROLL_AREA_ACTIVE);
        setScrollbarPosition(TargetChatArea);
    });
};

/* -------------------------------------------------------------------------- */
/*                                   choices                                   */
/* -------------------------------------------------------------------------- */
var choicesInit = function choicesInit() {
    if (window.Choices) {
        var elements = document.querySelectorAll('.js-choice');
        elements.forEach(function(item) {
            var userOptions = utils.getData(item, 'options');
            var choices = new window.Choices(item, _objectSpread({
                itemSelectText: ''
            }, userOptions));
            var needsValidation = document.querySelectorAll('.needs-validation');
            needsValidation.forEach(function(validationItem) {
                var selectFormValidation = function selectFormValidation() {
                    validationItem.querySelectorAll('.choices').forEach(function(choicesItem) {
                        var singleSelect = choicesItem.querySelector('.choices__list--single');
                        var multipleSelect = choicesItem.querySelector('.choices__list--multiple');
                        if (choicesItem.querySelector('[required]')) {
                            if (singleSelect) {
                                var _singleSelect$querySe;
                                if (((_singleSelect$querySe = singleSelect.querySelector('.choices__item--selectable')) === null || _singleSelect$querySe === void 0 ? void 0 : _singleSelect$querySe.getAttribute('data-value')) !== '') {
                                    choicesItem.classList.remove('invalid');
                                    choicesItem.classList.add('valid');
                                } else {
                                    choicesItem.classList.remove('valid');
                                    choicesItem.classList.add('invalid');
                                }
                            }
                            //----- for multiple select only ----------
                            if (multipleSelect) {
                                if (choicesItem.getElementsByTagName('option').length) {
                                    choicesItem.classList.remove('invalid');
                                    choicesItem.classList.add('valid');
                                } else {
                                    choicesItem.classList.remove('valid');
                                    choicesItem.classList.add('invalid');
                                }
                            }

                            //------ select end ---------------
                        }
                    });
                };
                validationItem.addEventListener('submit', function() {
                    selectFormValidation();
                });
                item.addEventListener('change', function() {
                    selectFormValidation();
                });
            });
            return choices;
        });
    }
};

/*-----------------------------------------------
|   Cookie notice
-----------------------------------------------*/
var cookieNoticeInit = function cookieNoticeInit() {
    var Selector = {
        NOTICE: '.notice',
        DATA_TOGGLE_Notice: '[data-bs-toggle="notice"]'
    };
    var Events = {
        CLICK: 'click',
        HIDDEN_BS_TOAST: 'hidden.bs.toast'
    };
    var DataKeys = {
        OPTIONS: 'options'
    };
    var ClassNames = {
        HIDE: 'hide'
    };
    var notices = document.querySelectorAll(Selector.NOTICE);
    var showNotice = true;
    notices.forEach(function(item) {
        var notice = new window.bootstrap.Toast(item);
        var options = _objectSpread({
            autoShow: false,
            autoShowDelay: 0,
            showOnce: false,
            cookieExpireTime: 3600000
        }, utils.getData(item, DataKeys.OPTIONS));
        var showOnce = options.showOnce,
            autoShow = options.autoShow,
            autoShowDelay = options.autoShowDelay;
        if (showOnce) {
            var hasNotice = utils.getCookie('notice');
            showNotice = hasNotice === null;
        }
        if (autoShow && showNotice) {
            setTimeout(function() {
                notice.show();
            }, autoShowDelay);
        }
        item.addEventListener(Events.HIDDEN_BS_TOAST, function(e) {
            var el = e.currentTarget;
            var toastOptions = _objectSpread({
                cookieExpireTime: 3600000,
                showOnce: false
            }, utils.getData(el, DataKeys.OPTIONS));
            if (toastOptions.showOnce) {
                utils.setCookie('notice', false, toastOptions.cookieExpireTime);
            }
        });
    });
    var btnNoticeToggle = document.querySelector(Selector.DATA_TOGGLE_Notice);
    if (btnNoticeToggle) {
        btnNoticeToggle.addEventListener(Events.CLICK, function(_ref) {
            var currentTarget = _ref.currentTarget;
            var id = currentTarget.getAttribute('href');
            var notice = new window.bootstrap.Toast(document.querySelector(id));
            var el = notice._element;
            utils.hasClass(el, ClassNames.HIDE) ? notice.show() : notice.hide();
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                                  Copy LinK                                 */
/* -------------------------------------------------------------------------- */

var copyLink = function copyLink() {
    var copyLinkModal = document.getElementById('copyLinkModal');
    copyLinkModal && copyLinkModal.addEventListener('shown.bs.modal', function() {
        var invitationLink = document.querySelector('.invitation-link');
        invitationLink.select();
    });
    var copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons && copyButtons.forEach(function(button) {
        var tooltip = new window.bootstrap.Tooltip(button);
        button.addEventListener('mouseover', function() {
            return tooltip.show();
        });
        button.addEventListener('mouseleave', function() {
            return tooltip.hide();
        });
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            var el = e.target;
            el.setAttribute('data-original-title', 'Copied');
            tooltip.show();
            el.setAttribute('data-original-title', 'Copy to clipboard');
            tooltip.update();
            var inputID = utils.getData(el, 'copy');
            var input = document.querySelector(inputID);
            input.select();
            document.execCommand('copy');
        });
    });
};

/* -------------------------------------------------------------------------- */
/*                                  Count Up                                  */
/* -------------------------------------------------------------------------- */

var countupInit = function countupInit() {
    if (window.countUp) {
        var countups = document.querySelectorAll('[data-countup]');
        countups.forEach(function(node) {
            var _utils$getData2 = utils.getData(node, 'countup'),
                endValue = _utils$getData2.endValue,
                options = _objectWithoutProperties(_utils$getData2, _excluded);
            var countUp = new window.countUp.CountUp(node, endValue, _objectSpread({
                duration: 5
            }, options));
            if (!countUp.error) {
                countUp.start();
            } else {
                console.error(countUp.error);
            }
        });
    }
};

/*-----------------------------------------------
|   Data table
-----------------------------------------------*/
var dataTablesInit = function dataTablesInit() {
    if (window.jQuery) {
        var $ = window.jQuery;
        var dataTables = $('[data-datatables]');
        var customDataTable = function customDataTable(elem) {
            elem.find('.pagination').addClass('pagination-sm');
        };
        dataTables.length && dataTables.each(function(index, value) {
            var $this = $(value);
            var options = $.extend({
                dom: "<'row mx-0'<'col-md-6'l><'col-md-6'f>>" + "<'table-responsive scrollbar'tr>" + "<'row g-0 align-items-center justify-content-center justify-content-sm-between'<'col-auto mb-2 mb-sm-0 px-3'i><'col-auto px-3'p>>"
            }, $this.data('datatables'));
            $this.DataTable(options);
            bulkSelectInit();
            var $wrpper = $this.closest('.dt-container');
            customDataTable($wrpper);
            $this.on('draw.dt', function() {
                return customDataTable($wrpper);
            });
        });
    }
};

/*-----------------------------------------------
|   Dashboard Table dropdown
-----------------------------------------------*/
var dropdownMenuInit = function dropdownMenuInit() {
    // Only for ios
    if (window.is.ios()) {
        var _Event = {
            SHOWN_BS_DROPDOWN: 'shown.bs.dropdown',
            HIDDEN_BS_DROPDOWN: 'hidden.bs.dropdown'
        };
        var Selector = {
            TABLE_RESPONSIVE: '.table-responsive',
            DROPDOWN_MENU: '.dropdown-menu'
        };
        document.querySelectorAll(Selector.TABLE_RESPONSIVE).forEach(function(table) {
            table.addEventListener(_Event.SHOWN_BS_DROPDOWN, function(e) {
                var t = e.currentTarget;
                if (t.scrollWidth > t.clientWidth) {
                    t.style.paddingBottom = "".concat(e.target.nextElementSibling.clientHeight, "px");
                }
            });
            table.addEventListener(_Event.HIDDEN_BS_DROPDOWN, function(e) {
                e.currentTarget.style.paddingBottom = '';
            });
        });
    }
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
/*                                   Popover                                  */
/* -------------------------------------------------------------------------- */

var emojiMartInit = function emojiMartInit() {
    var _ref2 = window.EmojiMart || {},
        Picker = _ref2.Picker;
    if (Picker) {
        var emojiMartBtns = document.querySelectorAll('[data-emoji-mart]');
        if (emojiMartBtns) {
            Array.from(emojiMartBtns).forEach(function(btn) {
                var inputTarget = utils.getData(btn, 'emoji-mart-input-target');
                var input = document.querySelector(inputTarget);
                var picker = new Picker(window._.merge(utils.getData(btn, 'emoji-mart'), {
                    previewPosition: 'none',
                    skinTonePosition: 'none',
                    onEmojiSelect: function onEmojiSelect(e) {
                        if (input) input.innerHTML += e["native"];
                    },
                    onClickOutside: function onClickOutside(e) {
                        if (!picker.contains(e.target) && !btn.contains(e.target)) {
                            picker.classList.add('d-none');
                        }
                    }
                }));
                picker.classList.add('d-none');
                btn.parentElement.appendChild(picker);
                btn.addEventListener('click', function() {
                    return picker.classList.toggle('d-none');
                });
            });
        }
    }
};

/* -------------------------------------------------------------------------- */
/*                                  Flatpickr                                 */
/* -------------------------------------------------------------------------- */

var defaultPredefinedRanges = [{
    id: 'today',
    label: 'Today',
    range: [new Date(new Date().setHours(0, 0, 0, 0)), new Date()]
}, {
    id: 'this_month',
    label: 'This Month',
    range: [new Date(new Date().getFullYear(), new Date().getMonth(), 1), new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)]
}, {
    id: 'last_month',
    label: 'Last Month',
    range: [new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1), new Date(new Date().getFullYear(), new Date().getMonth(), 0)]
}, {
    id: 'last_7_days',
    label: 'Last 7 Days',
    range: [new Date(new Date().getTime() - 7 * 24 * 60 * 60 * 1000), new Date()]
}, {
    id: 'last_30_days',
    label: 'Last 30 Days',
    range: [new Date(new Date().getTime() - 30 * 24 * 60 * 60 * 1000), new Date()]
}];
document.querySelectorAll('.datetimepicker').forEach(function(item) {
    function applyUserRange(defaultRange, userRange) {
        var matchingDefault = defaultRange.find(function(mathItem) {
            return mathItem.id === Object.keys(userRange)[0];
        });
        return matchingDefault ? _objectSpread(_objectSpread({}, matchingDefault), {}, {
            label: userRange[Object.keys(userRange)[0]]
        }) : userRange;
    }

    function findDefaultRange(defaultRange, userRange) {
        return defaultRange.find(function(rangeItem) {
            return rangeItem.id === userRange;
        }) || null;
    }

    function generateRangeButtons(predefinedDefaultRanges) {
        var userDefinedRanges = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
        var normalizedUserRanges = Array.isArray(userDefinedRanges) ? userDefinedRanges : predefinedDefaultRanges;
        var mergedRanges = normalizedUserRanges.map(function(userRange) {
            if (_typeof(userRange) === 'object') {
                return applyUserRange(predefinedDefaultRanges, userRange);
            }
            return findDefaultRange(predefinedDefaultRanges, userRange);
        }).filter(Boolean);
        return "\n      <ul class=\"flatpickr-predefined-ranges list-group list-group-flush\">\n        ".concat(mergedRanges.map(function(_ref3) {
            var range = _ref3.range,
                label = _ref3.label;
            return "\n            <button type=\"button\" \n              data-range=\"".concat(range.map(function(date) {
                return date instanceof Date ? date.toISOString() : date;
            }).join(','), "\" \n              class=\"nav-link list-group-item list-group-item-action\">\n              ").concat(label, "\n            </button>\n          ");
        }).join(''), "\n      </ul>\n    ");
    }

    function appendRangeButtonsIfNotExists(calendarContainer, rangeButtonsHtml) {
        if (!calendarContainer.querySelector('.flatpickr-predefined-ranges')) {
            calendarContainer.insertAdjacentHTML('afterbegin', rangeButtonsHtml);
        }
    }

    function addRangeButtonClickListeners(instance, calendarContainer) {
        _toConsumableArray(calendarContainer.querySelectorAll('[data-range]')).map(function(btn) {
            return btn.addEventListener('click', function() {
                var startDate = new Date(utils.getData(btn, 'range').split(',')[0]);
                var endDate = new Date(utils.getData(btn, 'range').split(',')[1]);
                instance.setDate([startDate, endDate], true);
                instance.redraw();
            });
        });
    }

    function initializeFlatpickr(element, options) {
        function showPredefinedRanges(selectedDates, dateStr, instance) {
            var calendarContainer = instance.calendarContainer;
            if (options.predefinedRanges) {
                calendarContainer.classList.add('predefinedRange');
                var rangeButtonsHtml = generateRangeButtons(defaultPredefinedRanges, options.predefinedRanges);
                appendRangeButtonsIfNotExists(calendarContainer, rangeButtonsHtml);
                addRangeButtonClickListeners(instance, calendarContainer);
            }
        }

        function hidePredefinedRanges(selectedDates, dateStr, instance) {
            if (options.predefinedRanges) {
                var calendarContainer = instance.calendarContainer;
                calendarContainer.classList.remove('predefinedRange');
            }
        }
        var instance = window.flatpickr(element, _objectSpread(_objectSpread({}, options), {}, {
            onOpen: showPredefinedRanges,
            onClose: hidePredefinedRanges
        }));
        return instance;
    }
    var options = utils.getData(item, 'options');
    initializeFlatpickr(item, options);
});

/* -------------------------------------------------------------------------- */
/*                               from-validation                              */
/* -------------------------------------------------------------------------- */

var formValidationInit = function formValidationInit() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
};

/* -------------------------------------------------------------------------- */
/*                                FullCalendar                                */
/* -------------------------------------------------------------------------- */

// var merge = window._.merge;
// var renderCalendar = function renderCalendar(el, option) {
//     var _document$querySelect;
//     var options = merge({
//         initialView: 'dayGridMonth',
//         editable: true,
//         direction: document.querySelector('html').getAttribute('dir'),
//         headerToolbar: {
//             left: 'prev,next today',
//             center: 'title',
//             right: 'dayGridMonth,timeGridWeek,timeGridDay'
//         },
//         buttonText: {
//             month: 'Month',
//             week: 'Week',
//             day: 'Day'
//         }
//     }, option);
//     var calendar = new window.FullCalendar.Calendar(el, options);
//     calendar.render();
//     (_document$querySelect = document.querySelector('.navbar-vertical-toggle')) === null || _document$querySelect === void 0 || _document$querySelect.addEventListener('navbar.vertical.toggle', function() {
//         return calendar.updateSize();
//     });
//     return calendar;
// };
// var fullCalendarInit = function fullCalendarInit() {
//     var calendars = document.querySelectorAll('[data-calendar]');
//     calendars.forEach(function(item) {
//         var options = utils.getData(item, 'calendar');
//         renderCalendar(item, options);
//     });
// };
// var fullCalendar = {
//     renderCalendar: renderCalendar,
//     fullCalendarInit: fullCalendarInit
// };

/* -------------------------------------------------------------------------- */
/*                                 Glightbox                                */
/* -------------------------------------------------------------------------- */

var glightboxInit = function glightboxInit() {
    if (window.GLightbox) {
        window.GLightbox({
            selector: '[data-gallery]'
        });
    }
};

/*-----------------------------------------------
|   Gooogle Map
-----------------------------------------------*/

function initMap() {
    var themeController = document.body;
    var $googlemaps = document.querySelectorAll('.googlemap');
    if ($googlemaps.length && window.google) {
        // Visit https://snazzymaps.com/ for more themes
        var mapStyles = {
            Default: [{
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{
                    color: '#e9e9e9'
                }, {
                    lightness: 17
                }]
            }, {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{
                    color: '#f5f5f5'
                }, {
                    lightness: 20
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#ffffff'
                }, {
                    lightness: 17
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#ffffff'
                }, {
                    lightness: 29
                }, {
                    weight: 0.2
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{
                    color: '#ffffff'
                }, {
                    lightness: 18
                }]
            }, {
                featureType: 'road.local',
                elementType: 'geometry',
                stylers: [{
                    color: '#ffffff'
                }, {
                    lightness: 16
                }]
            }, {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{
                    color: '#f5f5f5'
                }, {
                    lightness: 21
                }]
            }, {
                featureType: 'poi.park',
                elementType: 'geometry',
                stylers: [{
                    color: '#dedede'
                }, {
                    lightness: 21
                }]
            }, {
                elementType: 'labels.text.stroke',
                stylers: [{
                    visibility: 'on'
                }, {
                    color: '#ffffff'
                }, {
                    lightness: 16
                }]
            }, {
                elementType: 'labels.text.fill',
                stylers: [{
                    saturation: 36
                }, {
                    color: '#333333'
                }, {
                    lightness: 40
                }]
            }, {
                elementType: 'labels.icon',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'transit',
                elementType: 'geometry',
                stylers: [{
                    color: '#f2f2f2'
                }, {
                    lightness: 19
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#fefefe'
                }, {
                    lightness: 20
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#fefefe'
                }, {
                    lightness: 17
                }, {
                    weight: 1.2
                }]
            }],
            Gray: [{
                featureType: 'all',
                elementType: 'labels.text.fill',
                stylers: [{
                    saturation: 36
                }, {
                    color: '#000000'
                }, {
                    lightness: 40
                }]
            }, {
                featureType: 'all',
                elementType: 'labels.text.stroke',
                stylers: [{
                    visibility: 'on'
                }, {
                    color: '#000000'
                }, {
                    lightness: 16
                }]
            }, {
                featureType: 'all',
                elementType: 'labels.icon',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 20
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 17
                }, {
                    weight: 1.2
                }]
            }, {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 20
                }]
            }, {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 21
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 17
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 29
                }, {
                    weight: 0.2
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 18
                }]
            }, {
                featureType: 'road.local',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 16
                }]
            }, {
                featureType: 'transit',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 19
                }]
            }, {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 17
                }]
            }],
            Midnight: [{
                featureType: 'all',
                elementType: 'labels.text.fill',
                stylers: [{
                    color: '#ffffff'
                }]
            }, {
                featureType: 'all',
                elementType: 'labels.text.stroke',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 13
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#000000'
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#144b53'
                }, {
                    lightness: 14
                }, {
                    weight: 1.4
                }]
            }, {
                featureType: 'landscape',
                elementType: 'all',
                stylers: [{
                    color: '#08304b'
                }]
            }, {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{
                    color: '#0c4152'
                }, {
                    lightness: 5
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#000000'
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#0b434f'
                }, {
                    lightness: 25
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#000000'
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#0b3d51'
                }, {
                    lightness: 16
                }]
            }, {
                featureType: 'road.local',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }]
            }, {
                featureType: 'transit',
                elementType: 'all',
                stylers: [{
                    color: '#146474'
                }]
            }, {
                featureType: 'water',
                elementType: 'all',
                stylers: [{
                    color: '#021019'
                }]
            }],
            Hopper: [{
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{
                    hue: '#165c64'
                }, {
                    saturation: 34
                }, {
                    lightness: -69
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{
                    hue: '#b7caaa'
                }, {
                    saturation: -14
                }, {
                    lightness: -18
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'landscape.man_made',
                elementType: 'all',
                stylers: [{
                    hue: '#cbdac1'
                }, {
                    saturation: -6
                }, {
                    lightness: -9
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'road',
                elementType: 'geometry',
                stylers: [{
                    hue: '#8d9b83'
                }, {
                    saturation: -89
                }, {
                    lightness: -12
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry',
                stylers: [{
                    hue: '#d4dad0'
                }, {
                    saturation: -88
                }, {
                    lightness: 54
                }, {
                    visibility: 'simplified'
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{
                    hue: '#bdc5b6'
                }, {
                    saturation: -89
                }, {
                    lightness: -3
                }, {
                    visibility: 'simplified'
                }]
            }, {
                featureType: 'road.local',
                elementType: 'geometry',
                stylers: [{
                    hue: '#bdc5b6'
                }, {
                    saturation: -89
                }, {
                    lightness: -26
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{
                    hue: '#c17118'
                }, {
                    saturation: 61
                }, {
                    lightness: -45
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'poi.park',
                elementType: 'all',
                stylers: [{
                    hue: '#8ba975'
                }, {
                    saturation: -46
                }, {
                    lightness: -28
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'transit',
                elementType: 'geometry',
                stylers: [{
                    hue: '#a43218'
                }, {
                    saturation: 74
                }, {
                    lightness: -51
                }, {
                    visibility: 'simplified'
                }]
            }, {
                featureType: 'administrative.province',
                elementType: 'all',
                stylers: [{
                    hue: '#ffffff'
                }, {
                    saturation: 0
                }, {
                    lightness: 100
                }, {
                    visibility: 'simplified'
                }]
            }, {
                featureType: 'administrative.neighborhood',
                elementType: 'all',
                stylers: [{
                    hue: '#ffffff'
                }, {
                    saturation: 0
                }, {
                    lightness: 100
                }, {
                    visibility: 'off'
                }]
            }, {
                featureType: 'administrative.locality',
                elementType: 'labels',
                stylers: [{
                    hue: '#ffffff'
                }, {
                    saturation: 0
                }, {
                    lightness: 100
                }, {
                    visibility: 'off'
                }]
            }, {
                featureType: 'administrative.land_parcel',
                elementType: 'all',
                stylers: [{
                    hue: '#ffffff'
                }, {
                    saturation: 0
                }, {
                    lightness: 100
                }, {
                    visibility: 'off'
                }]
            }, {
                featureType: 'administrative',
                elementType: 'all',
                stylers: [{
                    hue: '#3a3935'
                }, {
                    saturation: 5
                }, {
                    lightness: -57
                }, {
                    visibility: 'off'
                }]
            }, {
                featureType: 'poi.medical',
                elementType: 'geometry',
                stylers: [{
                    hue: '#cba923'
                }, {
                    saturation: 50
                }, {
                    lightness: -46
                }, {
                    visibility: 'on'
                }]
            }],
            Beard: [{
                featureType: 'poi.business',
                elementType: 'labels.text',
                stylers: [{
                    visibility: 'on'
                }, {
                    color: '#333333'
                }]
            }],
            AssassianCreed: [{
                featureType: 'all',
                elementType: 'all',
                stylers: [{
                    visibility: 'on'
                }]
            }, {
                featureType: 'all',
                elementType: 'labels',
                stylers: [{
                    visibility: 'off'
                }, {
                    saturation: '-100'
                }]
            }, {
                featureType: 'all',
                elementType: 'labels.text.fill',
                stylers: [{
                    saturation: 36
                }, {
                    color: '#000000'
                }, {
                    lightness: 40
                }, {
                    visibility: 'off'
                }]
            }, {
                featureType: 'all',
                elementType: 'labels.text.stroke',
                stylers: [{
                    visibility: 'off'
                }, {
                    color: '#000000'
                }, {
                    lightness: 16
                }]
            }, {
                featureType: 'all',
                elementType: 'labels.icon',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 20
                }]
            }, {
                featureType: 'administrative',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 17
                }, {
                    weight: 1.2
                }]
            }, {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 20
                }]
            }, {
                featureType: 'landscape',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#4d6059'
                }]
            }, {
                featureType: 'landscape',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#4d6059'
                }]
            }, {
                featureType: 'landscape.natural',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#4d6059'
                }]
            }, {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{
                    lightness: 21
                }]
            }, {
                featureType: 'poi',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#4d6059'
                }]
            }, {
                featureType: 'poi',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#4d6059'
                }]
            }, {
                featureType: 'road',
                elementType: 'geometry',
                stylers: [{
                    visibility: 'on'
                }, {
                    color: '#7f8d89'
                }]
            }, {
                featureType: 'road',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#7f8d89'
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#7f8d89'
                }, {
                    lightness: 17
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#7f8d89'
                }, {
                    lightness: 29
                }, {
                    weight: 0.2
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 18
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#7f8d89'
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#7f8d89'
                }]
            }, {
                featureType: 'road.local',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 16
                }]
            }, {
                featureType: 'road.local',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#7f8d89'
                }]
            }, {
                featureType: 'road.local',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#7f8d89'
                }]
            }, {
                featureType: 'transit',
                elementType: 'geometry',
                stylers: [{
                    color: '#000000'
                }, {
                    lightness: 19
                }]
            }, {
                featureType: 'water',
                elementType: 'all',
                stylers: [{
                    color: '#2b3638'
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{
                    color: '#2b3638'
                }, {
                    lightness: 17
                }]
            }, {
                featureType: 'water',
                elementType: 'geometry.fill',
                stylers: [{
                    color: '#24282b'
                }]
            }, {
                featureType: 'water',
                elementType: 'geometry.stroke',
                stylers: [{
                    color: '#24282b'
                }]
            }, {
                featureType: 'water',
                elementType: 'labels',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'water',
                elementType: 'labels.text',
                stylers: [{
                    visibility: 'off '
                }]
            }, {
                featureType: 'water',
                elementType: 'labels.text.fill',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'water',
                elementType: 'labels.text.stroke',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'water',
                elementType: 'labels.icon',
                stylers: [{
                    visibility: 'off'
                }]
            }],
            SubtleGray: [{
                featureType: 'administrative',
                elementType: 'all',
                stylers: [{
                    saturation: '-100'
                }]
            }, {
                featureType: 'administrative.province',
                elementType: 'all',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'landscape',
                elementType: 'all',
                stylers: [{
                    saturation: -100
                }, {
                    lightness: 65
                }, {
                    visibility: 'on'
                }]
            }, {
                featureType: 'poi',
                elementType: 'all',
                stylers: [{
                    saturation: -100
                }, {
                    lightness: '50'
                }, {
                    visibility: 'simplified'
                }]
            }, {
                featureType: 'road',
                elementType: 'all',
                stylers: [{
                    saturation: -100
                }]
            }, {
                featureType: 'road.highway',
                elementType: 'all',
                stylers: [{
                    visibility: 'simplified'
                }]
            }, {
                featureType: 'road.arterial',
                elementType: 'all',
                stylers: [{
                    lightness: '30'
                }]
            }, {
                featureType: 'road.local',
                elementType: 'all',
                stylers: [{
                    lightness: '40'
                }]
            }, {
                featureType: 'transit',
                elementType: 'all',
                stylers: [{
                    saturation: -100
                }, {
                    visibility: 'simplified'
                }]
            }, {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{
                    hue: '#ffff00'
                }, {
                    lightness: -25
                }, {
                    saturation: -97
                }]
            }, {
                featureType: 'water',
                elementType: 'labels',
                stylers: [{
                    lightness: -25
                }, {
                    saturation: -100
                }]
            }],
            Tripitty: [{
                featureType: 'all',
                elementType: 'labels',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'administrative',
                elementType: 'all',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'landscape',
                elementType: 'all',
                stylers: [{
                    color: '#2c5ca5'
                }]
            }, {
                featureType: 'poi',
                elementType: 'all',
                stylers: [{
                    color: '#2c5ca5'
                }]
            }, {
                featureType: 'road',
                elementType: 'all',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'transit',
                elementType: 'all',
                stylers: [{
                    visibility: 'off'
                }]
            }, {
                featureType: 'water',
                elementType: 'all',
                stylers: [{
                    color: '#193a70'
                }, {
                    visibility: 'on'
                }]
            }],
            Cobalt: [{
                featureType: 'all',
                elementType: 'all',
                stylers: [{
                    invert_lightness: true
                }, {
                    saturation: 10
                }, {
                    lightness: 30
                }, {
                    gamma: 0.5
                }, {
                    hue: '#435158'
                }]
            }]
        };
        $googlemaps.forEach(function(itm) {
            var latLng = utils.getData(itm, 'latlng').split(',');
            var markerPopup = itm.innerHTML;
            var icon = utils.getData(itm, 'icon') ? utils.getData(itm, 'icon') : 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png';
            var zoom = utils.getData(itm, 'zoom');
            var mapElement = itm;
            var mapStyle = utils.getData(itm, 'theme');
            if (utils.getData(itm, 'theme') === 'streetview') {
                var pov = utils.getData(itm, 'pov');
                var _mapOptions = {
                    position: {
                        lat: Number(latLng[0]),
                        lng: Number(latLng[1])
                    },
                    pov: pov,
                    zoom: zoom,
                    gestureHandling: 'none',
                    scrollwheel: false
                };
                return new window.google.maps.StreetViewPanorama(mapElement, _mapOptions);
            }
            var mapOptions = {
                zoom: zoom,
                scrollwheel: utils.getData(itm, 'scrollwheel'),
                center: new window.google.maps.LatLng(latLng[0], latLng[1]),
                styles: utils.isDark() === 'dark' ? mapStyles.Cobalt : mapStyles[mapStyle]
            };
            var map = new window.google.maps.Map(mapElement, mapOptions);
            var infowindow = new window.google.maps.InfoWindow({
                content: markerPopup
            });
            var marker = new window.google.maps.Marker({
                position: new window.google.maps.LatLng(latLng[0], latLng[1]),
                icon: icon,
                map: map
            });
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
            themeController && themeController.addEventListener('clickControl', function(_ref4) {
                var _ref4$detail = _ref4.detail,
                    control = _ref4$detail.control,
                    value = _ref4$detail.value;
                if (control === 'theme') {
                    map.set('styles', value === 'dark' ? mapStyles.Cobalt : mapStyles[mapStyle]);
                }
            });
            return null;
        });
    }
}
var hideOnCollapseInit = function hideOnCollapseInit() {
    var previewMailForm = document.querySelector('#previewMailForm');
    var previewFooter = document.querySelector('#preview-footer');
    if (previewMailForm) {
        previewMailForm.addEventListener('show.bs.collapse', function() {
            previewFooter.classList.add('d-none');
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                           Icon copy to clipboard                           */
/* -------------------------------------------------------------------------- */

// var iconCopiedInit = function iconCopiedInit() {
//     var iconList = document.getElementById('icon-list');
//     var iconCopiedToast = document.getElementById('icon-copied-toast');
//     var iconCopiedToastInstance = new window.bootstrap.Toast(iconCopiedToast);
//     if (iconList) {
//         iconList.addEventListener('click', function(e) {
//             var el = e.target;
//             if (el.tagName === 'INPUT') {
//                 el.select();
//                 el.setSelectionRange(0, 99999);
//                 document.execCommand('copy');
//                 iconCopiedToast.querySelector('.toast-body').innerHTML = "<span class=\"fw-black\">Copied:</span> <code>".concat(el.value, "</code>");
//                 iconCopiedToastInstance.show();
//             }
//         });
//     }
// };

/* -------------------------------------------------------------------------- */
/*                                   Inputmask                                */
/* -------------------------------------------------------------------------- */
var inputmaskInit = function inputmaskInit() {
    if (window.Inputmask) {
        var elements = document.querySelectorAll('[data-input-mask]');
        elements.forEach(function(item) {
            var userOptions = utils.getData(item, 'input-mask');
            var defaultOptions = {
                showMaskOnFocus: false,
                showMaskOnHover: false,
                jitMasking: true
            };
            var maskOptions = window._.merge(defaultOptions, userOptions);
            var inputmask = new window.Inputmask(_objectSpread({}, maskOptions)).mask(item);
            return inputmask;
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                                   Kanbah                                   */
/* -------------------------------------------------------------------------- */

var kanbanInit = function kanbanInit() {
    var Selectors = {
        KANBAN_COLUMN: '.kanban-column',
        KANBAN_ITEMS_CONTAINER: '.kanban-items-container',
        BTN_ADD_CARD: '.btn-add-card',
        COLLAPSE: '.collapse',
        ADD_LIST_FORM: '#addListForm',
        BTN_COLLAPSE_DISMISS: '[data-dismiss="collapse"]',
        BTN_FORM_HIDE: '[data-btn-form="hide"]',
        INPUT_ADD_CARD: '[data-input="add-card"]',
        INPUT_ADD_LIST: '[data-input="add-list"]'
    };
    var ClassNames = {
        FORM_ADDED: 'form-added',
        D_NONE: 'd-none'
    };
    var Events = {
        CLICK: 'click',
        SHOW_BS_COLLAPSE: 'show.bs.collapse',
        SHOWN_BS_COLLAPSE: 'shown.bs.collapse'
    };
    var addCardButtons = document.querySelectorAll(Selectors.BTN_ADD_CARD);
    var formHideButtons = document.querySelectorAll(Selectors.BTN_FORM_HIDE);
    var addListForm = document.querySelector(Selectors.ADD_LIST_FORM);
    var collapseDismissButtons = document.querySelectorAll(Selectors.BTN_COLLAPSE_DISMISS);

    // Show add card form and place scrollbar bottom of the list
    addCardButtons && addCardButtons.forEach(function(button) {
        button.addEventListener(Events.CLICK, function(_ref5) {
            var el = _ref5.currentTarget;
            var column = el.closest(Selectors.KANBAN_COLUMN);
            var container = column.querySelector(Selectors.KANBAN_ITEMS_CONTAINER);
            var scrollHeight = container.scrollHeight;
            column.classList.add(ClassNames.FORM_ADDED);
            container.querySelector(Selectors.INPUT_ADD_CARD).focus();
            container.scrollTo({
                top: scrollHeight
            });
        });
    });

    // Remove add card form
    formHideButtons.forEach(function(button) {
        button.addEventListener(Events.CLICK, function(_ref6) {
            var el = _ref6.currentTarget;
            el.closest(Selectors.KANBAN_COLUMN).classList.remove(ClassNames.FORM_ADDED);
        });
    });
    if (addListForm) {
        // Hide add list button when the form is going to show
        addListForm.addEventListener(Events.SHOW_BS_COLLAPSE, function(_ref7) {
            var el = _ref7.currentTarget;
            var nextElement = el.nextElementSibling;
            nextElement && nextElement.classList.add(ClassNames.D_NONE);
        });

        // Focus input field when the form is shown
        addListForm.addEventListener(Events.SHOWN_BS_COLLAPSE, function(_ref8) {
            var el = _ref8.currentTarget;
            el.querySelector(Selectors.INPUT_ADD_LIST).focus();
        });
    }

    // Hide add list form when the dismiss button is clicked
    collapseDismissButtons.forEach(function(button) {
        button.addEventListener(Events.CLICK, function(_ref9) {
            var el = _ref9.currentTarget;
            var collapseElement = el.closest(Selectors.COLLAPSE);
            var collapse = window.bootstrap.Collapse.getInstance(collapseElement);
            utils.hasClass(collapseElement.nextElementSibling, ClassNames.D_NONE) && collapseElement.nextElementSibling.classList.remove(ClassNames.D_NONE);
            collapse.hide();
        });
    });
};

/* -------------------------------------------------------------------------- */
/*                                   leaflet                                  */
/* -------------------------------------------------------------------------- */

var leafletActiveUserInit = function leafletActiveUserInit() {
    var points = [{
        lat: 53.958332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 52.958332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 51.958332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 53.958332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 54.958332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 55.958332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 53.908332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 53.008332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 53.158332,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 53.000032,
        "long": -1.080278,
        name: 'Diana Meyer',
        street: 'Slude Strand 27',
        location: '1130 Kobenhavn'
    }, {
        lat: 52.292001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 52.392001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 51.492001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 51.192001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 52.292001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 54.392001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 51.292001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 52.102001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 52.202001,
        "long": -2.22,
        name: 'Anke Schroder',
        street: 'Industrivej 54',
        location: '4140 Borup'
    }, {
        lat: 51.063202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.363202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.463202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.563202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.763202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.863202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.963202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.000202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.000202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.163202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 52.263202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 53.463202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 55.163202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 56.263202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 56.463202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 56.563202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 56.663202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 56.763202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 56.863202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 56.963202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 57.973202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 57.163202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.163202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.263202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.363202,
        "long": -1.308,
        name: 'Tobias Vogel',
        street: 'Mollebakken 33',
        location: '3650 Olstykke'
    }, {
        lat: 51.409,
        "long": -2.647,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.68,
        "long": -1.49,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 50.259998,
        "long": -5.051,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 54.906101,
        "long": -1.38113,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.383331,
        "long": -1.466667,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.483002,
        "long": -2.2931,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.509865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.109865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.209865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.309865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.409865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.609865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.709865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.809865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 51.909865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.109865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.209865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.309865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.409865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.509865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.609865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.709865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.809865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.909865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.519865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.529865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.539865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.549865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 52.549865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.109865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.209865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.319865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.329865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.409865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.559865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.619865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.629865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.639865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.649865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.669865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.669865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.719865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.739865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.749865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.759865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.769865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.769865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.819865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.829865,
        "long": -0.118092,
        name: 'Richard Hendricks',
        street: '37 Seafield Place',
        location: 'London'
    }, {
        lat: 53.483959,
        "long": -2.244644,
        name: 'Ethel B. Brooks',
        street: '2576 Sun Valley Road'
    }, {
        lat: 40.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 39.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 38.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 37.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 40.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 41.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 42.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 43.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 44.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 45.737,
        "long": -73.923,
        name: 'Marshall D. Lewis',
        street: '1489 Michigan Avenue',
        location: 'Michigan'
    }, {
        lat: 46.7128,
        "long": 74.006,
        name: 'Elizabeth C. Lyons',
        street: '4553 Kenwood Place',
        location: 'Fort Lauderdale'
    }, {
        lat: 40.7128,
        "long": 74.1181,
        name: 'Elizabeth C. Lyons',
        street: '4553 Kenwood Place',
        location: 'Fort Lauderdale'
    }, {
        lat: 14.235,
        "long": 51.9253,
        name: 'Ralph D. Wylie',
        street: '3186 Levy Court',
        location: 'North Reading'
    }, {
        lat: 15.235,
        "long": 51.9253,
        name: 'Ralph D. Wylie',
        street: '3186 Levy Court',
        location: 'North Reading'
    }, {
        lat: 16.235,
        "long": 51.9253,
        name: 'Ralph D. Wylie',
        street: '3186 Levy Court',
        location: 'North Reading'
    }, {
        lat: 14.235,
        "long": 51.9253,
        name: 'Ralph D. Wylie',
        street: '3186 Levy Court',
        location: 'North Reading'
    }, {
        lat: 15.8267,
        "long": 47.9218,
        name: 'Hope A. Atkins',
        street: '3715 Hillcrest Drive',
        location: 'Seattle'
    }, {
        lat: 15.9267,
        "long": 47.9218,
        name: 'Hope A. Atkins',
        street: '3715 Hillcrest Drive',
        location: 'Seattle'
    }, {
        lat: 23.4425,
        "long": 58.4438,
        name: 'Samuel R. Bailey',
        street: '2883 Raoul Wallenberg Place',
        location: 'Cheshire'
    }, {
        lat: 23.5425,
        "long": 58.3438,
        name: 'Samuel R. Bailey',
        street: '2883 Raoul Wallenberg Place',
        location: 'Cheshire'
    }, {
        lat: -37.8927369333,
        "long": 175.4087452333,
        name: 'Samuel R. Bailey',
        street: '3228 Glory Road',
        location: 'Nashville'
    }, {
        lat: -38.9064188833,
        "long": 175.4441556833,
        name: 'Samuel R. Bailey',
        street: '3228 Glory Road',
        location: 'Nashville'
    }, {
        lat: -12.409874,
        "long": -65.596832,
        name: 'Ann J. Perdue',
        street: '921 Ella Street',
        location: 'Dublin'
    }, {
        lat: -22.090887,
        "long": -57.411827,
        name: 'Jorge C. Woods',
        street: '4800 North Bend River Road',
        location: 'Allen'
    }, {
        lat: -19.019585,
        "long": -65.261963,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: -16.500093,
        "long": -68.214684,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: -17.413977,
        "long": -66.165321,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: -16.489689,
        "long": -68.119293,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: 54.766323,
        "long": 3.08603729,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: 54.866323,
        "long": 3.08603729,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: 49.537685,
        "long": 3.08603729,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: 54.715424,
        "long": 0.509207,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: 44.891666,
        "long": 10.136665,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: 48.078335,
        "long": 14.535004,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: -26.358055,
        "long": 27.398056,
        name: 'Russ E. Panek',
        street: '4068 Hartland Avenue',
        location: 'Appleton'
    }, {
        lat: -29.1,
        "long": 26.2167,
        name: 'Wilbur J. Dry',
        street: '2043 Jadewood Drive',
        location: 'Northbrook'
    }, {
        lat: -29.883333,
        "long": 31.049999,
        name: 'Wilbur J. Dry',
        street: '2043 Jadewood Drive',
        location: 'Northbrook'
    }, {
        lat: -26.266111,
        "long": 27.865833,
        name: 'Wilbur J. Dry',
        street: '2043 Jadewood Drive',
        location: 'Northbrook'
    }, {
        lat: -29.087217,
        "long": 26.154898,
        name: 'Wilbur J. Dry',
        street: '2043 Jadewood Drive',
        location: 'Northbrook'
    }, {
        lat: -33.958252,
        "long": 25.619022,
        name: 'Wilbur J. Dry',
        street: '2043 Jadewood Drive',
        location: 'Northbrook'
    }, {
        lat: -33.977074,
        "long": 22.457581,
        name: 'Wilbur J. Dry',
        street: '2043 Jadewood Drive',
        location: 'Northbrook'
    }, {
        lat: -26.563404,
        "long": 27.844164,
        name: 'Wilbur J. Dry',
        street: '2043 Jadewood Drive',
        location: 'Northbrook'
    }, {
        lat: 51.21389,
        "long": -102.462776,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 52.321945,
        "long": -106.584167,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 50.288055,
        "long": -107.793892,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 52.7575,
        "long": -108.28611,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 50.393333,
        "long": -105.551941,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 50.930557,
        "long": -102.807777,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 52.856388,
        "long": -104.610001,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 52.289722,
        "long": -106.666664,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 52.201942,
        "long": -105.123055,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 53.278046,
        "long": -110.00547,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 49.13673,
        "long": -102.990959,
        name: 'Joseph B. Poole',
        street: '3364 Lunetta Street',
        location: 'Wichita Falls'
    }, {
        lat: 45.484531,
        "long": -73.597023,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 45.266666,
        "long": -71.900002,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 45.349998,
        "long": -72.51667,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 47.333332,
        "long": -79.433334,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 45.400002,
        "long": -74.033333,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 45.683334,
        "long": -73.433334,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 48.099998,
        "long": -77.783333,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 45.5,
        "long": -72.316666,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 46.349998,
        "long": -72.550003,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 48.119999,
        "long": -69.18,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 45.599998,
        "long": -75.25,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 46.099998,
        "long": -71.300003,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 45.700001,
        "long": -73.633331,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 47.68,
        "long": -68.879997,
        name: 'Claudette D. Nowakowski',
        street: '3742 Farland Avenue',
        location: 'San Antonio'
    }, {
        lat: 46.716667,
        "long": -79.099998,
        name: '299'
    }, {
        lat: 45.016666,
        "long": -72.099998,
        name: '299'
    }];
    var _window2 = window,
        L = _window2.L;
    var mapContainer = document.getElementById('map');
    if (L && mapContainer) {
        var getFilterColor = function getFilterColor() {
            return utils.isDark() === 'dark' ? ['invert:98%', 'grayscale:69%', 'bright:89%', 'contrast:111%', 'hue:205deg', 'saturate:1000%'] : ['bright:101%', 'contrast:101%', 'hue:23deg', 'saturate:225%'];
        };
        var tileLayerTheme = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
        var tiles = L.tileLayer.colorFilter(tileLayerTheme, {
            attribution: null,
            transparent: true,
            filter: getFilterColor()
        });
        var map = L.map('map', {
            center: L.latLng(10.737, 0),
            zoom: 0,
            layers: [tiles],
            minZoom: 1.3,
            zoomSnap: 0.5,
            dragging: !L.Browser.mobile,
            tap: !L.Browser.mobile
        });
        var mcg = L.markerClusterGroup({
            chunkedLoading: false,
            spiderfyOnMaxZoom: false
        });
        points.map(function(point) {
            var name = point.name,
                location = point.location,
                street = point.street;
            var icon = L.icon({
                iconUrl: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAApCAYAAADAk4LOAAAACXBIWXMAAAFgAAABYAEg2RPaAAADpElEQVRYCZ1XS1LbQBBtybIdiMEJKSpUqihgEW/xDdARyAnirOIl3MBH8NK7mBvkBpFv4Gy9IRSpFIQiRPyNfqkeZkY9HwmFt7Lm06+7p/vN2MmyDIrQ6QebALAHAD4AbFuWfQeAAACGs5H/w5jlsJJw4wMA+GhMFuMA99jIDJJOP+ihZwDQFmNuowWO1wS3viDXpdEdZPEc0odruj0EgN5s5H8tJOEEX8R3rbkMtcU34NTqhe5nSQTJ7Tkk80s6/Gk28scGiULguFBffgdufdEwWoQ0uoXo8hdAlooVH0REjISfwZSlyHGh0V5n6aHAtKTxXI5g6nQnMH0P4bEgwtR18Yw8Pj8QZ4ARUAI0Hl+fQZZGisGEBVwHr7XKzox57DXZ/ij8Cdwe2u057z9/wygOxRl4S2vSUHx1oucaMQGAHTrgtdag9mK5aN+Wx/uAAQ9Zenp/SRce4TpaNbQK4+sTcGqeTB/aIXv3XN5oj2VKqii++U0JunpZ8urxee4hvjqVc2hHpBDXuKKT9XMgVYJ1/1fPGSeaikzgmWWkMIi9bVf8UhotXxzORn5gWFchI8QyttlzjS0qpsaIGY2MMsujV/AUSdcY0dDpB6/EiOPYzclR1CI5mOez3ekHvrFLxa7cR5pTscfrXjk0Vhm5V2PqLUWnH3R5GbPGpMVD7E1ckXesKBQ7AS/vmQ1c0+kHuxpBj98lTCm8pbc5QRJRdZ6qHb/wGryXq3Lxszv+5gySuwvxueXySwYvHEjuQ9ofTGKYlrmK1EsCHMd5SoD7mZ1HHFCBHLNbMEshvrugqWLn01hpVVJhFgVGkDvK7hR6n2B+d9C7xsqWsbkqHv4cCsWezEb+o2SR+SFweUBxfA5wH7kShjKt2vWL57Px3GhIFEezkb8pxvUWHYhotAfCk2AtkEcxoOttrxUWDR5svb1emSQKj0WXK1HYIgFREbiBqmoZcB2RkbE+byMZiosorVgAZF1ID7yQhEs38wa7nUqNDezdlavC2HbBGSQkGgZ8uJVBmzeiKCRRpEa9ilWghORVeGB7BxeSKF5xqbFBkxBrFKUk/JHA7ppENQaCnCjthK+3opCEYyANztXmZN858cDYWSUSHk3A311GAZDvo6deNKUk1EsqnJoQlkYBNlmxQZeaMgmxoUokICoHDce351RCCiuKoirJWEgNOYvQplM2VCLhUqF7jf94rW9kHVUjQeheV4riv0i4ZOzzz/2y/+0KAOAfr4EE4HpCFhwAAAAASUVORK5CYII=\n        "
            });
            var marker = L.marker(new L.LatLng(point.lat, point["long"]), {
                icon: icon
            }, {
                name: name,
                location: location
            });
            var popupContent = "\n        <h6 class=\"mb-1\">".concat(name, "</h6>\n        <p class=\"m-0 text-500\">").concat(street, ", ").concat(location, "</p>\n      ");
            var popup = L.popup({
                minWidth: 180
            }).setContent(popupContent);
            marker.bindPopup(popup);
            mcg.addLayer(marker);
            return true;
        });
        map.addLayer(mcg);
        var themeController = document.body;
        themeController.addEventListener('clickControl', function(_ref10) {
            var _ref10$detail = _ref10.detail,
                control = _ref10$detail.control,
                value = _ref10$detail.value;
            if (control === 'theme') {
                tiles.updateFilter(value === 'dark' ? ['invert:98%', 'grayscale:69%', 'bright:89%', 'contrast:111%', 'hue:205deg', 'saturate:1000%'] : ['bright:101%', 'contrast:101%', 'hue:23deg', 'saturate:225%']);
            }
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                                 Data Table                                 */
/* -------------------------------------------------------------------------- */

var togglePaginationButtonDisable = function togglePaginationButtonDisable(button, disabled) {
    button.disabled = disabled;
    button.classList[disabled ? 'add' : 'remove']('disabled');
};
var listInit = function listInit() {
    if (window.List) {
        var lists = document.querySelectorAll('[data-list]');
        if (lists.length) {
            lists.forEach(function(el) {
                var bulkSelect = el.querySelector('[data-bulk-select]');
                var options = utils.getData(el, 'list');
                if (options.pagination) {
                    options = _objectSpread(_objectSpread({}, options), {}, {
                        pagination: _objectSpread({
                            item: "<li><button class='page' type='button'></button></li>"
                        }, options.pagination)
                    });
                }
                var paginationButtonNext = el.querySelector('[data-list-pagination="next"]');
                var paginationButtonPrev = el.querySelector('[data-list-pagination="prev"]');
                var viewAll = el.querySelector('[data-list-view="*"]');
                var viewLess = el.querySelector('[data-list-view="less"]');
                var listInfo = el.querySelector('[data-list-info]');
                var listFilters = document.querySelectorAll('[data-list-filter]');
                var list = new window.List(el, options);

                //-------fallback-----------

                list.on('updated', function(item) {
                    var fallback = el.querySelector('.fallback') || document.getElementById(options.fallback);
                    if (fallback) {
                        if (item.matchingItems.length === 0) {
                            fallback.classList.remove('d-none');
                        } else {
                            fallback.classList.add('d-none');
                        }
                    }
                });

                // ---------------------------------------

                var totalItem = list.items.length;
                var itemsPerPage = list.page;
                var btnDropdownClose = list.listContainer.querySelector('.btn-close');
                var pageQuantity = Math.ceil(totalItem / itemsPerPage);
                var numberOfcurrentItems = list.visibleItems.length;
                var pageCount = 1;
                btnDropdownClose && btnDropdownClose.addEventListener('search.close', function() {
                    list.fuzzySearch('');
                });
                var updateListControls = function updateListControls() {
                    listInfo && (listInfo.innerHTML = "".concat(list.i, " to ").concat(numberOfcurrentItems, " of ").concat(totalItem));
                    paginationButtonPrev && togglePaginationButtonDisable(paginationButtonPrev, pageCount === 1);
                    paginationButtonNext && togglePaginationButtonDisable(paginationButtonNext, pageCount === pageQuantity);
                    if (pageCount > 1 && pageCount < pageQuantity) {
                        togglePaginationButtonDisable(paginationButtonNext, false);
                        togglePaginationButtonDisable(paginationButtonPrev, false);
                    }
                };

                // List info
                updateListControls();
                if (paginationButtonNext) {
                    paginationButtonNext.addEventListener('click', function(e) {
                        e.preventDefault();
                        pageCount += 1;
                        var nextInitialIndex = list.i + itemsPerPage;
                        nextInitialIndex <= list.size() && list.show(nextInitialIndex, itemsPerPage);
                        numberOfcurrentItems += list.visibleItems.length;
                        updateListControls();
                    });
                }
                if (paginationButtonPrev) {
                    paginationButtonPrev.addEventListener('click', function(e) {
                        e.preventDefault();
                        pageCount -= 1;
                        numberOfcurrentItems -= list.visibleItems.length;
                        var prevItem = list.i - itemsPerPage;
                        prevItem > 0 && list.show(prevItem, itemsPerPage);
                        updateListControls();
                    });
                }
                var toggleViewBtn = function toggleViewBtn() {
                    viewLess.classList.toggle('d-none');
                    viewAll.classList.toggle('d-none');
                };
                if (viewAll) {
                    viewAll.addEventListener('click', function() {
                        list.show(1, totalItem);
                        pageQuantity = 1;
                        pageCount = 1;
                        numberOfcurrentItems = totalItem;
                        updateListControls();
                        toggleViewBtn();
                    });
                }
                if (viewLess) {
                    viewLess.addEventListener('click', function() {
                        list.show(1, itemsPerPage);
                        pageQuantity = Math.ceil(totalItem / itemsPerPage);
                        pageCount = 1;
                        numberOfcurrentItems = list.visibleItems.length;
                        updateListControls();
                        toggleViewBtn();
                    });
                }
                // numbering pagination
                if (options.pagination) {
                    el.querySelector('.pagination').addEventListener('click', function(e) {
                        if (e.target.classList[0] === 'page') {
                            pageCount = Number(e.target.innerText);
                            updateListControls();
                        }
                    });
                }
                if (listFilters) {
                    listFilters.forEach(function(listFilter) {
                        listFilter.addEventListener('change', function() {
                            var activeFilters = {};
                            listFilters.forEach(function(filter) {
                                var key = filter.getAttribute('data-list-filter');
                                var value = filter.value.trim().toLowerCase();
                                if (value) {
                                    activeFilters[key] = value;
                                }
                            });
                            list.filter(function(item) {
                                return Object.keys(activeFilters).every(function(key) {
                                    var itemValue = item.values()[key].toLowerCase();
                                    return itemValue.includes(activeFilters[key]);
                                });
                            });
                        });
                    });
                }

                //bulk-select
                if (bulkSelect) {
                    var bulkSelectInstance = window.BulkSelect.getInstance(bulkSelect);
                    bulkSelectInstance.attachRowNodes(list.items.map(function(item) {
                        return item.elm.querySelector('[data-bulk-select-row]');
                    }));
                    bulkSelect.addEventListener('change', function() {
                        if (list) {
                            if (bulkSelect.checked) {
                                list.items.forEach(function(item) {
                                    item.elm.querySelector('[data-bulk-select-row]').checked = true;
                                });
                            } else {
                                list.items.forEach(function(item) {
                                    item.elm.querySelector('[data-bulk-select-row]').checked = false;
                                });
                            }
                        }
                    });
                }
            });
        }
    }
};
var lottieInit = function lottieInit() {
    var lotties = document.querySelectorAll('.lottie');
    if (lotties.length) {
        lotties.forEach(function(item) {
            var options = utils.getData(item, 'options');
            window.bodymovin.loadAnimation(_objectSpread({
                container: item,
                path: '../img/animated-icons/warning-light.json',
                renderer: 'svg',
                loop: true,
                autoplay: true,
                name: 'Hello World'
            }, options));
        });
    }
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
        HTML: 'html',
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
/*                               noUiSlider                                   */
/* -------------------------------------------------------------------------- */
var nouisliderInit = function nouisliderInit() {
    if (window.noUiSlider) {
        var elements = document.querySelectorAll('[data-nouislider]');
        elements.forEach(function(item) {
            var sliderValue = document.querySelector('[data-nouislider-value]');
            var userOptions = utils.getData(item, 'nouislider');
            var defaultOptions = {
                start: [10],
                connect: [true, false],
                step: 1,
                range: {
                    min: [0],
                    max: [100]
                },
                tooltips: true
            };
            var options = window._.merge(defaultOptions, userOptions);
            window.noUiSlider.create(item, _objectSpread({}, options));
            sliderValue && item.noUiSlider.on('update', function(values, handle) {
                sliderValue.innerHTML = values[handle];
            });
        });
    }
};

/*-----------------------------------------------
|   Inline Player [plyr]
-----------------------------------------------*/

var plyrInit = function plyrInit() {
    if (window.Plyr) {
        var plyrs = document.querySelectorAll('.player');
        plyrs.forEach(function(plyr) {
            var userOptions = utils.getData(plyr, 'options');
            var defaultOptions = {
                captions: {
                    active: true
                }
            };
            var options = window._.merge(defaultOptions, userOptions);
            return new window.Plyr(plyr, options);
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

/*-----------------------------------------------
|  Quantity
-----------------------------------------------*/
var quantityInit = function quantityInit() {
    var Selector = {
        DATA_QUANTITY_BTN: '[data-quantity] [data-type]',
        DATA_QUANTITY: '[data-quantity]',
        DATA_QUANTITY_INPUT: '[data-quantity] input[type="number"]'
    };
    var Events = {
        CLICK: 'click'
    };
    var Attributes = {
        MIN: 'min'
    };
    var DataKey = {
        TYPE: 'type'
    };
    var quantities = document.querySelectorAll(Selector.DATA_QUANTITY_BTN);
    quantities.forEach(function(quantity) {
        quantity.addEventListener(Events.CLICK, function(e) {
            var el = e.currentTarget;
            var type = utils.getData(el, DataKey.TYPE);
            var numberInput = el.closest(Selector.DATA_QUANTITY).querySelector(Selector.DATA_QUANTITY_INPUT);
            var min = numberInput.getAttribute(Attributes.MIN);
            var value = parseInt(numberInput.value, 10);
            if (type === 'plus') {
                value += 1;
            } else {
                value = value > min ? value -= 1 : value;
            }
            numberInput.value = value;
        });
    });
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

/* -------------------------------------------------------------------------- */
/*                                 Scrollbars                                 */
/* -------------------------------------------------------------------------- */

var scrollbarInit = function scrollbarInit() {
    Array.prototype.forEach.call(document.querySelectorAll('.scrollbar-overlay'), function(el) {
        return new window.SimpleBar(el, {
            autoHide: true
        });
    });
};
var searchInit = function searchInit() {
    var Selectors = {
        SEARCH_DISMISS: '[data-bs-dismiss="search"]',
        DROPDOWN_TOGGLE: '[data-bs-toggle="dropdown"]',
        DROPDOWN_MENU: '.dropdown-menu',
        SEARCH_BOX: '.search-box',
        SEARCH_INPUT: '.search-input',
        SEARCH_TOGGLE: '[data-bs-toggle="search"]'
    };
    var ClassName = {
        SHOW: 'show'
    };
    var Attribute = {
        ARIA_EXPANDED: 'aria-expanded'
    };
    var Events = {
        CLICK: 'click',
        FOCUS: 'focus',
        SHOW_BS_DROPDOWN: 'show.bs.dropdown',
        SEARCH_CLOSE: 'search.close'
    };
    var hideSearchSuggestion = function hideSearchSuggestion(searchArea) {
        var el = searchArea.querySelector(Selectors.SEARCH_TOGGLE);
        var dropdownMenu = searchArea.querySelector(Selectors.DROPDOWN_MENU);
        if (!el || !dropdownMenu) return;
        el.setAttribute(Attribute.ARIA_EXPANDED, 'false');
        el.classList.remove(ClassName.SHOW);
        dropdownMenu.classList.remove(ClassName.SHOW);
    };
    var searchAreas = document.querySelectorAll(Selectors.SEARCH_BOX);
    var hideAllSearchAreas = function hideAllSearchAreas() {
        searchAreas.forEach(hideSearchSuggestion);
    };
    searchAreas.forEach(function(searchArea) {
        var input = searchArea.querySelector(Selectors.SEARCH_INPUT);
        var btnDropdownClose = searchArea.querySelector(Selectors.SEARCH_DISMISS);
        var dropdownMenu = searchArea.querySelector(Selectors.DROPDOWN_MENU);
        if (input) {
            input.addEventListener(Events.FOCUS, function() {
                hideAllSearchAreas();
                var el = searchArea.querySelector(Selectors.SEARCH_TOGGLE);
                if (!el || !dropdownMenu) return;
                el.setAttribute(Attribute.ARIA_EXPANDED, 'true');
                el.classList.add(ClassName.SHOW);
                dropdownMenu.classList.add(ClassName.SHOW);
            });
        }
        document.addEventListener(Events.CLICK, function(_ref12) {
            var target = _ref12.target;
            !searchArea.contains(target) && hideSearchSuggestion(searchArea);
        });
        btnDropdownClose && btnDropdownClose.addEventListener(Events.CLICK, function(e) {
            hideSearchSuggestion(searchArea);
            input.value = '';
            var event = new CustomEvent(Events.SEARCH_CLOSE);
            e.currentTarget.dispatchEvent(event);
        });
    });
    document.querySelectorAll(Selectors.DROPDOWN_TOGGLE).forEach(function(dropdown) {
        dropdown.addEventListener(Events.SHOW_BS_DROPDOWN, function() {
            hideAllSearchAreas();
        });
    });
};

/*-----------------------------------------------
|   Select2
-----------------------------------------------*/

var select2Init = function select2Init() {
    if (window.jQuery) {
        var $ = window.jQuery;
        var select2 = $('.selectpicker');
        select2.length && select2.each(function(index, value) {
            var $this = $(value);
            var options = $.extend({
                theme: 'bootstrap-5'
            }, $this.data('options'));
            $this.select2(options);
        });
    }
};

/* -------------------------------------------------------------------------- */
/*                                 SortableJS                                 */
/* -------------------------------------------------------------------------- */

var sortableInit = function sortableInit() {
    var getData = utils.getData;
    var sortableEl = document.querySelectorAll('[data-sortable]');
    var defaultOptions = {
        animation: 150,
        group: {
            name: 'shared'
        },
        delay: 100,
        delayOnTouchOnly: true,
        forceFallback: true,
        onStart: function onStart() {
            document.body.classList.add('sortable-dragging');
        },
        onEnd: function onEnd() {
            document.body.classList.remove('sortable-dragging');
        }
    };
    sortableEl.forEach(function(el) {
        var userOptions = getData(el, 'sortable');
        var options = window._.merge(defaultOptions, userOptions);
        return window.Sortable.create(el, options);
    });
};

/*-----------------------------------------------
|  Swiper
-----------------------------------------------*/
var swiperInit = function swiperInit() {
    var swipers = document.querySelectorAll('[data-swiper]');
    var navbarVerticalToggle = document.querySelector('.navbar-vertical-toggle');
    swipers.forEach(function(swiper) {
        var options = utils.getData(swiper, 'swiper');
        var thumbsOptions = options.thumb;
        var thumbsInit;
        if (thumbsOptions) {
            var thumbImages = swiper.querySelectorAll('img');
            var slides = '';
            thumbImages.forEach(function(img) {
                slides += "\n          <div class='swiper-slide '>\n            <img class='img-fluid rounded mt-1' src=".concat(img.src, " alt=''/>\n          </div>\n        ");
            });
            var thumbs = document.createElement('div');
            thumbs.setAttribute('class', 'swiper thumb');
            thumbs.innerHTML = "<div class='swiper-wrapper'>".concat(slides, "</div>");
            if (thumbsOptions.parent) {
                var parent = document.querySelector(thumbsOptions.parent);
                parent.parentNode.appendChild(thumbs);
            } else {
                swiper.parentNode.appendChild(thumbs);
            }
            thumbsInit = new window.Swiper(thumbs, thumbsOptions);
        }
        var swiperNav = swiper.querySelector('.swiper-nav');
        var newSwiper = new window.Swiper(swiper, _objectSpread(_objectSpread({}, options), {}, {
            navigation: {
                nextEl: swiperNav === null || swiperNav === void 0 ? void 0 : swiperNav.querySelector('.swiper-button-next'),
                prevEl: swiperNav === null || swiperNav === void 0 ? void 0 : swiperNav.querySelector('.swiper-button-prev')
            },
            thumbs: {
                swiper: thumbsInit
            }
        }));
        if (navbarVerticalToggle) {
            navbarVerticalToggle.addEventListener('navbar.vertical.toggle', function() {
                newSwiper.update();
            });
        }
    });
};

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
