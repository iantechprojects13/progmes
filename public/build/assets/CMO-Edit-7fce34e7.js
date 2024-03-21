import{h as I,T as N,m as d,A as _,o as c,f as p,a as i,w as f,F as v,b as e,e as M,i as m,D as C,u as n,C as x,t as y,d as q,g as R,p as D,B as g,n as B}from"./app-f4d80686.js";import E from"./Layout-713aa5ab.js";const T={class:"relative"},j=e("button",{class:"px-3 h-10 bg-blue-500 hover:bg-blue-600 rounded text-white"},[q(" Save "),e("i",{class:"fas fa-caret-down ml-2"})],-1),H={class:"w-44"},P={class:"border-b border-gray-400"},z={class:"overflow-x-auto pb-5"},L=e("div",{class:"px-3 pt-3 font-bold text-gray-600 text-base"}," CMO Details ",-1),G={class:"w-full flex flex-col justify-between p-3"},J={class:"flex flex-col lg:flex-row"},K={class:"w-full flex flex-col mt-2"},Q=e("label",{for:"discipline",class:"font-bold text-gray-600"},"Discipline",-1),W=e("option",{value:null}," Select discipline ",-1),X=["value"],Y={class:"w-full flex flex-col mt-2"},Z=e("label",{for:"program",class:"font-bold text-gray-600"},"Program",-1),ee=["disabled"],te=e("option",{value:null}," Select program ",-1),oe=["value"],se={key:0},re={class:"flex flex-col lg:flex-row"},ne={class:"w-full flex flex-col mt-2"},le=e("label",{for:"number",class:"font-bold text-gray-600"},"CMO No.",-1),ae={class:"w-full flex flex-col mt-2"},ie=e("label",{for:"series",class:"font-bold text-gray-600"},"Series",-1),de={class:"w-full flex flex-col mt-2"},ce=e("label",{for:"version",class:"font-bold text-gray-600"},"Version",-1),pe=e("th",{class:"px-2 py-3 border-t border-gray-400"}," Item No ",-1),me=e("th",{class:"px-2 py-3 border-t border-gray-400"}," Area ",-1),ue=e("th",{class:"px-2 py-3 border-t border-gray-400"}," Minimum Requirement ",-1),he={class:"w-24 text-center align-top py-5 font-bold"},_e={class:"max-w-18 w-1/4 min-w-18 py-5 px-2 align-top"},fe=["id","onUpdate:modelValue","onFocus","onInput"],be={class:"min-w-18 py-5 px-2 align-top"},ge=["id","onUpdate:modelValue","onFocus","onInput"],ve={data(){return{openSaveOption:!1}},layout:E,methods:{handleAreaInput(u){let s=this.$refs.area[u];s.style.height="18px",s.style.height=s.scrollHeight+"px"},handleMinreqInput(u){let s=this.$refs.minreq[u];s.style.height="18px",s.style.height=s.scrollHeight+"px"}}},we=Object.assign(ve,{__name:"CMO-Edit",props:["cmo","discipline_list","program_list"],setup(u){const s=u,w=I(()=>{let l=[];for(var r=0;r<s.cmo.criteria.length;r++)l.push(s.cmo.criteria[r].area);return l}),V=I(()=>{let l=[];for(var r=0;r<s.cmo.criteria.length;r++)l.push(s.cmo.criteria[r].minimumRequirement);return l}),o=N({id:d(s.cmo.id),discipline:d(s.cmo.disciplineId)!==null?d(s.cmo.disciplineId):null,program:d(s.cmo.programId)!==null?d(s.cmo.programId):null,number:d(s.cmo.number),series:d(s.cmo.series),version:d(s.cmo.version),area:w,minReq:V});function k(){o.post("/admin/CMOs/save-and-publish",o)}function $(){o.post("/admin/CMOs/save-as-draft",o)}return(l,r)=>{const A=_("Head"),O=_("AdminPanel"),S=_("dropdown-option"),b=_("FormErrorMessage"),U=_("content-table"),F=_("content-container");return c(),p(v,null,[i(A,{title:"Edit CMO"}),i(O),i(F,{pageTitle:"New CMO",hasTopButton:"true"},{"top-button":f(()=>[e("div",T,[i(S,{position:"right"},{button:f(()=>[j]),options:f(()=>[e("div",H,[e("button",{class:"py-1.5 hover:bg-gray-200 w-full text-left indent-7",onClick:M($,["prevent"])}," Save as draft "),e("button",{class:"py-1.5 hover:bg-gray-200 w-full text-left indent-7",onClick:M(k,["prevent"])}," Publish ")])]),_:1})])]),"main-content":f(()=>[e("div",P,[e("div",z,[L,e("div",G,[e("div",J,[e("div",K,[Q,m(e("select",{id:"discipline","onUpdate:modelValue":r[0]||(r[0]=t=>n(o).discipline=t),class:"py-1 px-3 h-10 text-sm border border-gray-400 rounded m-1",onChange:r[1]||(r[1]=t=>n(o).program=null)},[W,(c(!0),p(v,null,x(u.discipline_list,t=>(c(),p("option",{key:t.id,value:t.id},y(t.discipline),9,X))),128))],544),[[C,n(o).discipline]]),i(b,{message:l.$page.props.errors.discipline,theme:"dark"},null,8,["message"])]),e("div",Y,[Z,m(e("select",{id:"program","onUpdate:modelValue":r[2]||(r[2]=t=>n(o).program=t),class:"py-1 px-3 h-10 text-sm border border-gray-400 rounded m-1",disabled:!n(o).discipline},[te,(c(!0),p(v,null,x(u.program_list,t=>m((c(),p("option",{key:t.id,value:t.id},[q(y(t.program)+" ",1),t.major!=null?(c(),p("span",se," - "+y(t.major),1)):R("",!0)],8,oe)),[[D,t.disciplineId==n(o).discipline]])),128))],8,ee),[[C,n(o).program]]),i(b,{message:l.$page.props.errors.program,theme:"dark"},null,8,["message"])])]),e("div",re,[e("div",ne,[le,m(e("input",{id:"number",type:"number",placeholder:"CMO No.","onUpdate:modelValue":r[3]||(r[3]=t=>n(o).number=t),class:"h-10 m-1 rounded text-sm border border-gray-400"},null,512),[[g,n(o).number]]),i(b,{message:l.$page.props.errors.number,theme:"dark"},null,8,["message"])]),e("div",ae,[ie,m(e("input",{id:"series",type:"number",placeholder:"Series","onUpdate:modelValue":r[4]||(r[4]=t=>n(o).series=t),class:"h-10 m-1 rounded text-sm border border-gray-400"},null,512),[[g,n(o).series]]),i(b,{message:l.$page.props.errors.series,theme:"dark"},null,8,["message"])]),e("div",de,[ce,m(e("input",{id:"version",type:"number",placeholder:"Version","onUpdate:modelValue":r[5]||(r[5]=t=>n(o).version=t),class:"h-10 m-1 rounded text-sm border border-gray-400"},null,512),[[g,n(o).version]]),i(b,{message:l.$page.props.errors.version,theme:"dark"},null,8,["message"])])])])])]),i(U,null,{"table-head":f(()=>[pe,me,ue]),"table-body":f(()=>[(c(!0),p(v,null,x(w.value,(t,a)=>(c(),p("tr",{key:a,class:B({"bg-slate-200":a%2==1})},[e("td",he,y(a+1),1),e("td",_e,[m(e("textarea",{ref_for:!0,ref:"area",id:"area"+a,"onUpdate:modelValue":h=>n(o).area[a]=h,class:"areaInput min-h-max h-10 resize-none text-sm rounded w-full overflow-hidden border border-gray-400",onFocus:h=>l.handleAreaInput(a),onInput:h=>l.handleAreaInput(a)},null,40,fe),[[g,n(o).area[a]]])]),e("td",be,[m(e("textarea",{ref_for:!0,ref:"minreq",id:"minReq"+a,"onUpdate:modelValue":h=>n(o).minReq[a]=h,class:"minreqInput min-h-max resize-none text-sm bg-white rounded w-full overflow-hidden border border-gray-400",onFocus:h=>l.handleMinreqInput(a),onInput:h=>l.handleMinreqInput(a)},null,40,ge),[[g,n(o).minReq[a]]])])],2))),128))]),_:1})]),_:1})],64)}}});export{we as default};
