!function(){"use strict";var e=window.wp.blocks,t=window.wp.element,o=window.wp.i18n,a=window.wp.coreData,n=window.wp.blockEditor,r=window.wp.components,s=window.wp.data,i=window.wp.editor;const c="_wat-campaign",l="wat-campaign",p="wat-sponsor",m=async(e,t)=>{const o=await(a=c,n={name:t},(0,s.dispatch)("core").saveEntityRecord("taxonomy",a,n));var a,n,r,i;return await(r=l,i={title:t,status:"publish",[p]:[e],[c]:[o.id]},(0,s.dispatch)("core").saveEntityRecord("postType",r,i)),o};var d=window.wp.primitives,g=(0,t.createElement)(d.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(d.Path,{d:"M19 3H5c-.6 0-1 .4-1 1v7c0 .5.4 1 1 1h14c.5 0 1-.4 1-1V4c0-.6-.4-1-1-1zM5.5 10.5v-.4l1.8-1.3 1.3.8c.3.2.7.2.9-.1L11 8.1l2.4 2.4H5.5zm13 0h-2.9l-4-4c-.3-.3-.8-.3-1.1 0L8.9 8l-1.2-.8c-.3-.2-.6-.2-.9 0l-1.3 1V4.5h13v6zM4 20h9v-1.5H4V20zm0-4h16v-1.5H4V16z"}));const u=["image"],w=e=>{const{onSelectImage:a,onUploadError:r,noticeUI:s,title:i,instructions:c}=e;return(0,t.createElement)(n.MediaPlaceholder,{style:{height:"100%"},icon:(0,t.createElement)(n.BlockIcon,{icon:g}),onSelect:a,notices:s,onError:r,accept:"image/*",allowedTypes:u,labels:{title:i||(0,o.__)("Featured Image","wrt-theme"),instructions:c||(0,o.__)("Upload a media file or pick one from your media library.","wrt-theme")}})},E=e=>{const{className:a}=e;return(0,t.createElement)("div",{className:a,style:{height:"100%",backgroundColor:"#E3E3E3",display:"flex",alignItems:"center",flexDirection:"column",justifyContent:"center"}},(0,t.createElement)(r.Icon,{icon:g}),(0,t.createElement)("p",null,(0,o.__)("Loading the Featured Image","wrt-theme")),(0,t.createElement)(r.Spinner,null))},h=e=>{const{media:o,size:a="full"}=e,n=o?.media_details?.sizes?.[a]?.source_url||o?.source_url||"";return(0,t.createElement)("img",{className:"single-post--hero-image wp-post-image",src:n,alt:"logo"})},y=({postType:e,postId:t,noticeUI:o,noticeOperations:n})=>{const[r,i]=(0,a.useEntityProp)("postType",e,"featured_media",t),c=(0,s.useSelect)((e=>r&&e(a.store).getMedia(r,{context:"view"})),[r]);return{ALLOWED_MEDIA_TYPES:u,featuredImage:r,setFeaturedImage:i,media:c,noticeUI:o,FeaturedMediaImage:h,PlaceholderChip:E,FeaturedMediaPlaceholder:w,onUploadError:e=>{n.removeAllNotices(),n.createErrorNotice(e)},onSelectImage:e=>{e&&e.id&&i(e.id)}}},I="wat-campaign";var _=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"title":"Post Sponsor","description":"Post Sponsor","textdomain":"wrt-theme","name":"wrt/post-sponsor","icon":"index-card","category":"formatting","attributes":{"sponsorLogo":{"type":"object"},"sponsorId":{"type":"integer"},"sponsorDescription":{"type":"string"}},"usesContext":["postId","postType"],"supports":{"html":false,"reusable":false},"editorScript":"file:./index.js"}');(0,e.registerBlockType)(_,{edit:e=>{const{context:{postId:d,postType:g},noticeUI:u,noticeOperations:w}=e,{campaign:E}=(()=>{const e=(0,s.useSelect)((e=>{const t=e("core/editor").getEditedPostAttribute(c)?.[0]||0,o=e("core").getEntityRecords("postType",l,{[c]:[t]});return{campaign:o?.[0]||!1,sponsor:e("core").getEntityRecord("taxonomy",p,o?.[0]?.[p]?.[0])}})),{editPost:t}=(0,s.useDispatch)(i.store),o=e=>{t({[c]:[e.id]})};return{...e,sponsoredCampaignProps:{...e,onSelectCampaign:o,onResetCampaign:()=>{t({[c]:[]})},onAddNewCampaign:async(e,t)=>{const a=await m(e,t);o(a)}}}})();let h=0;h=g===I?d:E.id;const _=(0,n.useBlockProps)({className:"single-post--sponsor sponsor-desktop"}),[v,C]=(0,a.useEntityProp)("postType",I,"meta",h),[P,S]=(0,a.useEntityProp)("postType",I,"excerpt",h),{ALLOWED_MEDIA_TYPES:T,media:x,FeaturedMediaImage:f,FeaturedMediaPlaceholder:b,PlaceholderChip:k,featuredImage:M,onSelectImage:N,onUploadError:U}=y({postType:I,postId:h,noticeOperations:w,noticeUI:u});let F;return F=M?x?(0,t.createElement)(f,{media:x,size:"sponsor-logo"}):(0,t.createElement)(k,null):(0,t.createElement)(b,{onSelectImage:N,onUploadError:U,noticeUI:u}),E||g===I?(0,t.createElement)(t.Fragment,null,!!x&&(0,t.createElement)(t.Fragment,null,(0,t.createElement)(n.BlockControls,null,(0,t.createElement)(n.MediaReplaceFlow,{mediaId:M,mediaURL:x.source_url,allowedTypes:T,accept:"image/*",onSelect:N,onError:U})),(0,t.createElement)(n.InspectorControls,null,(0,t.createElement)(r.PanelBody,{title:(0,o.__)("Post Sponsor Settings","wrt-theme")},(0,t.createElement)(r.TextControl,{label:(0,o.__)("Campaign Link"),value:v?.["campaign-url"]||"",onChange:e=>{C({"campaign-url":e})}}),(0,t.createElement)(r.TextControl,{label:(0,o.__)("Campaign CTA"),value:v?.["campaign-cta"]||"",onChange:e=>{C({"campaign-cta":e})}})))),(0,t.createElement)("div",_,(0,t.createElement)("div",{className:"post-card__sponsored-by"},(0,t.createElement)("div",{className:"s-display-logo"},F)),(0,t.createElement)(n.RichText,{value:P,onChange:S,tagName:"div",placeholder:(0,o.__)("Add as description for this campaign...","wrt-theme"),className:"single-post--sponsor-content"}))):null},save:()=>null})}();