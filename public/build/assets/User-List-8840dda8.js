import{T as A,A as u,o as s,f as o,a as n,w as a,g as F,F as v,b as e,i as g,p as b,B as L,u as _,z as T,n as w,C as k,t as f,D as $}from"./app-ee162bce.js";import M from"./Layout-a469915b.js";const R=e("button",{class:"select-none text-blue-500 h-10 mr-7 border-b-2 relative font-bold border-blue-500"}," Active ",-1),V=e("button",{class:"mr-7 select-none text-gray-700 hover:text-black"}," Request ",-1),P=e("button",{class:"select-none text-gray-700 hover:text-black mr-7"}," Inactive ",-1),q={class:"w-full flex justify-end relative"},B=e("i",{class:"fas fa-search"},null,-1),j=[B],z={class:"mr-1"},K=e("button",{class:"w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Refresh page"},[e("i",{class:"fas fa-refresh"})],-1),O={class:"mr-1"},Y={class:"flex justify-between items-center px-2 min-w-6 border whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500"},G={key:0},J={key:1},Q={key:2},W={key:3},X=e("i",{class:"fas fa-caret-down ml-2"},null,-1),Z={class:"w-52"},ee=e("div",{class:"px-3 text-gray-600 text-sm font-bold tracking-tight"}," TYPE ",-1),te=e("th",{class:"p-3 pl-5"},"Name/Email",-1),se=e("th",{class:"p-3"},"Type",-1),oe=e("th",{class:"p-3"},"Role",-1),ne=e("th",{class:"p-3"},"Discipline",-1),ae=e("th",{class:"p-3"},"Program",-1),le=e("th",{class:"p-3"},"HEI Name",-1),ie={class:"p-3 text-right"},re=e("i",{class:"fas fa-ellipsis-v"},null,-1),ce=[re],de={key:0},he=e("td",{class:"py-10 text-center",colspan:"7"}," No users found ",-1),pe=[he],ue={class:"p-3 pl-5"},_e={class:"flex flex-row"},fe={class:"mr-3 w-10"},me=["src"],ge={class:"flex flex-col"},ve={class:"font-bold"},ye={class:"p-3"},be={class:"p-3 whitespace-normal"},we={class:"p-3 whitespace-normal"},ke={class:"p-3 whitespace-normal"},xe={class:"p-3 whitespace-normal"},Ce={class:"p-3 text-right"},Ee=e("button",{class:"select-none h-10 w-10 text-center text-white bg-green-600 hover:bg-green-700 rounded mr-1 tooltipForActions","data-tooltip":"View profile"},[e("i",{class:"fas fa-eye"})],-1),He=["onClick"],De=e("i",{class:"fas fa-lock"},null,-1),Se=[De],Ue=e("div",{class:"mr-2"},"Items per page",-1),Ie=e("option",{value:"25"},"25",-1),Ne=e("option",{value:50},"50",-1),Ae=e("option",{value:75},"75",-1),Fe=e("option",{value:100},"100",-1),Le=e("option",{value:200},"200",-1),Te=[Ie,Ne,Ae,Fe,Le],$e={key:0},Me={data(){return{modal:!1,selectedUser:"",modaltype:"",title:"",openSelectPageDropdown:!1}},layout:M,methods:{toggleModal(l,i,r){this.modal=!this.modal,this.selectedUser=l,this.modaltype=i,this.title=r},closeModal(){this.modal=!1}}},Pe=Object.assign(Me,{__name:"User-List",props:["user_list","canEdit","canFilter","showRequest","showInactive","filters"],setup(l){const i=l,r=A({show:i.filters.show,search:i.filters.search,type:i.filters.type});function m(){r.get("/admin/users/list",{preserveState:!0,preserveScroll:!0})}function C(){r.get("/admin/users/list",{preserveScroll:!1,preserveState:!1})}return(c,d)=>{const E=u("Head"),H=u("AdminPanel"),y=u("Link"),D=u("dropdown-option"),S=u("content-table"),U=u("content-container"),x=u("Notification"),I=u("Confirmation");return s(),o(v,null,[n(E,{title:"Users List"}),n(H),n(U,{onSubmit:m,pageTitle:l.canEdit?"Active Users List":"Users List",hasNavigation:l.canEdit,hasSearch:"true",hasFilters:"true",data_list:l.user_list},{navigation:a(()=>[e("div",null,[R,n(y,{href:c.route("admin.users.request")},{default:a(()=>[V]),_:1},8,["href"]),g(n(y,{href:c.route("admin.users.inactive")},{default:a(()=>[P]),_:1},8,["href"]),[[b,l.showInactive]])])]),search:a(()=>[e("div",q,[g(e("input",{onKeydown:T(m,["enter"]),"onUpdate:modelValue":d[0]||(d[0]=t=>_(r).search=t),type:"search",id:"content-search",placeholder:"Search a user",class:"w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2"},null,544),[[L,_(r).search]]),e("button",{onClick:m,class:"hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3"},j)])]),options:a(()=>[e("div",z,[n(y,{href:"/admin/users/list"},{default:a(()=>[K]),_:1})]),g(e("div",O,[n(D,{position:"right"},{button:a(()=>[e("button",Y,[i.filters.type==null?(s(),o("span",G,"Type")):i.filters.type=="CHED"?(s(),o("span",J,"CHED")):i.filters.type=="HEI"?(s(),o("span",Q,"HEI")):(s(),o("span",W,"Type")),X])]),options:a(()=>[e("div",Z,[ee,e("button",{"preserve-state":"",onClick:d[1]||(d[1]=t=>{_(r).type="",m()}),class:w(["w-full py-0.5 text-left indent-7 hover:bg-gray-200",{"bg-gray-300":i.filters.type==null}])}," All ",2),e("button",{onClick:d[2]||(d[2]=t=>{_(r).type="CHED",m()}),class:w(["w-full py-0.5 text-left indent-7 hover:bg-gray-200",{"bg-gray-300":i.filters.type=="CHED"}])}," CHED ",2),e("button",{onClick:d[3]||(d[3]=t=>{_(r).type="HEI",m()}),class:w(["w-full py-0.5 text-left indent-7 hover:bg-gray-200",{"bg-gray-300":i.filters.type=="HEI"}])}," HEI ",2)])]),_:1})],512),[[b,l.canFilter]])]),"main-content":a(()=>[n(S,null,{"table-head":a(()=>[te,se,oe,ne,ae,le,g(e("th",ie,ce,512),[[b,l.canEdit]])]),"table-body":a(()=>[l.user_list.data.length==0?(s(),o("tr",de,pe)):(s(!0),o(v,{key:1},k(l.user_list.data,(t,N)=>(s(),o("tr",{key:t.id,class:w(["hover:bg-slate-300 align-top",{"bg-slate-200":N%2==0}])},[e("td",ue,[e("div",_e,[e("div",fe,[e("img",{src:t.avatar?t.avatar:"/assets/user.png",alt:"user",class:"rounded-full"},null,8,me)]),e("div",ge,[e("div",ve,f(t.name),1),e("div",null,f(t.email),1)])])]),e("td",ye,f(t.type),1),e("td",be,f(t.role),1),e("td",we,[(s(!0),o(v,null,k(t.user_role,h=>{var p;return s(),o("div",{key:h.id},f((p=h.discipline)==null?void 0:p.discipline),1)}),128))]),e("td",ke,[(s(!0),o(v,null,k(t.user_role,h=>{var p;return s(),o("div",{key:h.id},f((p=h.program)==null?void 0:p.program),1)}),128))]),e("td",xe,[(s(!0),o(v,null,k(t.user_role,h=>{var p;return s(),o("div",{key:h.id},f((p=h.institution)==null?void 0:p.name),1)}),128))]),e("td",Ce,[n(y,{href:"/admin/users/list/profile/"+t.id+"/view"},{default:a(()=>[Ee]),_:2},1032,["href"]),g(e("button",{onClick:h=>c.toggleModal(t,"deactivateUser","Deactivate User"),class:"select-none h-10 w-10 text-center text-white bg-red-500 hover:bg-red-600 rounded mr-1 tooltipForActions","data-tooltip":"Deactivate"},Se,8,He),[[b,l.canEdit]])])],2))),128))]),_:1})]),"show-item":a(()=>[Ue,g(e("select",{"onUpdate:modelValue":d[4]||(d[4]=t=>_(r).show=t),id:"showResultCount",onChange:C,class:"select-none rounded h-8 w-20 p-1 text-sm"},Te,544),[[$,_(r).show]])]),_:1},8,["pageTitle","hasNavigation","data_list"]),n(x,{message:c.$page.props.flash.success,type:"success"},null,8,["message"]),n(x,{message:c.$page.props.flash.failed,type:"failed"},null,8,["message"]),n(x,{message:c.$page.props.flash.error,type:"failed"},null,8,["message"]),c.modal?(s(),o("div",$e,[n(I,{onClose:c.closeModal,title:c.title,modaltype:c.modaltype,selected:c.selectedUser},null,8,["onClose","title","modaltype","selected"])])):F("",!0)],64)}}});export{Pe as default};
