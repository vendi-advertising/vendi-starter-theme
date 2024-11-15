/*!
 *  @preserve
 *  
 *  @module      iframe-resizer/child 5.3.0 (iife) 
 *
 *  @license     GPL-3.0 for non-commercial use only.
 *               For commercial use, you must purchase a license from
 *               https://iframe-resizer.com/pricing
 * 
 *  @description Keep same and cross domain iFrames sized to their content 
 *
 *  @author      David J. Bradshaw <info@iframe-resizer.com>
 * 
 *  @see         {@link https://iframe-resizer.com}
 * 
 *  @copyright  (c) 2013 - 2024, David J. Bradshaw. All rights reserved.
 */


!function(){"use strict";const e="5.3.0",t=10,n="data-iframe-size",o="data-iframe-overflow",i="bottom",r="right",a="resizeParent",l=(e,t,n,o)=>e.addEventListener(t,n,o||!1),s=(e,t,n)=>e.removeEventListener(t,n,!1),c=["<iy><yi>Puchspk Spjluzl Rlf</><iy><iy>","<iy><yi>Tpzzpun Spjluzl Rlf</><iy><iy>","Aopz spiyhyf pz hchpshisl dpao ivao Jvttlyjphs huk Vwlu-Zvbyjl spjluzlz.<iy><iy><i>Jvttlyjphs Spjluzl</><iy>Mvy jvttlyjphs bzl, <p>pmyhtl-ylzpgly</> ylxbpylz h svd jvza vul aptl spjluzl mll. Mvy tvyl pumvythapvu cpzpa <b>oaawz://pmyhtl-ylzpgly.jvt/wypjpun</>.<iy><iy><i>Vwlu Zvbyjl Spjluzl</><iy>Pm fvb hyl bzpun aopz spiyhyf pu h uvu-jvttlyjphs vwlu zvbyjl wyvqlja aolu fvb jhu bzl pa mvy myll bukly aol alytz vm aol NWS C3 Spjluzl. Av jvumpyt fvb hjjlwa aolzl alytz, wslhzl zla aol <i>spjluzl</> rlf pu <p>pmyhtl-ylzpgly</> vwapvuz av <i>NWSc3</>.<iy><iy>Mvy tvyl pumvythapvu wslhzl zll: <b>oaawz://pmyhtl-ylzpgly.jvt/nws</>","<i>NWSc3 Spjluzl Clyzpvu</><iy><iy>Aopz clyzpvu vm <p>pmyhtl-ylzpgly</> pz ilpun bzlk bukly aol alytz vm aol <i>NWS C3</> spjluzl. Aopz spjluzl hssvdz fvb av bzl <p>pmyhtl-ylzpgly</> pu Vwlu Zvbyjl wyvqljaz, iba pa ylxbpylz fvby wyvqlja av il wbispj, wyvcpkl haaypibapvu huk il spjluzlk bukly clyzpvu 3 vy shaly vm aol NUB Nlulyhs Wbispj Spjluzl.<iy><iy>Pm fvb hyl bzpun aopz spiyhyf pu h uvu-vwlu zvbyjl wyvqlja vy dlizpal, fvb dpss ullk av wbyjohzl h svd jvza vul aptl jvttlyjphs spjluzl.<iy><iy>Mvy tvyl pumvythapvu cpzpa <b>oaawz://pmyhtl-ylzpgly.jvt/wypjpun</>."];Object.fromEntries(["2cgs7fdf4xb","1c9ctcccr4z","1q2pc4eebgb","ueokt0969w","w2zxchhgqz","1umuxblj2e5"].map(((e,t)=>[e,Math.max(0,t-1)])));const d=e=>(e=>e.replaceAll(/[A-Za-z]/g,(e=>String.fromCodePoint((e<="Z"?90:122)>=(e=e.codePointAt(0)+19)?e:e-26))))(c[e]),u=e=>e,m=1e5,f=e=>Math.round(e*m)/m;let p="",h=!1;const y=e=>{p=e.id,h=e.logging},g=e=>""!=`${e}`&&void 0!==e;const b=(...e)=>[`[iframe-resizer][${p||"child"}]`,...e].join(" "),v=(...e)=>h&&console?.info(`[iframe-resizer][${p}]`,...e),w=(...e)=>console?.warn(b(...e)),z=(...e)=>console?.warn((e=>t=>window.chrome?e(t.replaceAll("<br>","\n").replaceAll("<rb>","[31;1m").replaceAll("</>","[m").replaceAll("<b>","[1m").replaceAll("<i>","[3m").replaceAll("<u>","[4m")):e(t.replaceAll("<br>","\n").replaceAll(/<[/a-z]+>/gi,"")))(b)(...e)),S=e=>z(e);let E=[];const j=e=>{const t=e.side||i,n=e.onChange||u,r={root:document.documentElement,rootMargin:"0px",threshold:1},a=new WeakSet;function l(){E=document.querySelectorAll(`[${o}]`),n()}const s=new IntersectionObserver((function(e){e.forEach((e=>{e.target.toggleAttribute(o,(e=>0===e.boundingClientRect[t]||e.boundingClientRect[t]>e.rootBounds[t])(e))})),requestAnimationFrame(l)}),r);return function(e){for(const t of e)t.nodeType!==Node.ELEMENT_NODE||a.has(t)||(s.observe(t),a.add(t))}},C=()=>E.length>0,$="--ifr-start",T="--ifr-end",M="--ifr-measure",P=[],A=new WeakSet,O=e=>"object"==typeof e&&A.add(e);let N=null,I=null,k={};const R=setInterval((()=>{if(P.length<10)return;if(k.hasTags&&k.len<25)return;P.sort();const e=Math.min(P.reduce(((e,t)=>e+t),0)/P.length,P[Math.floor(P.length/2)]);e<=4||(clearInterval(R),z(`<rb>Performance Warning</>\n\nCalculating the page size is taking an excessive amount of time (${f(e)}ms).\n\nTo improve performance add the <b>data-iframe-size</> attribute to the ${k.Side.toLowerCase()} most element on the page. For more details see: <u>https://iframe-resizer.com/perf</>.`))}),5e3);function x(e){e.getEntries().forEach((e=>{if(e.name===T){const{duration:t}=performance.measure(M,$,T);!function(e,t){const{Side:n,len:o,hasTags:i,logging:r}=e;k=e,A.has(I)||N===I||i&&o<=1||(r||O(I),N=I,v(`\n${n} position calculated from:`,I,`\nParsed ${o} ${i?"tagged":"potentially overflowing"} elements in ${f(t)}ms`))}(e.detail,t),P.push(t),P.length>100&&P.shift()}}))}function q(){new PerformanceObserver(x).observe({entryTypes:["mark"]}),O(document.documentElement),O(document.body)}"undefined"!=typeof document&&"undefined"!=typeof PerformanceObserver&&("loading"===document.readyState?document.addEventListener("DOMContentLoaded",q):q()),"undefined"!=typeof window&&function(){const o={contentVisibilityAuto:!0,opacityProperty:!0,visibilityProperty:!0},c={height:()=>(w("Custom height calculation function not defined"),qe.auto()),width:()=>(w("Custom width calculation function not defined"),Le.auto())},m={bodyOffset:1,bodyScroll:1,offset:1,documentElementOffset:1,documentElementScroll:1,boundingClientRect:1,max:1,min:1,grow:1,lowestElement:1},p=128,h={},b="checkVisibility"in window,v="auto",M="[iFrameSizer]",P=M.length,A={max:1,min:1,bodyScroll:1,documentElementScroll:1},O="scroll";let N,k,R,x,q=!0,L="",B=0,D="",W="",U=!0,F=!1,V=!0,J=!1,H=1,Z=v,_=!0,Q="",X={},Y=!1,G=0,K=!1,ee="",te=u,ne="child",oe=null,ie=!1,re="",ae=[],le=window.parent,se="*",ce=0,de=!1,ue="",me=1,fe=O,pe=window,he=()=>{w("onMessage function not defined")},ye=()=>{},ge=null,be=null;function ve(){var o,s,u;!function(){const e=e=>"true"===e,t=Q.slice(P).split(":");ee=t[0],B=void 0===t[1]?B:Number(t[1]),F=void 0===t[2]?F:e(t[2]),Y=void 0===t[3]?Y:e(t[3]),q=void 0===t[6]?q:e(t[6]),D=t[7],Z=void 0===t[8]?Z:t[8],L=t[9],W=t[10],ce=void 0===t[11]?ce:Number(t[11]),X.enable=void 0!==t[12]&&e(t[12]),ne=void 0===t[13]?ne:t[13],fe=void 0===t[14]?fe:t[14],K=void 0===t[15]?K:e(t[15]),N=void 0===t[16]?N:Number(t[16]),k=void 0===t[17]?k:Number(t[17]),U=void 0===t[18]?U:e(t[18]),t[19],ue=t[20]||ue,G=void 0===t[21]?G:Number(t[21])}(),y({id:ee,logging:Y}),function(){function e(e){he=e?.onMessage||he,ye=e?.onReady||ye,"number"==typeof e?.offset&&(z("<rb>Deprecated option</>\n\n The <b>offset</> option has been renamed to <b>offsetSize</>. Use of the old name will be removed in a future version of <i>iframe-resizer</>."),U&&(N=e?.offset),F&&(k=e?.offset)),"number"==typeof e?.offsetSize&&(U&&(N=e?.offsetSize),F&&(k=e?.offsetSize)),Object.prototype.hasOwnProperty.call(e,"sizeSelector")&&(re=e.sizeSelector),se=e?.targetOrigin||se,Z=e?.heightCalculationMethod||Z,fe=e?.widthCalculationMethod||fe}function t(e,t){return"function"==typeof e&&(c[t]=e,e="custom"),e}if(1===G)return;const n=window.iframeResizer||window.iFrameResizer;"object"==typeof n&&(e(n),Z=t(Z,"height"),fe=t(fe,"width"))}(),function(){try{ie="iframeParentListener"in window.parent}catch(e){}}(),G<0?S(`${d(G+2)}${d(2)}`):ue.codePointAt(0)>4||G<2&&S(d(3)),ue&&""!==ue&&"false"!==ue?ue!==e&&z(`<b>Version mismatch</>\n\nThe parent and child pages are running different versions of <i>iframe resizer</>.\n\nParent page: ${ue} - Child page: ${e}.\n`):z("<rb>Legacy version detected on parent page</>\n\nDetected legacy version of parent page script. It is recommended to update the parent page to use <b>@iframe-resizer/parent</>.\n\nSee <u>https://iframe-resizer.com/setup/</> for more details.\n"),$e(),Te(),function(){let e=!1;const t=t=>document.querySelectorAll(`[${t}]`).forEach((o=>{e=!0,o.removeAttribute(t),o.toggleAttribute(n,!0)}));t("data-iframe-height"),t("data-iframe-width"),e&&z("<rb>Deprecated Attributes</>\n          \nThe <b>data-iframe-height</> and <b>data-iframe-width</> attributes have been deprecated and replaced with the single <b>data-iframe-size</> attribute. Use of the old attributes will be removed in a future version of <i>iframe-resizer</>.")}(),we(),U!==F&&(te=j({onChange:()=>We("overflowChanged","Overflow updated"),side:U?i:r})),1!==G&&(pe.parentIframe=Object.freeze({autoResize:e=>(!0===e&&!1===q?(q=!0,We("autoResizeEnabled","Auto Resize enabled")):!1===e&&!0===q&&(q=!1),Je(0,0,"autoResize",JSON.stringify(q)),q),close(){Je(0,0,"close")},getId:()=>ee,getPageInfo(e){if("function"==typeof e)return ge=e,Je(0,0,"pageInfo"),void z("<rb>Deprecated Method</>\n          \nThe <b>getPageInfo()</> method has been deprecated and replaced with  <b>getParentProps()</>. Use of this method will be removed in a future version of <i>iframe-resizer</>.\n");ge=null,Je(0,0,"pageInfoStop")},getParentProps(e){if("function"!=typeof e)throw new TypeError("parentIframe.getParentProps(callback) callback not a function");return be=e,Je(0,0,"parentInfo"),()=>{be=null,Je(0,0,"parentInfoStop")}},getParentProperties(e){z("<rb>Renamed Method</>\n          \nThe <b>getParentProperties()</> method has been renamed <b>getParentProps()</>. Use of the old name will be removed in a future version of <i>iframe-resizer</>.\n"),this.getParentProps(e)},moveToAnchor(e){X.findTarget(e)},reset(){Ve()},scrollBy(e,t){Je(t,e,"scrollBy")},scrollTo(e,t){Je(t,e,"scrollTo")},scrollToOffset(e,t){Je(t,e,"scrollToOffset")},sendMessage(e,t){Je(0,0,"message",JSON.stringify(e),t)},setHeightCalculationMethod(e){Z=e,$e()},setWidthCalculationMethod(e){fe=e,Te()},setTargetOrigin(e){se=e},resize(e,t){We(a,`parentIframe.resize(${e||""}${t?`,${t}`:""})`,e,t)},size(e,t){z("<rb>Deprecated Method</>\n          \nThe <b>size()</> method has been deprecated and replaced with  <b>resize()</>. Use of this method will be removed in a future version of <i>iframe-resizer</>.\n"),this.resize(e,t)}}),pe.parentIFrame=pe.parentIframe),function(){function e(e){Je(0,0,e.type,`${e.screenY}:${e.screenX}`)}function t(t,n){l(window.document,t,e)}!0===K&&(t("mouseenter"),t("mouseleave"))}(),X=function(){const e=()=>({x:document.documentElement.scrollLeft,y:document.documentElement.scrollTop});function n(n){const o=n.getBoundingClientRect(),i=e();return{x:parseInt(o.left,t)+parseInt(i.x,t),y:parseInt(o.top,t)+parseInt(i.y,t)}}function o(e){function t(e){const t=n(e);Je(t.y,t.x,"scrollToOffset")}const o=e.split("#")[1]||e,i=decodeURIComponent(o),r=document.getElementById(i)||document.getElementsByName(i)[0];void 0===r?Je(0,0,"inPageLink",`#${o}`):t(r)}function i(){const{hash:e,href:t}=window.location;""!==e&&"#"!==e&&o(t)}function r(){for(const e of document.querySelectorAll('a[href^="#"]'))"#"!==e.getAttribute("href")&&l(e,"click",(t=>{t.preventDefault(),o(e.getAttribute("href"))}))}function a(){l(window,"hashchange",i)}function s(){setTimeout(i,p)}function c(){r(),a(),s()}return X.enable&&(1===G?z("In page linking requires a Professional or Business license. Please see https://iframe-resizer.com/pricing for more details."):c()),{findTarget:o}}(),ze(Ie(document)()),void 0===D&&(D=`${B}px`),Se("margin",(s="margin",(u=D).includes("-")&&(w(`Negative CSS value ignored for ${s}`),u=""),u)),Se("background",L),Se("padding",W),function(){const e=document.createElement("div");e.style.clear="both",e.style.display="block",e.style.height="0",document.body.append(e)}(),function(){const e=e=>e.style.setProperty("height","auto","important");e(document.documentElement),e(document.body)}(),Ee(),We("init","Init message from host page",void 0,void 0,e),document.title&&""!==document.title&&Je(0,0,"title",document.title),je({method:o="add",eventType:"After Print",eventName:"afterprint"}),je({method:o,eventType:"Before Print",eventName:"beforeprint"}),je({method:o,eventType:"Ready State Change",eventName:"readystatechange"}),function(){const e=new Set;let t=!1,n=0,o=[];const i=t=>{for(const n of t){const{addedNodes:t,removedNodes:o}=n;for(const n of t)e.add(n);for(const t of o)e.delete(t)}},r=16,a=2,l=200;let s=1;function c(){const d=performance.now(),u=d-n;if(u>r*s+++a&&u<l)return setTimeout(c,r*s),void(n=d);s=1,o.forEach(i),o=[],0!==e.size?(Ee(),we(),ze(e),e.forEach(Ae),e.clear(),t=!1):t=!1}function d(e){o.push(e),t||(n=performance.now(),t=!0,requestAnimationFrame(c))}function u(){const e=new window.MutationObserver(d),t=document.querySelector("body"),n={attributes:!1,attributeOldValue:!1,characterData:!1,characterDataOldValue:!1,childList:!0,subtree:!0};return e.observe(t,n),e}u()}(),oe=new ResizeObserver(Me),oe.observe(document.body),Pe.add(document.body),Ae(document.body),setTimeout(ye)}function we(){ae=document.querySelectorAll(`[${n}]`),J=ae.length>0}function ze(e){J||te(e)}function Se(e,t){void 0!==t&&""!==t&&"null"!==t&&document.body.style.setProperty(e,t)}function Ee(){if(""!==re)for(const e of document.querySelectorAll(re))e.dataset.iframeSize=!0}function je(e){({add(t){function n(){We(e.eventName,e.eventType)}h[t]=n,l(window,t,n,{passive:!0})},remove(e){const t=h[e];delete h[e],s(window,e,t)}})[e.method](e.eventName)}function Ce(e,t,n,o){return t!==e&&(e in n||(w(`${e} is not a valid option for ${o}CalculationMethod.`),e=t),e in m&&z(`<rb>Deprecated ${o}CalculationMethod (${e})</>\n\nThis version of <i>iframe-resizer</> can auto detect the most suitable ${o} calculation method. It is recommended that you remove this option.`)),e}function $e(){Z=Ce(Z,v,qe,"height")}function Te(){fe=Ce(fe,O,Le,"width")}function Me(e){Array.isArray(e)&&0!==e.length&&We("resizeObserver",`Resize Observed: ${function(e){switch(!0){case!g(e):return"";case g(e.id):return`${e.nodeName.toUpperCase()}#${e.id}`;case g(e.name):return`${e.nodeName.toUpperCase()} (${e.name})`;default:return e.nodeName.toUpperCase()+(g(e.className)?`.${e.className}`:"")}}(e[0].target)}`)}const Pe=new WeakSet;function Ae(e){if(e.nodeType!==Node.ELEMENT_NODE)return;if(!Pe.has(e)){const t=getComputedStyle(e)?.position;""!==t&&"static"!==t&&(oe.observe(e),Pe.add(e))}const t=Ie(e)();for(const e of t){if(Pe.has(e)||e?.nodeType!==Node.ELEMENT_NODE)continue;const t=getComputedStyle(e)?.position;""!==t&&"static"!==t&&(oe.observe(e),Pe.add(e))}}function Oe(e){performance.mark($);const t=(n=e).charAt(0).toUpperCase()+n.slice(1);var n;let i=0,r=document.documentElement,a=J?0:document.documentElement.getBoundingClientRect().bottom;performance.mark($);const l=J?ae:C()?E:Ie(document)();let s=l.length;for(const t of l)J||!b||t.checkVisibility(o)?(i=t.getBoundingClientRect()[e]+parseFloat(getComputedStyle(t).getPropertyValue(`margin-${e}`)),i>a&&(a=i,r=t)):s-=1;return I=r,performance.mark(T,{detail:{Side:t,len:s,hasTags:J,logging:Y}}),a}const Ne=e=>[e.bodyOffset(),e.bodyScroll(),e.documentElementOffset(),e.documentElementScroll(),e.boundingClientRect()],Ie=e=>()=>e.querySelectorAll("* :not(head):not(meta):not(base):not(title):not(script):not(link):not(style):not(map):not(area):not(option):not(optgroup):not(template):not(track):not(wbr):not(nobr)"),ke={height:0,width:0},Re={height:0,width:0};function xe(e){function t(){return Re[i]=r,ke[i]=s,r}const n=C(),o=e===qe,i=o?"height":"width",r=e.boundingClientRect(),a=Math.ceil(r),l=Math.floor(r),s=(e=>e.documentElementScroll()+Math.max(0,e.getOffset()))(e);switch(!0){case!e.enabled():return s;case J:return e.taggedElement();case!n&&0===Re[i]&&0===ke[i]:return t();case de&&r===Re[i]&&s===ke[i]:return Math.max(r,s);case 0===r:return s;case!n&&r!==Re[i]&&s<=ke[i]:return t();case!o:return e.taggedElement();case!n&&r<Re[i]:case s===l||s===a:case r>s:return t()}return Math.max(e.taggedElement(),t())}const qe={enabled:()=>U,getOffset:()=>N,auto:()=>xe(qe),bodyOffset:()=>{const{body:e}=document,n=getComputedStyle(e);return e.offsetHeight+parseInt(n.marginTop,t)+parseInt(n.marginBottom,t)},bodyScroll:()=>document.body.scrollHeight,offset:()=>qe.bodyOffset(),custom:()=>c.height(),documentElementOffset:()=>document.documentElement.offsetHeight,documentElementScroll:()=>document.documentElement.scrollHeight,boundingClientRect:()=>Math.max(document.documentElement.getBoundingClientRect().bottom,document.body.getBoundingClientRect().bottom),max:()=>Math.max(...Ne(qe)),min:()=>Math.min(...Ne(qe)),grow:()=>qe.max(),lowestElement:()=>Oe(i),taggedElement:()=>Oe(i)},Le={enabled:()=>F,getOffset:()=>k,auto:()=>xe(Le),bodyScroll:()=>document.body.scrollWidth,bodyOffset:()=>document.body.offsetWidth,custom:()=>c.width(),documentElementScroll:()=>document.documentElement.scrollWidth,documentElementOffset:()=>document.documentElement.offsetWidth,boundingClientRect:()=>Math.max(document.documentElement.getBoundingClientRect().right,document.body.getBoundingClientRect().right),max:()=>Math.max(...Ne(Le)),min:()=>Math.min(...Ne(Le)),rightMostElement:()=>Oe(r),scroll:()=>Math.max(Le.bodyScroll(),Le.documentElementScroll()),taggedElement:()=>Oe(r)},Be=(e,t)=>!(Math.abs(e-t)<=ce);let De=!1;function We(e,t,n,o,i){x=performance.now(),(q||e===a)&&(document.hidden||(De||(R=!0,function(e,t,n,o,i){const r=void 0===n?qe[Z]():n,l=void 0===o?Le[fe]():o;U&&Be(H,r)||F&&Be(me,l)||"init"===e?(Ue(),H=r,me=l,Je(H,me,e,i)):!e!==a&&(U&&Z in A||F&&fe in A)?Ve():R=!1}(e,0,n,o,i),requestAnimationFrame((()=>{De=!1}))),De=!0))}function Ue(){de||(de=!0,requestAnimationFrame((()=>{de=!1})))}function Fe(e){H=qe[Z](),me=Le[fe](),Je(H,me,e)}function Ve(e){const t=Z;Z=v,Ue(),Fe("reset"),Z=t}function Je(e,t,n,o,i){G<-1||(void 0!==i||(i=se),function(){const r=`${ee}:${e+(N||0)}:${t+(k||0)}:${n}${void 0===o?"":`:${o}`}`;Y&&(console.group(`[iframe-resizer][${ee}]`),console.info("Sending message to host page via "+(ie?"sameDomain":"postMessage")),console.info(`%c${r}`,"font-style: italic"),R&&console.info(function(){const e=f(performance.now()-x);return"init"===n?`Initialised iFrame in %c${e}ms`:`Content size recalculated in %c${e}ms`}(),"font-weight:bold;color:#777"),console.groupEnd()),R=!1,ie?window.parent.iframeParentListener(M+r):le.postMessage(M+r,i)}())}function He(e){const t={init:function(){Q=e.data,le=e.source,ve(),V=!1,setTimeout((()=>{_=!1}),p)},reset(){_||Fe("resetPage")},resize(){We(a)},moveToAnchor(){X.findTarget(o())},inPageLink(){this.moveToAnchor()},pageInfo(){const e=o();ge?setTimeout((()=>ge(JSON.parse(e)))):Je(0,0,"pageInfoStop")},parentInfo(){const e=o();be?setTimeout(be(Object.freeze(JSON.parse(e)))):Je(0,0,"parentInfoStop")},message(){const e=o();he(JSON.parse(e))}},n=()=>e.data.split("]")[1].split(":")[0],o=()=>e.data.slice(e.data.indexOf(":")+1),i=()=>"iframeResize"in window||void 0!==window.jQuery&&""in window.jQuery.prototype,r=()=>e.data.split(":")[2]in{true:1,false:1};M===`${e.data}`.slice(0,P)&&(!1!==V?r()&&t.init():function(){const o=n();o in t?t[o]():i()||r()||w(`Unexpected message (${e.data})`)}())}function Ze(){"loading"!==document.readyState&&window.parent.postMessage("[iFrameResizerChild]Ready","*")}function _e(e){return He(e),pe}"iframeChildListener"in window?w("Already setup"):(window.iframeChildListener=e=>setTimeout((()=>He({data:e,sameDomain:!0}))),l(window,"message",He),l(window,"readystatechange",Ze),Ze());try{top?.document?.getElementById("banner")&&(pe={},window.mockMsgListener=_e,s(window,"message",He),define([],(()=>_e)))}catch(e){}}()}();