import{m as w,T as B,A as i,o as r,f as c,a,w as o,F as y,b as t,i as f,B as T,u as p,z as V,C as $,n as L,t as O,p as x,D as z,d as K,O as C}from"./app-a1d3aa91.js";import U from"./Layout-8eb335a9.js";const j=t("button",{class:"bg-blue-500 hover:bg-blue-600 h-10 px-3 rounded text-white whitespace-nowrap"},[t("i",{class:"fas fa-plus mr-2"}),K("Add HEI ")],-1),q={class:"w-full flex flex-row relative items-center"},P=t("i",{class:"fa fa-search text-gray-400 absolute left-5"},null,-1),R={class:"mr-1"},G=t("i",{class:"fas fa-filter"},null,-1),J=[G],Q=t("button",{class:"w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Refresh page"},[t("i",{class:"fas fa-refresh"})],-1),W=t("th",{class:"p-2 pl-5"},"HEI Name",-1),X=t("th",{class:"p-2 pr-5 text-right"},[t("i",{class:"fas fa-ellipsis-v"})],-1),Y={key:0},Z=t("td",{class:"text-center py-10",colspan:"3"}," No HEI found ",-1),tt=[Z],et={class:"p-2 pl-5"},ot={class:"p-2 pr-5 text-right whitespace-nowrap"},st=["onClick"],nt=t("i",{class:"fas fa-eye"},null,-1),at=[nt],it=["onClick"],lt=t("i",{class:"fas fa-edit"},null,-1),rt=[lt],ct=["onClick"],dt=t("i",{class:"fas fa-trash"},null,-1),ht=[dt],ut={class:"flex flex-col"},_t=t("label",{for:"show"},"Items per page",-1),ft=t("option",{value:"25"},"25",-1),pt=t("option",{value:"50"},"50",-1),gt=t("option",{value:"100"},"100",-1),mt=t("option",{value:"200"},"200",-1),vt=[ft,pt,gt,mt],bt={key:0},wt=t("i",{class:"fas fa-spinner animate-spin"},null,-1),yt=[wt],xt={key:1},Ct={data(){return{confirmationModal:!1,selectedHEI:"",modaltype:"",title:""}},layout:U,methods:{toggleConfirmationModal(s,d,h){this.confirmationModal=!this.confirmationModal,this.selectedHEI=s,this.modaltype=d,this.title=h},closeModal(){this.confirmationModal=!1}}},Et=Object.assign(Ct,{__name:"HEI",props:["institution_list","canEdit","canAdd","canDelete","filters"],setup(s){const d=s,h=w(!1),u=w(!1),l=B({show:d.filters.show!=null?d.filters.show:null,search:d.filters.search});function g(){h.value=!h.value}function k(){l.get("/admin/higher-education-institutions",{onStart:()=>{u.value=!0},onFinish:()=>{u.value=!1},preserveState:!1,preserveScroll:!0})}function M(){l.get("/admin/higher-education-institutions",{onStart:()=>{u.value=!0},onFinish:()=>{u.value=!1,g()},preserveState:!0,preserveScroll:!0})}function E(e){C.get("/admin/higher-education-institutions/"+e+"/edit")}function F(e){C.get("/admin/higher-education-institutions/"+e+"/view")}return(e,_)=>{const H=i("Head"),m=i("Link"),I=i("content-table"),A=i("content-container"),S=i("modal"),D=i("Confirmation"),v=i("Notification");return r(),c(y,null,[a(H,{title:"Higher Education Institution List"}),a(A,{hasAdminPanel:!0,pageTitle:"Higher Education Institution List",page:"hei",hasTopButton:s.canAdd,hasSearch:!0,hasFilters:!0,data_list:s.institution_list},{"top-button":o(()=>[a(m,{href:"/admin/higher-education-institutions/create"},{default:o(()=>[j]),_:1})]),search:o(()=>[t("div",q,[P,f(t("input",{onKeydown:V(k,["enter"]),"onUpdate:modelValue":_[0]||(_[0]=n=>p(l).search=n),type:"search",id:"content-search",placeholder:"Search",class:"w-full rounded-lg border border-gray-300 indent-10 h-10 text-base placeholder-gray-400"},null,544),[[T,p(l).search]])])]),options:o(()=>[t("div",R,[t("button",{onClick:g,class:"w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions","data-tooltip":"Filters"},J),a(m,{href:"/admin/higher-education-institutions"},{default:o(()=>[Q]),_:1})])]),"main-content":o(()=>[a(I,null,{"table-head":o(()=>[W,X]),"table-body":o(()=>[s.institution_list.data.length==0?(r(),c("tr",Y,tt)):(r(!0),c(y,{key:1},$(s.institution_list.data,(n,N)=>(r(),c("tr",{key:n.id,class:L(["hover:bg-gray-200 border-b",{"bg-gray-100":N%2==1}])},[t("td",et,O(n.name),1),t("td",ot,[t("button",{onClick:b=>F(n.id),class:"select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions","data-tooltip":"View"},at,8,st),f(t("button",{onClick:b=>E(n.id),class:"select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-blue-500 hover:text-blue-600 tooltipForActions","data-tooltip":"Edit"},rt,8,it),[[x,s.canEdit]]),f(t("button",{onClick:b=>e.toggleConfirmationModal(n,"deleteHEI","Delete HEI"),class:"select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-red-500 hover:text-red-600 tooltipForActions","data-tooltip":"Delete"},ht,8,ct),[[x,s.canDelete]])])],2))),128))]),_:1})]),_:1},8,["hasTopButton","data_list"]),a(S,{showModal:h.value,onClose:g,width:"sm",height:"long",title:"Filters"},{"custom-button":o(()=>[t("button",{onClick:M,class:"text-white bg-green-600 hover:bg-green-700 w-20 rounded h-10"},[u.value?(r(),c("span",bt,yt)):(r(),c("span",xt,"Apply"))])]),default:o(()=>[t("div",null,[t("div",ut,[_t,f(t("select",{"onUpdate:modelValue":_[1]||(_[1]=n=>p(l).show=n),id:"show",class:"rounded border-gray-400"},vt,512),[[z,p(l).show]])])])]),_:1},8,["showModal"]),a(D,{showModal:e.confirmationModal,onClose:e.closeModal,title:e.title,modaltype:e.modaltype,selected:e.selectedHEI,width:"md",height:"short"},null,8,["showModal","onClose","title","modaltype","selected"]),a(v,{message:e.$page.props.flash.success,type:"success"},null,8,["message"]),a(v,{message:e.$page.props.flash.failed,type:"failed"},null,8,["message"])],64)}}});export{Et as default};
