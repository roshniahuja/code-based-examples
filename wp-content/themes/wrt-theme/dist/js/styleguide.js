!function(){"use strict";new class{constructor(t){this.sectionHeadings=t}init(){this.sectionHeadings?this.sectionHeadings.forEach((t=>{this.setupCollapsible(t)})):console.error("Styleguide: No sections detected.")}setupCollapsible(t){const e=t.parentNode.id;let o=!0;localStorage&&(o="true"!==localStorage.getItem(`section-${e}`)||!1),t.innerHTML=`\n\t\t\t<button class="toggle" aria-expanded="${!o}" id="toggle-${e}">\n\t\t\t\t<span>${t.textContent}</span>\n\t\t\t\t<svg aria-hidden="true" focusable="false" class="uikit__chevron-up" width="12" height="7" xmlns="http://www.w3.org/2000/svg" viewBox="3.3 4.5 11.4 7" role="img"><polygon points="9,4.5 3.3,10.1 4.8,11.5 9,7.3 13.2,11.5 14.7,10.1 "></polygon></svg>\n\t\t\t\t<svg aria-hidden="true" focusable="false" class="uikit__chevron-down" width="12" height="7" xmlns="http://www.w3.org/2000/svg" viewBox="3.3 6.5 11.4 7" role="img"><polygon points="9,13.5 14.7,7.9 13.2,6.5 9,10.7 4.8,6.5 3.3,7.9 "></polygon></svg>\n\t\t\t</button>\n\t\t`;const i=t.parentNode.querySelector(".content");i.hidden=o,i.setAttribute("aria-hidden",o),i.setAttribute("aria-labelledby",`toggle-${e}`);const n=t.querySelector("button");n.onclick=t=>this.toggleCollapsible(t,i,n)}toggleCollapsible(t,e,o){const i="true"===o.getAttribute("aria-expanded")||!1;o.setAttribute("aria-expanded",!i),e.hidden=i,e.setAttribute("aria-hidden",i);const n=e.parentNode.id;localStorage&&localStorage.setItem(`section-${n}`,!i)}}(document.querySelectorAll(".uikit__section h2.heading")).init()}();