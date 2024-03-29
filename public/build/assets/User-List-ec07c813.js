import{T,A as u,o as s,f as o,a as c,w as a,g as F,F as v,b as t,i as g,p as y,B as L,u as p,z as $,n as b,C as w,t as m,D as M}from"./app-612f8f79.js";import R from"./Layout-688c6e41.js";const P=t("button",{class:"select-none text-blue-500 h-10 mr-7 border-b-2 relative font-bold border-blue-500"}," Active ",-1),V=t("button",{class:"mr-7 select-none text-gray-700 hover:text-black"}," Request ",-1),q=t("button",{class:"select-none text-gray-700 hover:text-black mr-7"}," Inactive ",-1),B={class:"w-full flex justify-end relative"},j=t("i",{class:"fas fa-search"},null,-1),z=[j],K={class:"mr-1"},O=t("button",{class:"w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Refresh page"},[t("i",{class:"fas fa-refresh"})],-1),Y={class:"mr-1"},G={class:"flex justify-between items-center px-2 min-w-6 border whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500"},J={key:0},Q={key:1},W={key:2},X={key:3},Z=t("i",{class:"fas fa-caret-down ml-2"},null,-1),tt={class:"w-52"},et=t("div",{class:"px-3 text-gray-600 text-sm font-bold tracking-tight"}," TYPE ",-1),st=t("th",{class:"p-3 pl-5"},"Name/Email",-1),ot=t("th",{class:"p-3"},"Type",-1),nt=t("th",{class:"p-3"},"Role",-1),at=t("th",{class:"p-3"},"Discipline",-1),lt=t("th",{class:"p-3"},"Program",-1),it=t("th",{class:"p-3"},"HEI Name",-1),ct={class:"p-3 text-right"},rt=t("i",{class:"fas fa-ellipsis-v"},null,-1),dt=[rt],ht={key:0},_t=t("td",{class:"py-10 text-center",colspan:"7"}," No users found ",-1),ut=[_t],pt={class:"p-3 pl-5"},mt={class:"flex flex-row"},ft={class:"mr-3 w-10"},gt=["src"],vt={class:"flex flex-col"},yt={class:"font-bold"},bt={class:"p-3"},wt={class:"p-3 whitespace-normal"},xt={class:"p-3 whitespace-normal"},kt={class:"p-3 whitespace-normal"},Ct={class:"p-3 whitespace-normal"},Et={class:"p-3 text-right"},Ht=["onClick"],Dt=t("i",{class:"fas fa-lock"},null,-1),St=[Dt],Ut=t("div",{class:"mr-2"},"Items per page",-1),It=t("option",{value:"25"},"25",-1),Nt=t("option",{value:50},"50",-1),At=t("option",{value:75},"75",-1),Tt=t("option",{value:100},"100",-1),Ft=t("option",{value:200},"200",-1),Lt=[It,Nt,At,Tt,Ft],$t={key:0},Mt={data(){return{modal:!1,selectedUser:"",modaltype:"",title:"",openSelectPageDropdown:!1}},layout:R,methods:{toggleModal(n,l,i){this.modal=!this.modal,this.selectedUser=n,this.modaltype=l,this.title=i},closeModal(){this.modal=!1}}},Vt=Object.assign(Mt,{__name:"User-List",props:["user_list","canEdit","canFilter","showRequest","showInactive","filters"],setup(n){const l=n,i=T({show:l.filters.show,search:l.filters.search,type:l.filters.type});function f(){i.get("/admin/users/list",{preserveState:!0,preserveScroll:!0})}function E(){i.get("/admin/users/list",{preserveScroll:!1,preserveState:!1})}return(r,d)=>{const H=u("Head"),D=u("AdminPanel"),x=u("Link"),S=u("dropdown-option"),U=u("content-table"),I=u("content-container"),k=u("Notification"),N=u("Confirmation");return s(),o(v,null,[c(H,{title:"Users List"}),c(D),c(I,{onSubmit:f,pageTitle:n.canEdit?"Active Users List":"Users List",hasNavigation:n.canEdit,hasSearch:"true",hasFilters:"true",data_list:n.user_list},{navigation:a(()=>[t("div",null,[P,c(x,{href:r.route("admin.users.request")},{default:a(()=>[V]),_:1},8,["href"]),g(c(x,{href:r.route("admin.users.inactive")},{default:a(()=>[q]),_:1},8,["href"]),[[y,n.showInactive]])])]),search:a(()=>[t("div",B,[g(t("input",{onKeydown:$(f,["enter"]),"onUpdate:modelValue":d[0]||(d[0]=e=>p(i).search=e),type:"search",id:"content-search",placeholder:"Search a user",class:"w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2"},null,544),[[L,p(i).search]]),t("button",{onClick:f,class:"hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3"},z)])]),options:a(()=>[t("div",K,[c(x,{href:"/admin/users/list"},{default:a(()=>[O]),_:1})]),g(t("div",Y,[c(S,{position:"right"},{button:a(()=>[t("button",G,[l.filters.type==null?(s(),o("span",J,"Type")):l.filters.type=="CHED"?(s(),o("span",Q,"CHED")):l.filters.type=="HEI"?(s(),o("span",W,"HEI")):(s(),o("span",X,"Type")),Z])]),options:a(()=>[t("div",tt,[et,t("button",{"preserve-state":"",onClick:d[1]||(d[1]=e=>{p(i).type="",f()}),class:b(["w-full py-0.5 text-left indent-7 hover:bg-gray-200",{"bg-gray-300":l.filters.type==null}])}," All ",2),t("button",{onClick:d[2]||(d[2]=e=>{p(i).type="CHED",f()}),class:b(["w-full py-0.5 text-left indent-7 hover:bg-gray-200",{"bg-gray-300":l.filters.type=="CHED"}])}," CHED ",2),t("button",{onClick:d[3]||(d[3]=e=>{p(i).type="HEI",f()}),class:b(["w-full py-0.5 text-left indent-7 hover:bg-gray-200",{"bg-gray-300":l.filters.type=="HEI"}])}," HEI ",2)])]),_:1})],512),[[y,n.canFilter]])]),"main-content":a(()=>[c(U,null,{"table-head":a(()=>[st,ot,nt,at,lt,it,g(t("th",ct,dt,512),[[y,n.canEdit]])]),"table-body":a(()=>[n.user_list.data.length==0?(s(),o("tr",ht,ut)):(s(!0),o(v,{key:1},w(n.user_list.data,(e,A)=>(s(),o("tr",{key:e.id,class:b(["hover:bg-slate-300 align-top",{"bg-slate-200":A%2==0}])},[t("td",pt,[t("div",mt,[t("div",ft,[t("img",{src:e.avatar?e.avatar:"/assets/user.png",alt:"user",class:"rounded-full"},null,8,gt)]),t("div",vt,[t("div",yt,m(e.name),1),t("div",null,m(e.email),1)])])]),t("td",bt,m(e.type),1),t("td",wt,m(e.role),1),t("td",xt,[(s(!0),o(v,null,w(e.user_role,(h,C)=>{var _;return s(),o("div",{key:h.id},m((_=h.discipline)==null?void 0:_.discipline),1)}),128))]),t("td",kt,[(s(!0),o(v,null,w(e.user_role,(h,C)=>{var _;return s(),o("div",{key:h.id},m((_=h.program)==null?void 0:_.program),1)}),128))]),t("td",Ct,[(s(!0),o(v,null,w(e.user_role,(h,C)=>{var _;return s(),o("div",{key:h.id},m((_=h.institution)==null?void 0:_.name),1)}),128))]),g(t("td",Et,[t("button",{onClick:h=>r.toggleModal(e,"deactivateUser","Deactivate User"),class:"select-none h-10 w-10 text-center text-white bg-red-500 hover:bg-red-600 rounded mr-1 tooltipForActions","data-tooltip":"Deactivate"},St,8,Ht)],512),[[y,n.canEdit]])],2))),128))]),_:1})]),"show-item":a(()=>[Ut,g(t("select",{"onUpdate:modelValue":d[4]||(d[4]=e=>p(i).show=e),id:"showResultCount",onChange:E,class:"select-none rounded h-8 w-20 p-1 text-sm"},Lt,544),[[M,p(i).show]])]),_:1},8,["pageTitle","hasNavigation","data_list"]),c(k,{message:r.$page.props.flash.success,type:"success"},null,8,["message"]),c(k,{message:r.$page.props.flash.failed,type:"failed"},null,8,["message"]),r.modal?(s(),o("div",$t,[c(N,{onClose:r.closeModal,title:r.title,modaltype:r.modaltype,selected:r.selectedUser},null,8,["onClose","title","modaltype","selected"])])):F("",!0)],64)}}});export{Vt as default};
