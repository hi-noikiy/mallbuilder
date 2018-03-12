/*pub-1|2013-03-12 17:06:52*/
KISSY.add("category/category",
function (C) {
    var C = KISSY,
    O = C.DOM,
    P = C.Event,
    B = window,
    Q = document,
    I = (C.UA.ie == 6);
    var D = "selected",
    N = "fn-hide",
    L = "mouseenter",
    E = "mouseleave";
    var H = {
        showDelay: 0.1,
        hideDelay: 0.1,
        viewId: null,
        subViews: null,
        triggers: null,
        lazyload: false,
        dataUrl: null
    };
    var G = "755px";
    var M = 10;
    var K = 10;
    var R = true;
    var F = false;
    function J(S, U) {
        var T = this;
        if (!(T instanceof J)) {
            return new J(S, U)
        }
        T.container = C.get(S);
        T.config = C.merge(H, U || {});
        T.config.viewer = C.get(U.viewId, T.container);
        T.triggers = O.query(U.triggers, T.container);
        T._init()
    }
    function A(S, U) {
        for (var T = 0; T < S.length; T = T + 1) {
            if (S[T] === U) {
                return T
            }
        }
        return -1
    }
    C.mix(J.prototype, {
        changeTrigger: function (S) {
            var T = this;
            var U = T.triggers;
            C.each(U,
            function (V) {
                O.removeClass(V, D)
            });
            O.addClass(U[S], D)
        },
        changeView: function (T) {
            var U = this;
            C.each(U.subViews,
            function (V) {
                O.addClass(V, N)
            });
            O.removeClass(T, N);
            var S = O.height(T);
            O.height(U.viewer, (S + M + K) + "px");
            U.resetPostion();
            if (!U.shadow) {
                U.shadow = O.get(".shadow", U.viewer)
            }
            O.height(U.shadow, (S + K) + "px")
        },
        show: function () {
            var V = this,
            W = V.config;
            var U = V.subViews;
            var S = W.idx;
            var T = V.isDataReady ? U[S] : U[0];
            var X = O.width(V.viewer);
            if (V.hideTimer) {
                clearTimeout(V.hideTimer)
            }
            if (R && X == 0) {
                F = false;
                R = false;
                if (V.expandTimer) {
                    clearTimeout(V.expandTimer)
                }
                V.expandTimer = setTimeout(function () {
                    V.changeTrigger(S);
                    V.changeView(T);
                    if (!I) {
                        new C.Anim(V.viewer, {
                            width: G
                        },
                        0.2, "linear").run()
                    } else {
                        O.width(V.viewer, G)
                    }
                },
                W.showDelay * 1000)
            } else {
                if (V.resetTimer) {
                    clearTimeout(V.resetTimer)
                }
                V.resetTimer = setTimeout(function () {
                    if (V.status == "visible") {
                        V.changeTrigger(S);
                        V.changeView(T)
                    }
                },
                W.showDelay * 1000)
            }
        },
        hide: function (S) {
            var T = this,
            U = T.config,
            V = T.triggers;
            T.status = "hidden";
            R = true;
            if (T.viewer) {
                if (T.expandTimer) {
                    clearTimeout(T.expandTimer)
                }
                if (T.hideTimer) {
                    clearTimeout(T.hideTimer)
                }
                T.hideTimer = setTimeout(function () {
                    C.each(V,
                    function (W) {
                        O.removeClass(W, D)
                    });
                    O.css(T.viewer, {
                        width: "0"
                    })
                },
                U.hideDelay * 1000)
            }
        },
        resetPostion: function () {
            var V = this.triggers[this.config.idx],
            d = O.offset(V).top,
            Z = O.offset(this.container),
            b = O.height(V),
            W = O.height(this.viewer),
            f = O.width(V),
            a = O.viewportHeight(),
            T = O.scrollTop(),
            e = d - T,
            U = a - W - e,
            g = a - e,
            S = d - Z.top;
            if (U <= 0) {
                U = Math.abs(U);
                var c = 20;
                if (g > b) {
                    var Y = g - b;
                    if (Y > c) {
                        S = S - U - c + 7
                    } else {
                        S = S - U
                    }
                } else {
                    S = S - U + c + g + 20
                }
            }
            if (S < 30) {
                S = 0
            }
            var X = C.UA.ie ? 0 : M;
            if (!I && F) {
                new C.Anim(this.viewer, {
                    top: (S - X) + "px"
                },
                0.3, "easeOutStrong").run()
            } else {
                this.viewer.style.top = (S - X) + "px";
                F = true
            }
        },
        _load: function (U) {
            var S = this,
            T = S.config;
            C.IO.get(U, I ? {
                t: +new Date
            } : {},
            function (V) {
                if (!V) {
                    return
                }
                newViewer = O.create(V);
                O.html(S.viewer, O.html(newViewer));
                S.subViews = O.query(T.subViews, S.viewer);
                S.shadow = O.get(".shadow", S.viewer);
                S.isDataReady = true;
                if (S.status == "visible") {
                    R = false;
                    S.show()
                }
            },
            "text")
        },
        _init: function () {
            var S = this,
            T = S.config;
            C.each(S.triggers,
            function (U) {
                P.on(U, L,
                function (W) {
                    W.halt();
                    var V = A(S.triggers, U);
                    T.idx = V;
                    S.status = "visible";
                    if (!S.viewer) {
                        if (!T.viewer && T.lazyload) {
                            S.viewer = O.create('<div id="J_SubCategory" class="subCategory"><div class="shadow"></div><div class="subView j_SubView" style="height:520px; text-align:center; line-height:520px;">loading...</div></div>');
                            S.container.appendChild(S.viewer);
                            S.subViews = O.query(T.subViews, S.viewer);
                            S.isDataReady = false;
                            S._load(T.dataUrl)
                        } else {
                            S.viewer = T.viewer;
                            S.subViews = O.query(T.subViews, S.viewer);
                            S.isDataReady = true
                        }
                    }
                    S.show()
                });
                P.on(U, E,
                function (V) {
                    S.status = "triggerLeave"
                })
            });
            P.on(S.container, E,
            function (U) {
                S.hide(T.idx)
            })
        }
    });
    KISSY.Category = J;
    return J
});
(function (A) {
    var A = KISSY,
    F = A.DOM,
    C = A.Event,
    B = A.UA;
    A.use("category/category",
    function (D, E) {
        new D.Category("#J_Category", {
            viewId: "#J_SubCategory",
            subViews: ".j_SubView",
            triggers: ".j_MenuItem"
        })
    });
})(KISSY);
