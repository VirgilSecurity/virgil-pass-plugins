"use strict";!function(e){var i=e.VirgilSDK=e.VirgilSDK||{};i.config={SERVICE_URL:"https://auth.virgilsecurity.com",APP_ID:"chrome",REFERENCE_DATA_OPTION:"virgil-reference",AUTH_BTN_SELECTOR:'[data-virgil-ui="auth-btn"], [href^="virgilsecurity:"]',AUTH_BTN_TXT:"Virgil Auth",AUTH_BTN_TXT_LOADING:"Virgil Auth...",EXTENSION_STORE_URL:"https://auth-demo.virgilsecurity.com/uploads/virgil-chrome.zip",AUTH_BTN_MOBILE_PREFIX:"virgilsecurity://"}}(window),function(e){var i=e.VirgilSDK=e.VirgilSDK||{};i.utils={isFunction:function(e){return"function"!=typeof/./?"function"==typeof e||!1:"[object Function]"===Object.prototype.toString.call(e)},isString:function(e){return"[object String]"===Object.prototype.toString.call(e)},isObject:function(e){var i=typeof e;return"function"===i||"object"===i&&!!e},getBrowserInfo:function(){var i,t,n,r,o=e.navigator,s=o.userAgent,a=o.appName,u=""+parseFloat(o.appVersion),f={chrome:"Chrome",firefox:"Firefox",safari:"Safari",opera:"Opera",ie:"Microsoft Internet Explorer"};return-1!=(n=s.indexOf("OPR/"))?(a=f.opera,u=s.substring(n+4)):-1!=(n=s.indexOf(f.opera))?(a=f.opera,u=s.substring(n+6),-1!=(n=s.indexOf("Version"))&&(u=s.substring(n+8))):-1!=(n=s.indexOf("MSIE"))?(a=f.ie,u=s.substring(n+5)):-1!=(n=s.indexOf(f.chrome))?(a=f.chrome,u=s.substring(n+7)):-1!=(n=s.indexOf(f.safari))?(a=f.safari,u=s.substring(n+7),-1!=(n=s.indexOf("Version"))&&(u=s.substring(n+8))):-1!=(n=s.indexOf(f.firefox))?(a=f.firefox,u=s.substring(n+8)):(t=s.lastIndexOf(" ")+1)<(n=s.lastIndexOf("/"))&&(a=s.substring(t,n),u=s.substring(n+1),a.toLowerCase()==a.toUpperCase()&&(a=o.appName)),-1!=(r=u.indexOf(";"))&&(u=u.substring(0,r)),-1!=(r=u.indexOf(" "))&&(u=u.substring(0,r)),i=parseInt(""+u,10),isNaN(i)&&(u=""+parseFloat(o.appVersion),i=parseInt(o.appVersion,10)),{name:a,agent:s,versions:{major:i,full:u},isMobile:function(){return o&&!!(o.userAgent.match(/Android/i)||o.userAgent.match(/webOS/i)||o.userAgent.match(/iPhone/i)||o.userAgent.match(/iPad/i)||o.userAgent.match(/iPod/i)||o.userAgent.match(/BlackBerry/i)||o.userAgent.match(/Windows Phone/i))},isChrome:function(){return a===f.chrome},isFirefox:function(){return a===f.firefox},isSafari:function(){return a===f.safari},isOpera:function(){return a===f.opera},isIe:function(){return a===f.ie}}}}}(window),function(e){var i=e.VirgilSDK=e.VirgilSDK||{},t=i.utils,n=i.config,r=t.getBrowserInfo(),o={notSupportedBrowser:"Sorry, the browser {{browser}} not supported yet.\nWe are working hard for support {{browser}} and it will be done very soon.",pleaseInstallExtension:"The Virgil authorization extension is not installed. Click OK to download extension,  unpack it and install. Do not forget refresh the page after installation."};i.isVirgilExtensionInstalled=!1,i.init=function(){this.initAuthBtn()},i.getProp=function(e){var t=i[e];return void 0===typeof t?null:t},i.initAuthBtn=function(){var i=this,s=e.document.querySelector(n.AUTH_BTN_SELECTOR);s&&(r.isMobile()?s.addEventListener("click",function(i){i.preventDefault(),e.open(n.AUTH_BTN_MOBILE_PREFIX+s.getAttribute("data-"+n.REFERENCE_DATA_OPTION),"_parent")}):s.addEventListener("click",function(){if(r.isChrome()){if(!i.getProp("isVirgilExtensionInstalled")){var s=confirm(o.pleaseInstallExtension);s&&setTimeout(function(){e.open(n.EXTENSION_STORE_URL,"_parent")},0)}}else alert(t.parseTemplate(o.notSupportedBrowser,{browser:""+r.name+" - "+r.versions.full}))}))},r.isMobile()||r.isChrome()&&e.document.addEventListener("virgil:extension:attach",function(){i.isVirgilExtensionInstalled=!0}),e.document.addEventListener("DOMContentLoaded",function(){i.init()})}(window);