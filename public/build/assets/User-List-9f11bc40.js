import{m as E,T as $,A as _,o as s,f as o,a,i as p,p as w,b as e,w as n,F as y,B as q,u as g,z as B,C as x,n as R,t as h,d as j,g as P,D as M}from"./app-a1d3aa91.js";import S from"./Layout-8eb335a9.js";const z=e("button",{class:"select-none h-12 w-24 hover:bg-gray-100 text-blue-500 border-b-4 relative font-bold border-blue-500"}," Active ",-1),K=e("button",{class:"select-none h-12 w-24 hover:bg-gray-100 text-gray-700 hover:text-black"}," Request ",-1),O=e("button",{class:"select-none h-12 w-24 hover:bg-gray-100 text-gray-700 hover:text-black"}," Inactive ",-1),G={class:"w-full flex flex-row relative items-center"},J=e("i",{class:"fa fa-search text-gray-400 absolute left-5"},null,-1),Q={class:"mr-1"},W=e("i",{class:"fas fa-filter"},null,-1),X=[W],Y=e("button",{class:"w-10 h-10 hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Refresh page"},[e("i",{class:"fas fa-refresh"})],-1),Z=e("th",{class:"p-2 pl-5"},"Name/Email",-1),ee=e("th",{class:"p-2"},"Type",-1),te=e("th",{class:"p-2"},"Role",-1),se=e("th",{class:"p-2"},"Discipline",-1),oe=e("th",{class:"p-2"},"Program",-1),le=e("th",{class:"p-2"},"HEI Name",-1),ae={class:"p-2 pr-5 text-right"},ne=e("i",{class:"fas fa-ellipsis-v"},null,-1),ie=[ne],re={key:0},ce=e("td",{class:"py-10 text-center",colspan:"7"}," No users found ",-1),de=[ce],he={class:"p-3 pl-5"},ue={class:"flex flex-row"},_e={class:"mr-3 w-10"},pe=["src"],fe={class:"flex flex-col"},ge={class:"font-bold"},me={class:"p-2"},ve={class:"p-2"},ye={class:"p-2"},be={class:"p-2"},we={key:0},xe={class:"p-2"},ke={class:"p-2 pr-5 text-right whitespace-nowrap"},Ce=e("button",{class:"select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions","data-tooltip":"View profile"},[e("i",{class:"fas fa-eye"})],-1),Fe=["onClick"],Ue=e("i",{class:"fas fa-lock"},null,-1),Ee=[Ue],Me={class:"flex flex-col"},Se=e("label",{for:"type"},"Account Type",-1),Ae=e("option",{value:null},"All",-1),Ne=e("option",{value:"HEI"},"HEI",-1),Le=e("option",{value:"CHED"},"CHED",-1),De=[Ae,Ne,Le],Ve={class:"mt-3 flex flex-col"},He=e("label",{for:"show"},"Items per page",-1),Ie=e("option",{value:"25"},"25",-1),Te=e("option",{value:"50"},"50",-1),$e=e("option",{value:"100"},"100",-1),qe=e("option",{value:"200"},"200",-1),Be=[Ie,Te,$e,qe],Re={key:0},je=e("i",{class:"fas fa-spinner animate-spin"},null,-1),Pe=[je],ze={key:1},Ke={components:{Layout:S},data(){return{modal:!1,selectedUser:"",modaltype:"",title:"",openSelectPageDropdown:!1}},layout:S,methods:{toggleModal(i,u,m){this.modal=!this.modal,this.selectedUser=i,this.modaltype=u,this.title=m},closeModal(){this.modal=!1}}},Je=Object.assign(Ke,{__name:"User-List",props:["user_list","canEdit","canFilter","showRequest","showInactive","filters"],setup(i){const u=i,m=E(!1),v=E(!1),c=$({show:u.filters.show!=null?u.filters.show:null,search:u.filters.search,type:u.filters.type!=null?u.filters.type:null});function k(){m.value=!m.value}function A(){c.get("/admin/users/list",{onStart:()=>{v.value=!0},onFinish:()=>{v.value=!1},preserveState:!1,preserveScroll:!0})}function N(){c.get("/admin/users/list",{onStart:()=>{v.value=!0},onFinish:()=>{v.value=!1,k()},preserveState:!0,preserveScroll:!0})}return(l,f)=>{const L=_("Head"),b=_("Link"),D=_("content-table"),V=_("content-container"),H=_("modal"),C=_("Notification"),I=_("Confirmation");return s(),o(y,null,[a(L,{title:"Users List"}),p(e("div",null,"Loading...",512),[[w,l.isLoading]]),a(V,{hasAdminPanel:!0,pageTitle:i.canEdit?"Active Users List":"Users List",page:"user",hasNavigation:i.canEdit,hasSearch:!0,hasFilters:!0,data_list:i.user_list},{navigation:n(()=>[z,a(b,{href:l.route("admin.users.request")},{default:n(()=>[K]),_:1},8,["href"]),p(a(b,{href:l.route("admin.users.inactive")},{default:n(()=>[O]),_:1},8,["href"]),[[w,i.showInactive]])]),search:n(()=>[e("div",G,[J,p(e("input",{onKeydown:B(A,["enter"]),"onUpdate:modelValue":f[0]||(f[0]=t=>g(c).search=t),type:"search",id:"content-search",placeholder:"Search",class:"w-full rounded-lg border border-gray-300 indent-10 h-10 text-base placeholder-gray-400"},null,544),[[q,g(c).search]])])]),options:n(()=>[e("div",Q,[e("button",{onClick:k,class:"w-10 h-10 hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Filters"},X),a(b,{href:"/admin/users/list"},{default:n(()=>[Y]),_:1})])]),"main-content":n(()=>[a(D,null,{"table-head":n(()=>[Z,ee,te,se,oe,le,p(e("th",ae,ie,512),[[w,i.canEdit]])]),"table-body":n(()=>[i.user_list.data.length==0?(s(),o("tr",re,de)):(s(!0),o(y,{key:1},x(i.user_list.data,(t,T)=>(s(),o("tr",{key:t.id,class:R(["hover:bg-gray-200 border-b align-top",{"bg-gray-100":T%2==1}])},[e("td",he,[e("div",ue,[e("div",_e,[e("img",{src:t.avatar?t.avatar:"/assets/user.png",alt:"user",class:"rounded-full"},null,8,pe)]),e("div",fe,[e("div",ge,h(t.name),1),e("div",null,h(t.email),1)])])]),e("td",me,h(t.type),1),e("td",ve,h(t.role),1),e("td",ye,[(s(!0),o(y,null,x(t.user_role,r=>{var d;return s(),o("div",{key:r.id},h((d=r.discipline)==null?void 0:d.discipline),1)}),128))]),e("td",be,[(s(!0),o(y,null,x(t.user_role,r=>{var d,F,U;return s(),o("div",{key:r.id},[j(h((d=r.program)==null?void 0:d.program)+" ",1),((F=r.program)==null?void 0:F.major)!=null?(s(),o("span",we," - "+h((U=r.program)==null?void 0:U.major),1)):P("",!0)])}),128))]),e("td",xe,[(s(!0),o(y,null,x(t.user_role,r=>{var d;return s(),o("div",{key:r.id},h((d=r.institution)==null?void 0:d.name),1)}),128))]),e("td",ke,[a(b,{href:"/admin/users/list/profile/"+t.id+"/view"},{default:n(()=>[Ce]),_:2},1032,["href"]),p(e("button",{onClick:r=>l.toggleModal(t,"deactivateUser","Deactivate User"),class:"select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-red-500 hover:text-red-600 tooltipForActions","data-tooltip":"Deactivate User"},Ee,8,Fe),[[w,i.canEdit]])])],2))),128))]),_:1})]),_:1},8,["pageTitle","hasNavigation","data_list"]),a(H,{showModal:m.value,onClose:k,width:"sm",height:"long",title:"Filters"},{"custom-button":n(()=>[e("button",{onClick:N,class:"text-white bg-green-600 hover:bg-green-700 w-20 rounded h-10"},[v.value?(s(),o("span",Re,Pe)):(s(),o("span",ze,"Apply"))])]),default:n(()=>[e("div",null,[e("div",Me,[Se,p(e("select",{"onUpdate:modelValue":f[1]||(f[1]=t=>g(c).type=t),id:"type",class:"rounded border-gray-400"},De,512),[[M,g(c).type]])]),e("div",Ve,[He,p(e("select",{"onUpdate:modelValue":f[2]||(f[2]=t=>g(c).show=t),id:"show",class:"rounded border-gray-400"},Be,512),[[M,g(c).show]])])])]),_:1},8,["showModal"]),a(C,{message:l.$page.props.flash.success,type:"success"},null,8,["message"]),a(C,{message:l.$page.props.flash.failed,type:"failed"},null,8,["message"]),a(C,{message:l.$page.props.flash.error,type:"failed"},null,8,["message"]),a(I,{showModal:l.modal,onClose:l.closeModal,title:l.title,modaltype:l.modaltype,selected:l.selectedUser,width:"lg",height:"short"},null,8,["showModal","onClose","title","modaltype","selected"])],64)}}});export{Je as default};
