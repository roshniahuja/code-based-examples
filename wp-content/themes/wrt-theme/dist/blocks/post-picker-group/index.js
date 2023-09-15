!function(){"use strict";var t={d:function(e,o){for(var r in o)t.o(o,r)&&!t.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:o[r]})},o:function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r:function(t){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})}},e={};t.r(e),t.d(e,{hydrateData:function(){return w},releasePost:function(){return f},reservePost:function(){return P}});var o={};t.r(o),t.d(o,{getAvailablePosts:function(){return b},getClientPost:function(){return y},getReservedPosts:function(){return g},getState:function(){return m}});var r={};t.r(r),t.d(r,{getAvailablePosts:function(){return h}});var s=window.wp.data,a=window.wp.blocks,n=window.wp.element,i=window.wp.primitives,l=(0,n.createElement)(i.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,n.createElement)(i.Path,{d:"M18 5.5H6a.5.5 0 00-.5.5v3h13V6a.5.5 0 00-.5-.5zm.5 5H10v8h8a.5.5 0 00.5-.5v-7.5zm-10 0h-3V18a.5.5 0 00.5.5h2.5v-8zM6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"})),c=window.wp.blockEditor,p=window.wp.components;var d=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"wrt/post-picker-group","title":"Post Picker Group","category":"text","description":"Post picker group block","attributes":{"mode":{"type":"string","default":"post"},"contentTypes":{"type":"array","items":{"type":"string"},"default":["post"]},"partial":{"type":"string","default":"simple-card"},"size":{"type":"number","default":1},"containerClass":{"type":"string","default":""},"childrenClass":{"type":"string","default":""},"showExcerpt":{"type":"boolean","default":false},"location":{"type":"string","default":""}},"supports":{"reusable":false,"html":false,"anchor":true,"color":{"background":true,"text":true},"align":["wide","full"],"spacing":{"margin":["top","bottom"],"padding":["top","bottom"]}},"providesContext":{"wrt/postPickerContentTypes":"contentTypes","wrt/postPickerMode":"mode","wrt/postPickerPartial":"partial","wrt/postPickerClassName":"childrenClass","wrt/postPickerShowExcerpt":"showExcerpt","wrt/postPickerLocation":"location"},"textdomain":"tenup","editorScript":"file:./index.js"}'),u=window.wp.dataControls;const v={default:{availablePosts:{},reservedPosts:{}}};const w=(t,e)=>({type:"HYDRATE",data:{location:t,posts:e}}),P=(t,e)=>({type:"RESERVE_NEXT_POST",data:{clientId:t,location:e}}),f=(t,e)=>({type:"RELEASE_POST",data:{clientId:t,location:e}}),m=t=>t,b=(t,e)=>t?.[e]?.availablePosts||[],g=(t,e)=>t?.[e]?.reservedPosts||[],y=(t,e,o)=>t?.[o]?.reservedPosts?.[e]||void 0;function*h(t){const e=`/wrt/v1/curation-locations/hydrators/${t}`,o=yield(0,u.apiFetch)({path:e});return w(t,o||[])}const k={selectors:o,actions:e,resolvers:r,reducer:(t=v,{type:e,data:o})=>{switch(console.log(`Reducer: ${e}`,o),e){case"HYDRATE":return{...t,[o.location]:{availablePosts:o.posts,reservedPosts:{}}};case"RESERVE_NEXT_POST":return t?.[o.location]?.availablePosts?((t,e)=>{const{location:o,clientId:r}=e,s=t[o].availablePosts.shift();return{...t,[o]:{availablePosts:[...t[o].availablePosts],reservedPosts:{...t[o].reservedPosts,[r]:s}}}})(t,o):{...t};default:return{...t}}},controls:u.controls},E=(0,s.createReduxStore)("wrt/post-picker-data",k);(0,s.register)(E),(0,a.registerBlockType)(d,{icon:l,edit:t=>{const{attributes:{size:e=1,containerClass:o="",location:r}}=t,a=(0,c.useBlockProps)({className:o}),{hasResolved:i}=(0,s.useSelect)((t=>{const e=[r];return{posts:t("wrt/post-picker-data").getAvailablePosts(...e),hasResolved:t("wrt/post-picker-data").hasFinishedResolution("getAvailablePosts",e)}})),l=(0,c.useInnerBlocksProps)(a,{allowedBlocks:["wrt/post-picker"],template:Array(e).fill(["wrt/post-picker",{isCurated:!1}])});return i?(0,n.createElement)(c.BlockContextProvider,{value:{"wrt/postPickerGroup":{hasResolved:i,location:r}}},(0,n.createElement)("div",l)):(0,n.createElement)("p",a,(0,n.createElement)(p.Spinner,null))},save:t=>{const{attributes:e}=t,{containerClass:o}=e,r=c.useBlockProps.save({className:o});return(0,n.createElement)("div",r,(0,n.createElement)(c.InnerBlocks.Content,null))}})}();