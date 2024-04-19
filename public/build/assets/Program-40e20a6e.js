import{m as _,T as B,A as i,o as r,f as c,a as n,w as o,g as F,F as y,b as e,i as d,B as L,u as p,z as T,p as f,C as V,n as E,t as g,D as j,d as $,O}from"./app-ee162bce.js";import R from"./Layout-a469915b.js";const z=e("button",{class:"select-none text-blue-500 h-10 mr-8 border-b-2 font-bold border-blue-500"}," Program ",-1),H=e("button",{class:"select-none text-gray-500 hover:text-black"}," Discipline ",-1),K={class:"w-full flex justify-end relative"},U=e("i",{class:"fas fa-search"},null,-1),q=[U],I=e("button",{class:"select-none bg-blue-500 hover:bg-blue-600 h-10 px-3 whitespace-nowrap rounded text-white text-sm"},[e("i",{class:"fas fa-plus mr-2"}),$("Add program")],-1),G=e("button",{class:"w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Refresh page"},[e("i",{class:"fas fa-refresh"})],-1),J=e("th",{class:"p-3 pl-5"},"Program",-1),Q=e("th",{class:"p-3"},"Major",-1),W=e("th",{class:"p-3"},"Discipline",-1),X={class:"p-3 text-right"},Y=e("i",{class:"fas fa-ellipsis-v"},null,-1),Z=[Y],ee={key:0},te=e("td",{class:"text-center py-10",colspan:"4"}," No program found ",-1),oe=[te],se={class:"p-3 pl-5 whitespace-normal"},ae={class:"p-3 whitespace-normal"},ne={class:"p-3 whitespace-normal"},le={class:"p-3 text-right"},ie=["onClick"],re=e("i",{class:"fas fa-edit mr-0.5"},null,-1),ce=[re],de=["onClick"],he=e("i",{class:"fas fa-trash mr-0.5"},null,-1),ue=[he],pe=e("div",{class:"mr-2"},"Items per page",-1),me=e("option",{value:"25"},"25",-1),_e=e("option",{value:50},"50",-1),fe=e("option",{value:75},"75",-1),ge=e("option",{value:100},"100",-1),be=e("option",{value:200},"200",-1),ve=[me,_e,fe,ge,be],we={key:0},ye={layout:R,data(){return{confirmationModal:!1,selectedProgram:"",modaltype:"",title:""}},methods:{toggleConfirmationModal(s,h,l){this.confirmationModal=!this.confirmationModal,this.selectedProgram=s,this.modaltype=h,this.title=l},closeModal(){this.confirmationModal=!1,this.uploadModal=!1}}},xe=Object.assign(ye,{__name:"Program",props:["program_list","canEdit","canAdd","canDelete","filters"],setup(s){const h=s;_(null),_(!1),_(null);const l=B({show:h.filters.show,search:h.filters.search});function b(){l.get("/admin/program",{preserveState:!0,preserveScroll:!0})}function C(){l.get("/admin/program",{preserveScroll:!1,preserveState:!1})}function k(t){O.get("/admin/program/"+t+"/edit")}return(t,u)=>{const x=i("Head"),M=i("AdminPanel"),m=i("Link"),P=i("content-table"),A=i("content-container"),D=i("Confirmation"),v=i("Notification");return r(),c(y,null,[n(x,{title:"Program List"}),n(M),n(A,{pageTitle:"Program List",hasTopButton:s.canAdd,hasNavigation:"true",hasSearch:"true",hasFilters:"true",data_list:s.program_list},{navigation:o(()=>[e("div",null,[e("div",null,[z,n(m,{href:t.route("admin.discipline.list")},{default:o(()=>[H]),_:1},8,["href"])])])]),search:o(()=>[e("div",K,[d(e("input",{onKeydown:T(b,["enter"]),"onUpdate:modelValue":u[0]||(u[0]=a=>p(l).search=a),type:"search",id:"content-search",placeholder:"Search",class:"w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2"},null,544),[[L,p(l).search]]),e("button",{onClick:b,class:"hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3"},q)])]),"top-button":o(()=>[n(m,{href:"/admin/program/create"},{default:o(()=>[I]),_:1})]),options:o(()=>[e("div",null,[n(m,{href:"/admin/program"},{default:o(()=>[G]),_:1})])]),"main-content":o(()=>[n(P,null,{"table-head":o(()=>[J,Q,W,d(e("th",X,Z,512),[[f,s.canEdit]])]),"table-body":o(()=>[s.program_list.data.length==0?(r(),c("tr",ee,oe)):(r(!0),c(y,{key:1},V(s.program_list.data,(a,S)=>{var w;return r(),c("tr",{key:a.id,class:E(["hover:bg-slate-300",{"bg-slate-200":S%2==0}])},[e("td",se,g(a.program),1),e("td",ae,g(a.major),1),e("td",ne,g((w=a.discipline)==null?void 0:w.discipline),1),e("td",le,[d(e("button",{onClick:N=>k(a.id),class:"mr-1 select-none h-10 w-10 rounded text-white bg-blue-500 hover:bg-blue-600 tooltipForActions","data-tooltip":"Edit"},ce,8,ie),[[f,s.canEdit]]),d(e("button",{onClick:N=>t.toggleConfirmationModal(a,"deleteProgram","Delete Program"),class:"select-none h-10 w-10 rounded text-white bg-red-500 hover:bg-red-600 tooltipForActions","data-tooltip":"Delete"},ue,8,de),[[f,s.canDelete]])])],2)}),128))]),_:1})]),"show-item":o(()=>[pe,d(e("select",{"onUpdate:modelValue":u[1]||(u[1]=a=>p(l).show=a),id:"showResultCount",onChange:C,class:"select-none rounded h-8 w-20 p-1 text-sm"},ve,544),[[j,p(l).show]])]),_:1},8,["hasTopButton","data_list"]),t.confirmationModal?(r(),c("div",we,[n(D,{onClose:t.closeModal,title:t.title,modaltype:t.modaltype,selected:t.selectedProgram},null,8,["onClose","title","modaltype","selected"])])):F("",!0),n(v,{message:t.$page.props.flash.success,type:"success"},null,8,["message"]),n(v,{message:t.$page.props.flash.failed,type:"failed"},null,8,["message"])],64)}}});export{xe as default};
