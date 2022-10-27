/*! Built with http://stenciljs.com */
(function(Context,namespace,hydratedCssClass,resourcesUrl,s){"use strict";
s=document.querySelector("script[data-namespace='ionicons']");if(s){resourcesUrl=s.getAttribute('data-resources-url');}
(function(t,e,n,o){"use strict";function i(t,e){return"sc-"+t.t+(e&&e!==N?"-"+e:"")}function c(t,e){return t+(e?"-h":"-s")}function f(t,e,n,o){let c=n.t+o.mode,f=n[c];if((2===n.e||1===n.e&&!t.o.n)&&(o["s-sc"]=f?i(n,o.mode):i(n)),f||(f=n[c=n.t+N]),f){let i=e.i.head;if(e.n)if(1===n.e)i=o.shadowRoot;else{let t=o;for(;t=e.c(t);)if(t.host&&t.host.shadowRoot){i=t.host.shadowRoot;break}}let r=t.f.get(i);if(r||t.f.set(i,r={}),!r[c]){let t;{t=f.content.cloneNode(!0),r[c]=!0;const n=i.querySelectorAll("[data-styles]");e.r(i,t,n.length&&n[n.length-1].nextSibling||i.firstChild)}}}}function r(t,e,n,o="boolean"==typeof n){const i=e!==(e=e.replace(/^xlink\:?/,""));null==n||o&&(!n||"false"===n)?i?t.removeAttributeNS(D,T(e)):t.removeAttribute(e):"function"!=typeof n&&(n=o?"":n.toString(),i?t.setAttributeNS(D,T(e),n):t.setAttribute(e,n))}function s(t,e,n,o,i,c,f){if("class"!==n||c)if("style"===n){for(const t in o)i&&null!=i[t]||(/-/.test(t)?e.style.s(t):e.style[t]="");for(const t in i)o&&i[t]===o[t]||(/-/.test(t)?e.style.setProperty(t,i[t]):e.style[t]=i[t])}else if("o"!==n[0]||"n"!==n[1]||!/[A-Z]/.test(n[2])||n in e)if("list"!==n&&"type"!==n&&!c&&(n in e||-1!==["object","function"].indexOf(typeof i)&&null!==i)){const o=t.l(e);o&&o.u&&o.u[n]?(u(e,n,i),f&&o.u[n].a&&r(e,o.u[n].p,i,4===o.u[n].d)):"ref"!==n&&(u(e,n,null==i?"":i),null!=i&&!1!==i||t.o.v(e,n))}else null!=i&&"key"!==n?r(e,n,i):(c||t.o.m(e,n)&&(null==i||!1===i))&&t.o.v(e,n);else n=T(n)in e?T(n.substring(2)):T(n[2])+n.substring(3),i?i!==o&&t.o.b(e,n,i):t.o.y(e,n);else if(o!==i){const t=l(o),n=l(i),c=t.filter(t=>!n.includes(t)),f=l(e.className).filter(t=>!c.includes(t)),r=n.filter(e=>!t.includes(e)&&!f.includes(e));f.push(...r),e.className=f.join(" ")}}function l(t){return null==t||""===t?[]:t.trim().split(/\s+/)}function u(t,e,n){try{t[e]=n}catch(t){}}function a(t,e,n,o,i){const c=11===n.w.nodeType&&n.w.host?n.w.host:n.w,f=e&&e.vattrs||S,r=n.vattrs||S;for(i in f)r&&null!=r[i]||null==f[i]||s(t,c,i,f[i],void 0,o,n.g);for(i in r)i in f&&r[i]===("value"===i||"checked"===i?c[i]:f[i])||s(t,c,i,f[i],r[i],o,n.g)}function p(t,e){function n(i,c,f,r,s,p,y,h,w){if(h=c.vchildren[f],l||(d=!0,"slot"===h.vtag&&(u&&e.M(r,u+"-s"),h.vchildren?h.k=!0:h.C=!0)),R(h.vtext))h.w=e.j(h.vtext);else if(h.C)h.w=e.j("");else{if(p=h.w=P||"svg"===h.vtag?e.O("http://www.w3.org/2000/svg",h.vtag):e.x(h.k?"slot-fb":h.vtag),t.W(p)&&t.N.delete(b),P="svg"===h.vtag||"foreignObject"!==h.vtag&&P,a(t,null,h,P),R(u)&&p["s-si"]!==u&&e.M(p,p["s-si"]=u),h.vchildren)for(s=0;s<h.vchildren.length;++s)(y=n(i,h,s,p))&&e.S(p,y);"svg"===h.vtag&&(P=!1)}return h.w["s-hn"]=m,(h.k||h.C)&&(h.w["s-sr"]=!0,h.w["s-cr"]=v,h.w["s-sn"]=h.vname||"",(w=i&&i.vchildren&&i.vchildren[f])&&w.vtag===h.vtag&&i.w&&o(i.w)),h.w}function o(n,i,c,f){t.A=!0;const l=e.R(n);for(c=l.length-1;c>=0;c--)(f=l[c])["s-hn"]!==m&&f["s-ol"]&&(e.T(f),e.r(s(f),f,r(f)),e.T(f["s-ol"]),f["s-ol"]=null,d=!0),i&&o(f,i);t.A=!1}function i(t,o,i,c,f,s,l,u){const a=t["s-cr"];for((l=a&&e.c(a)||t).shadowRoot&&e.L(l)===m&&(l=l.shadowRoot);f<=s;++f)c[f]&&(u=R(c[f].vtext)?e.j(c[f].vtext):n(null,i,f,t))&&(c[f].w=u,e.r(l,u,r(o)))}function c(t,n,i,c){for(;n<=i;++n)R(t[n])&&(c=t[n].w,p=!0,c["s-ol"]?e.T(c["s-ol"]):o(c,!0),e.T(c))}function f(t,e){return t.vtag===e.vtag&&t.vkey===e.vkey&&("slot"!==t.vtag||t.vname===e.vname)}function r(t){return t&&t["s-ol"]?t["s-ol"]:t}function s(t){return e.c(t["s-ol"]?t["s-ol"]:t)}let l,u,p,d,v,m,b;const y=[];return function h(w,$,g,M,k,C,j,O,x,W,E,N){if(b=w,m=e.L(b),v=b["s-cr"],l=M,u=b["s-sc"],d=p=!1,function l(u,p,d){const v=p.w=u.w,m=u.vchildren,b=p.vchildren;P=p.w&&R(e.D(p.w))&&void 0!==p.w.ownerSVGElement,P="svg"===p.vtag||"foreignObject"!==p.vtag&&P,R(p.vtext)?(d=v["s-cr"])?e.P(e.c(d),p.vtext):u.vtext!==p.vtext&&e.P(v,p.vtext):("slot"!==p.vtag&&a(t,u,p,P),R(m)&&R(b)?function y(t,u,a,p,d,v,m,b){let y=0,h=0,w=u.length-1,$=u[0],g=u[w],M=p.length-1,k=p[0],C=p[M];for(;y<=w&&h<=M;)if(null==$)$=u[++y];else if(null==g)g=u[--w];else if(null==k)k=p[++h];else if(null==C)C=p[--M];else if(f($,k))l($,k),$=u[++y],k=p[++h];else if(f(g,C))l(g,C),g=u[--w],C=p[--M];else if(f($,C))"slot"!==$.vtag&&"slot"!==C.vtag||o(e.c($.w)),l($,C),e.r(t,$.w,e.q(g.w)),$=u[++y],C=p[--M];else if(f(g,k))"slot"!==$.vtag&&"slot"!==C.vtag||o(e.c(g.w)),l(g,k),e.r(t,g.w,$.w),g=u[--w],k=p[++h];else{for(d=null,v=y;v<=w;++v)if(u[v]&&R(u[v].vkey)&&u[v].vkey===k.vkey){d=v;break}R(d)?((b=u[d]).vtag!==k.vtag?m=n(u&&u[h],a,d,t):(l(b,k),u[d]=void 0,m=b.w),k=p[++h]):(m=n(u&&u[h],a,h,t),k=p[++h]),m&&e.r(s($.w),m,r($.w))}y>w?i(t,null==p[M+1]?null:p[M+1].w,a,p,h,M):h>M&&c(u,y,w)}(v,m,p,b):R(b)?(R(u.vtext)&&e.P(v,""),i(v,null,p,b,0,b.length-1)):R(m)&&c(m,0,m.length-1)),P&&"svg"===p.vtag&&(P=!1)}($,g),d){for(function t(n,o,i,c,f,r,s,l,u,a){for(f=0,r=(o=e.R(n)).length;f<r;f++){if((i=o[f])["s-sr"]&&(c=i["s-cr"]))for(l=e.R(e.c(c)),u=i["s-sn"],s=l.length-1;s>=0;s--)(c=l[s])["s-cn"]||c["s-nr"]||c["s-hn"]===i["s-hn"]||((3===(a=e.B(c))||8===a)&&""===u||1===a&&null===e.I(c,"slot")&&""===u||1===a&&e.I(c,"slot")===u)&&(y.some(t=>t.F===c)||(p=!0,c["s-sn"]=u,y.push({H:i,F:c})));1===e.B(i)&&t(i)}}(g.w),j=0;j<y.length;j++)(O=y[j]).F["s-ol"]||((x=e.j(""))["s-nr"]=O.F,e.r(e.c(O.F),O.F["s-ol"]=x,O.F));for(t.A=!0,j=0;j<y.length;j++){for(O=y[j],E=e.c(O.H),N=e.q(O.H),x=O.F["s-ol"];x=e.U(x);)if((W=x["s-nr"])&&W&&W["s-sn"]===O.F["s-sn"]&&E===e.c(W)&&(W=e.q(W))&&W&&!W["s-nr"]){N=W;break}(!N&&E!==e.c(O.F)||e.q(O.F)!==N)&&O.F!==N&&(e.T(O.F),e.r(E,O.F,N))}t.A=!1}return p&&function t(n,o,i,c,f,r,s,l){for(c=0,f=(i=e.R(n)).length;c<f;c++)if(o=i[c],1===e.B(o)){if(o["s-sr"])for(s=o["s-sn"],o.hidden=!1,r=0;r<f;r++)if(i[r]["s-hn"]!==o["s-hn"])if(l=e.B(i[r]),""!==s){if(1===l&&s===e.I(i[r],"slot")){o.hidden=!0;break}}else if(1===l||3===l&&""!==e.Q(i[r]).trim()){o.hidden=!0;break}t(o)}}(g.w),y.length=0,g}}function d(t,e){t&&(t.vattrs&&t.vattrs.ref&&t.vattrs.ref(e?null:t.w),t.vchildren&&t.vchildren.forEach(t=>{d(t,e)}))}function v(t,e,n,o,i){const c=t.B(e);let f,r,s,l;if(i&&1===c){(r=t.I(e,E))&&(s=r.split("."))[0]===o&&((l={}).vtag=t.L(l.w=e),n.vchildren||(n.vchildren=[]),n.vchildren[s[1]]=l,n=l,i=""!==s[2]);for(let c=0;c<e.childNodes.length;c++)v(t,e.childNodes[c],n,o,i)}else 3===c&&(f=e.previousSibling)&&8===t.B(f)&&"s"===(s=t.Q(f).split("."))[0]&&s[1]===o&&((l={vtext:t.Q(e)}).w=e,n.vchildren||(n.vchildren=[]),n.vchildren[s[2]]=l)}function m(t,e){let n,o,i=null,c=!1,f=!1;for(var r=arguments.length;r-- >2;)q.push(arguments[r]);for(;q.length>0;){let e=q.pop();if(e&&void 0!==e.pop)for(r=e.length;r--;)q.push(e[r]);else"boolean"==typeof e&&(e=null),(f="function"!=typeof t)&&(null==e?e="":"number"==typeof e?e=String(e):"string"!=typeof e&&(f=!1)),f&&c?i[i.length-1].vtext+=e:null===i?i=[f?{vtext:e}:e]:i.push(f?{vtext:e}:e),c=f}if(null!=e){if(e.className&&(e.class=e.className),"object"==typeof e.class){for(r in e.class)e.class[r]&&q.push(r);e.class=q.join(" "),q.length=0}null!=e.key&&(n=e.key),null!=e.name&&(o=e.name)}return"function"==typeof t?t(e,i||[],B):{vtag:t,vchildren:i,vtext:void 0,vattrs:e,vkey:n,vname:o,w:void 0,g:!1}}function b(t){return{vtag:t.vtag,vchildren:t.vchildren,vtext:t.vtext,vattrs:t.vattrs,vkey:t.vkey,vname:t.vname}}function y(t){const[e,n,,o,i,c]=t,f={color:{p:"color"}};if(o)for(let t=0;t<o.length;t++){const e=o[t];f[e[0]]={Z:e[1],a:!!e[2],p:"string"==typeof e[3]?e[3]:e[3]?e[0]:0,d:e[4]}}return{t:e,z:n,u:Object.assign({},f),e:i,G:c?c.map(h):void 0}}function h(t){return{J:t[0],K:t[1],V:!!t[2],X:!!t[3],Y:!!t[4]}}function w(t,e){if(R(e)&&"object"!=typeof e&&"function"!=typeof e){if(t===Boolean||4===t)return"false"!==e&&(""===e||!!e);if(t===Number||8===t)return parseFloat(e);if(t===String||2===t)return e.toString()}return e}function $(t,e){t._.add(e),t.tt.has(e)||(t.tt.set(e,!0),t.et?t.queue.write(()=>g(t,e)):t.queue.tick(()=>g(t,e)))}async function g(t,e,n,o,i){if(t.tt.delete(e),!t.nt.has(e)){if(o=t.ot.get(e)){if(o)try{o.componentWillUpdate&&await o.componentWillUpdate()}catch(n){t.it(n,5,e)}}else{if((i=t.ct.get(e))&&!i["s-rn"])return void(i["s-rc"]=i["s-rc"]||[]).push(()=>{g(t,e)});if(o=function f(t,e,n,o,i,c,r){try{o=new(i=t.l(e).ft),function s(t,e,n,o,i){t.rt.set(o,n),t.st.has(n)||t.st.set(n,{}),Object.entries(Object.assign({color:{type:String}},e.properties,{mode:{type:String}})).forEach(([e,c])=>{(function f(t,e,n,o,i,c,r,s){if(e.type||e.state){const f=t.st.get(n);e.state||(!e.attr||void 0!==f[i]&&""!==f[i]||(r=c&&c.lt)&&R(s=r[e.attr])&&(f[i]=w(e.type,s)),n.hasOwnProperty(i)&&(void 0===f[i]&&(f[i]=w(e.type,n[i])),"mode"!==i&&delete n[i])),o.hasOwnProperty(i)&&void 0===f[i]&&(f[i]=o[i]),e.watchCallbacks&&(f[I+i]=e.watchCallbacks.slice()),C(o,i,function l(e){return(e=t.st.get(t.rt.get(this)))&&e[i]},function u(n,o){(o=t.rt.get(this))&&(e.state||e.mutable)&&M(t,o,i,n)})}else if(e.elementRef)k(o,i,n);else if(e.context){const c=t.ut(e.context);void 0!==c&&k(o,i,c.at&&c.at(n)||c)}})(t,c,n,o,e,i)})}(t,i,e,o,n)}catch(n){o={},t.it(n,7,e,!0)}return t.ot.set(e,o),o}(t,e,t.pt.get(e)))try{o.componentWillLoad&&await o.componentWillLoad()}catch(n){t.it(n,3,e)}}(function r(t,e,n,o){try{const i=e.ft.host,f=e.ft.encapsulation,r="shadow"===f&&t.o.n;let s,l=n;if(s=function i(t,e,n){return t&&Object.keys(t).forEach(o=>{t[o].reflectToAttr&&((n=n||{})[o]=e[o])}),n}(e.ft.properties,o),r&&(l=n.shadowRoot),!n["s-rn"]){t.dt(t,t.o,e,n);const o=n["s-sc"];o&&(t.o.M(n,c(o,!0)),"scoped"===f&&t.o.M(n,c(o)))}if(o.render||o.hostData||i||s){t.vt=!0;const e=o.render&&o.render();let i;i=o.hostData&&o.hostData(),s&&(i=i?Object.assign(i,s):s),t.vt=!1;const c=t.mt.get(n)||{};c.w=l;const u=m(null,i,e);u.g=!0,t.mt.set(n,t.render(n,c,u,r,f))}n["s-rn"]=!0,n["s-rc"]&&(n["s-rc"].forEach(t=>t()),n["s-rc"]=null)}catch(e){t.vt=!1,t.it(e,8,n,!0)}})(t,t.l(e),e,o),e["s-init"]()}}function M(t,e,n,o,i){let c=t.st.get(e);c||t.st.set(e,c={});const f=c[n];if(o!==f&&(c[n]=o,i=t.ot.get(e))){{const t=c[I+n];if(t)for(let e=0;e<t.length;e++)try{i[t[e]].call(i,o,f,n)}catch(t){}}!t.vt&&e["s-rn"]&&$(t,e)}}function k(t,e,n){Object.defineProperty(t,e,{configurable:!0,value:n})}function C(t,e,n,o){Object.defineProperty(t,e,{configurable:!0,get:n,set:o})}function j(t,e,n,o,i,c){if(t._.delete(e),(i=t.ct.get(e))&&((o=i["s-ld"])&&((n=o.indexOf(e))>-1&&o.splice(n,1),o.length||i["s-init"]&&i["s-init"]()),t.ct.delete(e)),t.bt.length&&!t._.size)for(;c=t.bt.shift();)c()}function O(t,e,n,o){if(n.connectedCallback=function(){(function n(t,e,o){t.nt.delete(o),t.yt.has(o)||(t._.add(o),t.yt.set(o,!0),o["s-id"]||(o["s-id"]=t.ht()),function i(t,e,n){for(n=e;n=t.o.D(n);)if(t.W(n)){t.N.has(e)||(t.ct.set(e,n),(n["s-ld"]=n["s-ld"]||[]).push(e));break}}(t,o),t.queue.tick(()=>{t.pt.set(o,function n(t,e,o,i,c){return o.mode||(o.mode=t.wt(o)),o["s-cr"]||t.I(o,W)||t.n&&1===e.e||(o["s-cr"]=t.j(""),o["s-cr"]["s-cn"]=!0,t.r(o,o["s-cr"],t.R(o)[0])),t.n||1!==e.e||(o.shadowRoot=o),1===e.e&&t.n&&!o.shadowRoot&&t.$t(o,{mode:"open"}),i={gt:o["s-id"],lt:{}},e.u&&Object.keys(e.u).forEach(n=>{(c=e.u[n].p)&&(i.lt[c]=t.I(o,c))}),i}(t.o,e,o)),t.Mt(e,o)}))})(t,e,this)},n.disconnectedCallback=function(){(function e(t,n){if(!t.A&&function o(t,e){for(;e;){if(!t.c(e))return 9!==t.B(e);e=t.c(e)}}(t.o,n)){t.nt.set(n,!0),j(t,n),d(t.mt.get(n),!0),t.o.y(n),t.kt.delete(n);{const e=t.ot.get(n);e&&e.componentDidUnload&&e.componentDidUnload()}[t.ct,t.Ct,t.pt].forEach(t=>t.delete(n))}})(t,this)},n["s-init"]=function(){(function e(t,n,o,i,c,f){if(t.ot.get(n)&&!t.nt.has(n)&&(!n["s-ld"]||!n["s-ld"].length)){t.N.set(n,!0),t.jt.has(n)||(t.jt.set(n,!0),n["s-ld"]=void 0,t.o.M(n,o));try{d(t.mt.get(n)),(c=t.Ct.get(n))&&(c.forEach(t=>t(n)),t.Ct.delete(n))}catch(e){t.it(e,4,n)}j(t,n)}})(t,this,o)},n.forceUpdate=function(){$(t,this)},e.u){const o=Object.entries(e.u);{let t={};o.forEach(([e,{p:n}])=>{n&&(t[n]=e)}),t=Object.assign({},t),n.attributeChangedCallback=function(e,n,o){(function i(t,e,n,o){const i=t[T(n)];i&&(e[i]=o)})(t,this,e,o)}}(function i(t,e,n){e.forEach(([e,o])=>{const i=o.Z;3&i?C(n,e,function n(){return(t.st.get(this)||{})[e]},function n(i){M(t,this,e,w(o.d,i))}):32===i&&k(n,e,L)})})(t,o,n)}}function x(t,e,n,o){return function(){const i=arguments;return function c(t,e,n){let o=e[n];const i=t.i.body;return i?(o||(o=i.querySelector(n)),o||(o=e[n]=t.x(n),t.S(i,o)),o.componentOnReady()):Promise.resolve()}(t,e,n).then(t=>t[o].apply(t,i))}}const W="ssrv",E="ssrc",N="$",S={},A={enter:13,escape:27,space:32,tab:9,left:37,up:38,right:39,down:40},R=t=>null!=t,T=t=>t.toLowerCase(),L=()=>{},D="http://www.w3.org/1999/xlink";let P=!1;const q=[],B={forEach:(t,e)=>{t.forEach((t,n,o)=>e(b(t),n,o))},map:(t,e)=>t.map((t,n,o)=>(function i(t){return{vtag:t.vtag,vchildren:t.vchildren,vtext:t.vtext,vattrs:t.vattrs,vkey:t.vkey,vname:t.vname}})(e(b(t),n,o)))},I="wc-";(function F(t,e,n,o,i,c){function r(t,e){const o=t.t;n.customElements.get(o)||(O(w,s[o]=t,e.prototype,c),e.observedAttributes=Object.values(t.u).map(t=>t.p).filter(t=>!!t),n.customElements.define(t.t,e))}const s={html:{}},l={},u=n[t]=n[t]||{},a=function d(t,e,n){t.ael||(t.ael=((t,e,n,o)=>t.addEventListener(e,n,o)),t.rel=((t,e,n,o)=>t.removeEventListener(e,n,o)));const o=new WeakMap,i={i:n,n:!!n.documentElement.attachShadow,Ot:!1,B:t=>t.nodeType,x:t=>n.createElement(t),O:(t,e)=>n.createElementNS(t,e),j:t=>n.createTextNode(t),xt:t=>n.createComment(t),r:(t,e,n)=>t.insertBefore(e,n),T:t=>t.remove(),S:(t,e)=>t.appendChild(e),M:(t,e)=>{t.classList.add(e)},R:t=>t.childNodes,c:t=>t.parentNode,q:t=>t.nextSibling,U:t=>t.previousSibling,L:t=>T(t.nodeName),Q:t=>t.textContent,P:(t,e)=>t.textContent=e,I:(t,e)=>t.getAttribute(e),Wt:(t,e,n)=>t.setAttribute(e,n),Et:(t,e,n,o)=>t.setAttributeNS(e,n,o),v:(t,e)=>t.removeAttribute(e),m:(t,e)=>t.hasAttribute(e),wt:e=>e.getAttribute("mode")||(t.Context||{}).mode,Nt:(t,o)=>"child"===o?t.firstElementChild:"parent"===o?i.D(t):"body"===o?n.body:"document"===o?n:"window"===o?e:t,b:(e,n,c,f,r,s,l,u)=>{const a=n;let p=e,d=o.get(e);if(d&&d[a]&&d[a](),"string"==typeof s?p=i.Nt(e,s):"object"==typeof s?p=s:(u=n.split(":")).length>1&&(p=i.Nt(e,u[0]),n=u[1]),!p)return;let v=c;(u=n.split(".")).length>1&&(n=u[0],v=(t=>{t.keyCode===A[u[1]]&&c(t)})),l=i.Ot?{capture:!!f,passive:!!r}:!!f,t.ael(p,n,v,l),d||o.set(e,d={}),d[a]=(()=>{p&&t.rel(p,n,v,l),d[a]=null})},y:(t,e)=>{const n=o.get(t);n&&(e?n[e]&&n[e]():Object.keys(n).forEach(t=>{n[t]&&n[t]()}))},St:(t,n,o)=>t&&t.dispatchEvent(new e.CustomEvent(n,o)),D:(t,e)=>(e=i.c(t))&&11===i.B(e)?e.host:e,$t:(t,e)=>t.attachShadow(e)};return i}(u,n,o);e.isServer=e.isPrerender=!(e.isClient=!0),e.window=n,e.location=n.location,e.document=o,e.resourcesUrl=e.publicPath=i,u.h=m,u.Context=e;const b=n["s-defined"]=n["s-defined"]||{};let h=0;const w={o:a,At:r,Rt:e.emit,l:t=>s[a.L(t)],ut:t=>e[t],isClient:!0,W:t=>!(!b[a.L(t)]&&!w.l(t)),ht:()=>t+h++,it:(t,e,n)=>void 0,Tt:t=>(function e(t,n,o){return{create:x(t,n,o,"create"),componentOnReady:x(t,n,o,"componentOnReady")}})(a,l,t),queue:e.queue=function g(t,e){function n(e){return n=>{e.push(n),d||(d=!0,t.raf(c))}}function o(t){for(let e=0;e<t.length;e++)try{t[e](f())}catch(t){}t.length=0}function i(t,e){let n,o=0;for(;o<t.length&&(n=f())<e;)try{t[o++](n)}catch(t){}o===t.length?t.length=0:0!==o&&t.splice(0,o)}function c(){p++,o(l);const e=f()+7*Math.ceil(p*(1/22));i(u,e),i(a,e),u.length>0&&(a.push(...u),u.length=0),(d=l.length+u.length+a.length>0)?t.raf(c):p=0}const f=()=>e.performance.now(),r=Promise.resolve(),s=[],l=[],u=[],a=[];let p=0,d=!1;return t.raf||(t.raf=e.requestAnimationFrame.bind(e)),{tick(t){s.push(t),1===s.length&&r.then(()=>o(s))},read:n(l),write:n(u)}}(u,n),Mt:function M(t,e,n){if(t.ft)$(w,e);else{const n="string"==typeof t.z?t.z:t.z[e.mode],o=!a.n;import(i+n+(o?".sc":"")+".entry.js").then(n=>{try{t.ft=n[(t=>T(t).split("-").map(t=>t.charAt(0).toUpperCase()+t.slice(1)).join(""))(t.t)],function o(t,e,n,i,c){if(i){const n=e.t+(c||N);if(!e[n]){const o=t.x("template");e[n]=o,o.innerHTML=`<style>${i}</style>`,t.S(t.i.head,o)}}}(a,t,t.e,t.ft.style,t.ft.styleMode),$(w,e)}catch(e){t.ft=class{}}},t=>void 0)}},vt:!1,et:!1,A:!1,dt:f,ct:new WeakMap,f:new WeakMap,yt:new WeakMap,kt:new WeakMap,jt:new WeakMap,N:new WeakMap,rt:new WeakMap,pt:new WeakMap,ot:new WeakMap,nt:new WeakMap,tt:new WeakMap,Ct:new WeakMap,Lt:new WeakMap,mt:new WeakMap,st:new WeakMap,_:new Set,bt:[]};u.onReady=(()=>new Promise(t=>w.queue.write(()=>w._.size?w.bt.push(t):t()))),w.render=p(w,a);const k=a.i.documentElement;k["s-ld"]=[],k["s-rn"]=!0,k["s-init"]=(()=>{w.N.set(k,u.loaded=w.et=!0),a.St(n,"appload",{detail:{namespace:t}})}),function C(t,e,n){const o=n.querySelectorAll(`[${W}]`),i=o.length;let c,f,r,s,l,u;if(i>0)for(t.N.set(n,!0),s=0;s<i;s++)for(c=o[s],f=e.I(c,W),(r={}).vtag=e.L(r.w=c),t.mt.set(c,r),l=0,u=c.childNodes.length;l<u;l++)v(e,c.childNodes[l],r,f,!0)}(w,a,k),(u.components||[]).map(y).forEach(t=>r(t,class extends HTMLElement{})),function j(t,e,n,o,i,c){if(e.componentOnReady=((e,n)=>{if(!e.nodeName.includes("-"))return n(null),!1;const o=t.l(e);if(o)if(t.N.has(e))n(e);else{const o=t.Ct.get(e)||[];o.push(n),t.Ct.set(e,o)}return!!o}),i){for(c=i.length-1;c>=0;c--)e.componentOnReady(i[c][0],i[c][1])&&i.splice(c,1);for(c=0;c<o.length;c++)if(!n[o[c]].componentOnReady)return;for(c=0;c<i.length;c++)i[c][1](null);i.length=0}}(w,u,n,n["s-apps"],n["s-cr"]),u.initialized=!0})(o,n,t,e,resourcesUrl,hydratedCssClass)})(window,document,Context,namespace);
})({},"ionicons","hydrated");