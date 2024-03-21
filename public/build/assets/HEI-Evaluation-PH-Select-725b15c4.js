import{m as N,A as i,o as n,f as l,a as r,b as e,t as _,w as u,F as m,C as D,n as $,d as p,i as o,p as a,O as h}from"./app-f4d80686.js";import H from"./Layout-713aa5ab.js";const I={class:"md:mx-8 mx-3 mt-8 bg-white p-5 rounded"},B={class:"flex flex-col w-full rounded"},P={class:"w-auto flex flex-col md:flex-row"},V=e("div",{class:"mr-2 font-bold"}," HEI Name: ",-1),F={class:"w-auto flex flex-col md:flex-row mt-2 md:mt-1"},L={class:"w-auto flex flex-col md:flex-row"},O=e("div",{class:"mr-2 font-bold"}," Program: ",-1),A=e("th",{class:"p-3 border-b border-gray-400"},"Academic Year",-1),T=e("th",{class:"p-3 border-b border-gray-400"},"Status",-1),j=e("th",{class:"p-3 border-b border-gray-400 text-right"},[e("i",{class:"fas fa-ellipsis-v"})],-1),z={key:0},Y=e("td",{colspan:"3",class:"text-center py-10"},"No evaluation tool found",-1),q=[Y],G={class:"p-3 align-middle"},J=e("i",{class:"fas fa-file text-lg mr-2"},null,-1),K={class:"p-3 align-middle"},M={class:"text-white text-sm"},Q={class:"p-1 bg-green-600 rounded"},R={class:"p-1 bg-blue-500 rounded"},U={class:"p-1 bg-red-500 rounded"},W={class:"p-1 bg-blue-500 rounded"},X={class:"p-3 text-right"},Z=["disabled","onClick"],ee={key:0},te=e("i",{class:"fas fa-spinner animate-spin"},null,-1),se=[te],oe={key:1},ae=["onClick"],ne=e("i",{class:"fas fa-edit"},null,-1),le=["onClick"],re=e("i",{class:"fas fa-eye"},null,-1),ie={layout:H},ue=Object.assign(ie,{__name:"HEI-Evaluation-PH-Select",props:["program"],setup(d){const c=N(!1);function v(s){h.get("/hei/evaluation/"+s+"/edit")}function x(s){h.get("/hei/evaluation/"+s+"/view")}function y(s){h.post("/hei/tool/create",{id:s},{onStart:()=>{c.value=!0},onFinish:()=>{c.value=!1},preserveState:!1})}return(s,de)=>{var g;const w=i("Head"),k=i("page-title"),S=i("content-table"),C=i("content-container"),f=i("Notification");return n(),l(m,null,[r(w,{title:"Program Self-Evaluation"}),r(k,{title:"Program Self-Evaluation"}),e("div",I,[e("div",B,[e("div",P,[V,e("div",null,_((g=d.program.institution)==null?void 0:g.name),1)]),e("div",F,[e("div",L,[O,e("div",null,_(d.program.program.program),1)])])])]),r(C,null,{"main-content":u(()=>[r(S,null,{"table-head":u(()=>[A,T,j]),"table-body":u(()=>[d.program.evaluation_form.length==0?(n(),l("tr",z,q)):(n(!0),l(m,{key:1},D(d.program.evaluation_form,(t,E)=>(n(),l("tr",{key:t.id,class:$(["hover:bg-gray-100 border-b border-gray-300",{"bg-slate-200":E%2==0}])},[e("td",G,[J,p(_(t.effectivity),1)]),e("td",K,[e("div",M,[o(e("span",Q," Deployed ",512),[[a,t.status==="Deployed"]]),o(e("span",R," In progress ",512),[[a,t.status==="In progress"]]),o(e("span",U," Locked ",512),[[a,t.status==="Locked"]]),o(e("span",W," Submitted ",512),[[a,t.status==="Submitted"]])])]),e("td",X,[o(e("button",{disabled:c.value,class:"rounded h-10 px-2 w-24 text-white bg-blue-500 hover:bg-blue-600",onClick:b=>y(t.id)},[c.value?(n(),l("span",ee,se)):(n(),l("span",oe,"Start now"))],8,Z),[[a,t.status==="Deployed"]]),o(e("button",{onClick:b=>v(t.id),ref_for:!0,ref:"editButton",class:"rounded h-10 px-2 mr-1 text-gray-700 hover:text-black active:text-blue-500 border-2 border-gray-500 bg-stone-100"},[ne,p(" Edit ")],8,ae),[[a,t.status==="In progress"]]),o(e("button",{onClick:b=>x(t.id),class:"rounded h-10 px-2 text-gray-700 hover:text-black active:text-blue-500 border-2 border-gray-500 bg-stone-100"},[re,p(" View ")],8,le),[[a,t.status!="Deployed"]])])],2))),128))]),_:1})]),_:1}),r(f,{message:s.$page.props.flash.failed,type:"failed"},null,8,["message"]),r(f,{message:s.$page.props.flash.success,type:"success"},null,8,["message"])],64)}}});export{ue as default};
