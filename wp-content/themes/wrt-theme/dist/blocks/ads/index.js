!function(){"use strict";var e=window.wp.blocks,t=window.wp.element,a=window.wp.i18n,r=window.wp.blockEditor,l=window.wp.components;var n=e=>{const{attributes:n,setAttributes:o}=e,{ad:s}=n,i=(0,r.useBlockProps)(),c=[{label:(0,a.__)("Header","wrt-theme"),value:"weareteachers_leaderboard_atf",size:"320x100"},{label:(0,a.__)("Sidebar (big)","wrt-theme"),value:"weareteachers_med_rect_atf",size:"300x600"},{label:(0,a.__)("Sidebar (small)","wrt-theme"),value:"weareteachers_med_rect_btf",size:"300x250"},{label:(0,a.__)("Footer (sticky)","wrt-theme"),value:"weareteachers_adhesion",size:"728x90"},{label:(0,a.__)("Video","wrt-theme"),value:"weareteachers_dynamic_incontent",size:"468x60"}];return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(r.InspectorControls,null,(0,t.createElement)(l.PanelBody,{title:(0,a.__)("Link to topic","wrt-theme")},(0,t.createElement)(l.SelectControl,{label:(0,a.__)("Ad","wrt-theme"),value:s,options:c.map((({size:e,...t})=>t)),onChange:e=>o({ad:e}),__nextHasNoMarginBottom:!0}))),(0,t.createElement)("div",i,s&&(0,t.createElement)("img",{src:`https://placehold.co/${(e=>{const t=c.find((t=>t.value===e));return t?t.size:null})(s)}`,alt:"Ad placeholder"})))};var o=()=>null,s=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"title":"Ads","description":"Ads placement block","textdomain":"wrt-theme","name":"wrt/ads","icon":"html","category":"formatting","attributes":{"ad":{"type":"string","default":""}},"supports":{"html":false},"editorScript":"file:./index.js"}');(0,e.registerBlockType)(s,{edit:n,save:o})}();