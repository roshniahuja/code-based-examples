"use strict";(self.webpackChunkwrt_theme=self.webpackChunkwrt_theme||[]).push([[532],{4532:function(e,t,a){a.r(t),a.d(t,{default:function(){return l}});var r=a(6989),n=a.n(r),d=a(1975),i=a(5368),s=a.n(i);const c=(e,t)=>{const a=e.find((e=>e.find((e=>e.taxonomy===t))));if(!a)return null;const r=a?a.find((e=>e.taxonomy===t)):void 0;if(!r)return null;const n=document.createElement("a");return n.href=r.link,n.textContent=r.name,"wat-grade"===t&&(n.rel="tag"),"category"===t&&n.classList.add("cat-item__link"),n};var o=(e,t)=>{const a=e.content.cloneNode(!0);if(a.querySelector("[data-image]")&&t.featured_media&&t._embedded["wp:featuredmedia"]&&t._embedded["wp:featuredmedia"][0]){const e=a.querySelector("[data-image-link]");e.href=(0,d.escapeAttribute)(t.link);const r=(e=>{const t=e.source_url,a=e.alt_text||"",{height:r,width:n}=e.media_details;let d="";Object.keys(e.media_details.sizes).forEach((t=>{d+=`${e.media_details.sizes[t].source_url} ${e.media_details.sizes[t].width}w, `})),d=d.slice(0,-2);const i=document.createElement("img");return i.src=t,i.alt=a,i.width=n,i.height=r,i.srcset=d,i})(t._embedded["wp:featuredmedia"][0]);e.innerHTML=s().sanitize(r)}const r=a.querySelector("[data-grades]");if(r&&t._embedded["wp:term"]){const e=c(t._embedded["wp:term"],"wat-grade"),n=a.querySelector("[data-grades-link]");e&&n?r.replaceChild(e,n):r.remove()}const n=a.querySelector("[data-title-link]");n.href=(0,d.escapeAttribute)(t.link),n.ariaLabel=(0,d.escapeAttribute)(t.title.rendered),n.innerText=(0,d.escapeHTML)(t.title.rendered);const i=a.querySelector("[data-excerpt]");i&&t.excerpt.rendered?i.innerHTML=s().sanitize(t.excerpt.rendered):i.remove();const o=a.querySelector("[data-category]");if(o&&t._embedded["wp:term"]){const e=c(t._embedded["wp:term"],"category");o?o.innerHTML=s().sanitize(e):o.remove()}const l=a.querySelector("[data-author]");return l&&t._embedded.author&&(l.href=t._embedded.author[0].link,l.innerText=s().sanitize(t._embedded.author[0].name)),a};var l=async()=>{const e=document.getElementById("js-load-more");if(!("content"in document.createElement("template"))||!e)return;const t=document.querySelector(".filterable-post-card-contents-items"),a=document.getElementById("post-card-template"),r=async()=>{const{clear:r,page:d,query:i}=e.dataset;if(!i)return;let s=i;s+="&per_page=10",s+=`&page=${d||2}`;let c=[];try{e.classList.add("button-loading"),t.classList.add("loading"),c=await n()({path:`/wp/v2/posts?_embed&${s}`})}catch(e){console.warn("Load more error",e)}finally{e.classList.remove("button-loading"),t.classList.remove("loading"),((t,a)=>{if(10===a.length){e.dataset.clear="";const a=t||2;e.dataset.page=parseInt(a,10)+1}else e.style.display="none"})(d,c),c.length>0?(r&&(t.innerHTML=""),(e=>{e.forEach((e=>{const r=o(a,e);t.appendChild(r)}))})(c)):t.innerHTML="No posts found."}};e.addEventListener("click",(async()=>{await r()}))}}}]);