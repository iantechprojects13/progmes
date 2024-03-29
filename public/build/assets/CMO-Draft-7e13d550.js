import{T as y,m as k,s as L,A as d,o as a,f as l,a as o,w as n,g as B,F as O,b as e,i as u,B as P,u as _,z as T,p as m,e as H,d as I,C as R,n as U,t as p,D as j,O as S}from"./app-612f8f79.js";import"./index-decdce8d.js";import z from"./Layout-688c6e41.js";const K=e("button",{class:"select-none text-gray-500 h-10 mr-7 hover:text-black"}," CMO ",-1),q=e("button",{class:"select-none text-blue-500 font-bold border-b-2 h-10 border-blue-500"}," Draft ",-1),G={class:"w-full flex justify-end relative"},J=e("i",{class:"fas fa-search"},null,-1),Q=[J],W=["disabled"],X={key:0},Y=e("i",{class:"fas fa-spinner animate-spin"},null,-1),Z=[Y],ee={key:1},te=e("i",{class:"fas fa-file-import mr-2 text-base"},null,-1),se={class:"mr-1"},oe=e("button",{class:"w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Refresh page"},[e("i",{class:"fas fa-refresh"})],-1),ne=e("th",{class:"p-3 pl-5"},"CMO",-1),ae=e("th",{class:"p-3"},"Program",-1),le={class:"p-3"},ie={class:"p-3 text-right"},re=e("i",{class:"fas fa-ellipsis-v"},null,-1),de=[re],ce={key:0},he=e("td",{class:"text-center py-10",colspan:"4"}," No draft found ",-1),ue=[he],pe={class:"p-3 pl-5 whitespace-normal min-w-12"},fe={key:0},_e={key:1},me={class:"p-3 whitespace-normal min-w-12"},ge={class:"p-3 text-right"},be=["onClick"],ve=e("i",{class:"fas fa-paper-plane"},null,-1),Ce=[ve],we=["onClick"],Me=e("i",{class:"fas fa-edit"},null,-1),ye=[Me],ke=["onClick"],Oe=e("i",{class:"fas fa-trash"},null,-1),Se=[Oe],$e=e("div",{class:"mr-2"},"Items per page",-1),Ae=e("option",{value:"25"},"25",-1),De=e("option",{value:50},"50",-1),Ne=e("option",{value:75},"75",-1),xe=e("option",{value:100},"100",-1),Fe=e("option",{value:200},"200",-1),Ve=[Ae,De,Ne,xe,Fe],Ee={key:0},Le={data(){return{openCreateDropdown:!1,openListOption:!1,uploadModal:!1,confirmationModal:!1,selectedCMO:"",modaltype:"",title:""}},methods:{openUploadModal(){this.uploadModal=!this.uploadModal},toggleConfirmationModal(i,f,c){this.confirmationModal=!this.confirmationModal,this.selectedCMO=i,this.modaltype=f,this.title=c},closeModal(){this.confirmationModal=!1,this.uploadModal=!1}},layout:z},He=Object.assign(Le,{__name:"CMO-Draft",props:["cmo_list","canEdit","filters"],setup(i){y({cmo_file:null});const f=k(null),c=k(!1);function $(t){S.get("/admin/CMOs/draft/"+t+"/edit")}const b=i,h=y({show:b.filters.show,search:b.filters.search});function v(){h.get("/admin/CMOs/draft",{preserveScroll:!0})}function A(){h.get("/admin/CMOs/draft",{preserveScroll:!1,preserveState:!1})}return L(f,t=>{S.post("/admin/CMOs/create/import",{file:t},{onStart:()=>{c.value=!0},onFinish:()=>{c.value=!1},preserveScroll:!0,preserveState:!1})}),(t,r)=>{const D=d("Head"),N=d("AdminPanel"),C=d("Link"),x=d("content-table"),F=d("content-container"),V=d("Confirmation"),g=d("Notification");return a(),l(O,null,[o(D,{title:"CHED Memorandum Order List"}),o(N),o(F,{pageTitle:"CMO Draft List",hasTopButton:"true",hasSearch:"true",hasFilters:"true",hasNavigation:"true",data_list:i.cmo_list},{navigation:n(()=>[e("div",null,[o(C,{href:t.route("admin.cmo.list")},{default:n(()=>[K]),_:1},8,["href"]),q])]),search:n(()=>[e("div",G,[u(e("input",{onKeydown:T(v,["enter"]),"onUpdate:modelValue":r[0]||(r[0]=s=>_(h).search=s),type:"search",id:"content-search",placeholder:"Search a CMO",class:"w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2"},null,544),[[P,_(h).search]]),e("button",{onClick:v,class:"hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3"},Q)])]),"top-button":n(()=>[u(e("div",null,[e("input",{ref:"uploadfile",hidden:"",type:"file",onChange:r[1]||(r[1]=s=>f.value=s.target.files[0])},null,544),e("button",{onClick:r[2]||(r[2]=H(s=>t.$refs.uploadfile.click(),["prevent"])),disabled:c.value,class:"select-none bg-blue-500 hover:bg-blue-600 h-10 w-32 rounded text-white text-sm whitespace-nowrap"},[c.value?(a(),l("span",X,Z)):(a(),l("span",ee,[te,I("Import CMO")]))],8,W)],512),[[m,i.canEdit]])]),options:n(()=>[e("div",se,[o(C,{href:"/admin/CMOs/draft"},{default:n(()=>[oe]),_:1})])]),"main-content":n(()=>[o(x,null,{"table-head":n(()=>[ne,ae,u(e("th",le," Imported by",512),[[m,t.$page.props.auth.user.role=="Super Admin"||t.$page.props.auth.user.role=="Admin"]]),u(e("th",ie,de,512),[[m,i.canEdit]])]),"table-body":n(()=>[i.cmo_list.data.length==0?(a(),l("tr",ce,ue)):(a(!0),l(O,{key:1},R(i.cmo_list.data,(s,E)=>{var w;return a(),l("tr",{key:s.id,class:U(["hover:bg-slate-300",{"bg-slate-200":E%2==0}])},[e("td",pe,[s.number!=null&&s.series!=null&&s.version!=null?(a(),l("div",fe," CMO No."+p(s.number)+" Series of "+p(s.series)+", Version "+p(s.version),1)):(a(),l("div",_e," - "))]),e("td",me,p((w=s.program)==null?void 0:w.program),1),u(e("td",{class:"p-3"},p(s.created_by.name),513),[[m,t.$page.props.auth.user.role=="Super Admin"||t.$page.props.auth.user.role=="Admin"]]),e("td",ge,[e("button",{onClick:M=>t.toggleConfirmationModal(s.id,"publish","Publish CMO"),class:"select-none h-10 w-10 mr-1 rounded bg-green-600 hover:bg-green-700 text-white tooltipForActions","data-tooltip":"Publish"},Ce,8,be),e("button",{onClick:M=>$(s.id),class:"select-none h-10 w-10 mr-1 rounded bg-blue-500 hover:bg-blue-600 text-white tooltipForActions","data-tooltip":"Edit"},ye,8,we),e("button",{onClick:M=>t.toggleConfirmationModal(s.id,"deleteCMO","Delete Draft"),class:"select-none h-10 w-10 rounded bg-red-500 hover:bg-red-600 text-white tooltipForActions","data-tooltip":"Delete"},Se,8,ke)])],2)}),128))]),_:1})]),"show-item":n(()=>[$e,u(e("select",{"onUpdate:modelValue":r[3]||(r[3]=s=>_(h).show=s),id:"showResultCount",onChange:A,class:"select-none rounded h-8 w-20 p-1 text-sm"},Ve,544),[[j,_(h).show]])]),_:1},8,["data_list"]),t.confirmationModal?(a(),l("div",Ee,[o(V,{onClose:t.closeModal,title:t.title,modaltype:t.modaltype,selected:t.selectedCMO},null,8,["onClose","title","modaltype","selected"])])):B("",!0),o(g,{message:t.$page.props.flash.success,type:"success"},null,8,["message"]),o(g,{message:t.$page.props.flash.failed,type:"failed"},null,8,["message"]),o(g,{message:t.$page.props.errors.file,type:"failed"},null,8,["message"])],64)}}});export{He as default};
