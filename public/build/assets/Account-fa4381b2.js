import{h as l,A as c,o as n,f as i,a as d,w as v,F as h,b as t,t as a,i as p,p as b,C as A}from"./app-ee162bce.js";import D from"./Layout-a469915b.js";const H={class:"m-10"},I={class:"border border-gray-400"},k=t("th",{scope:"row",class:"px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap"}," Name",-1),C={class:"px-3 py-1 align-top border-b border-gray-400 w-full"},E=t("th",{scope:"row",class:"px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap"}," Account Type",-1),P={class:"px-3 py-1 align-top border-b border-gray-400 w-full"},N={key:0},B={key:1},F=t("th",{scope:"row",class:"px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap"}," Role",-1),L={class:"px-3 py-1 align-top border-b border-gray-400 w-full"},M=t("th",{scope:"row",class:"px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap"}," HEI Name",-1),S={class:"px-3 py-1 align-top border-b border-gray-400 w-full"},T=t("th",{scope:"row",class:"px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap"}," Discipline",-1),V={class:"px-3 py-1 align-top border-b border-gray-400 w-full"},j=t("th",{scope:"row",class:"px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap"}," Program",-1),O={class:"px-3 py-1 align-top border-b border-gray-400 w-full"},R={layout:D},G=Object.assign(R,{__name:"Account",props:["profile","roles","isAdmin","hasDiscipline","hasInstitution","hasProgram"],setup(r){const s=r,g=l(()=>{let e=[];if(s.hasDiscipline)for(var o=0;o<s.roles.length;o++)e.push(s.roles[o].discipline.discipline);return e}),u=l(()=>{var o;let e="";return s.hasProgram&&(e=(o=s.roles[0])==null?void 0:o.program.program),e}),_=l(()=>{var o;let e="";return s.hasInstitution&&(e=(o=s.roles[0])==null?void 0:o.institution.name),e});return(e,o)=>{const y=c("Head"),m=c("page-title"),w=c("content-container");return n(),i(h,null,[d(y,{title:"My Account"}),d(m,{title:"My Account"}),d(w,{pageTitle:"Account Details"},{"main-content":v(()=>[t("div",H,[t("table",I,[t("tr",null,[k,t("td",C,a(r.profile.name),1)]),t("tr",null,[E,t("td",P,[r.profile.type=="CHED"?(n(),i("div",N," Commission on Higher Education ")):(n(),i("div",B," Higher Education Institution "))])]),t("tr",null,[F,t("td",L,a(r.profile.role),1)]),p(t("tr",null,[M,t("td",S,a(_.value),1)],512),[[b,r.hasInstitution]]),p(t("tr",null,[T,t("td",V,[(n(!0),i(h,null,A(g.value,(f,x)=>(n(),i("div",{key:x},a(f),1))),128))])],512),[[b,r.hasDiscipline]]),p(t("tr",null,[j,t("td",O,a(u.value),1)],512),[[b,r.hasProgram]])])])]),_:1})],64)}}});export{G as default};
