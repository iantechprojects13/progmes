import{E as u,A as d,o,f as n,a as m,b as e,i as _,D as p,F as l,C as g,O as v,t as f}from"./app-a1d3aa91.js";const h={class:"flex items-center justify-center h-screen"},b={class:"max-w-md mx-auto"},w=e("div",{class:"mb-3 font-bold"},"Test User",-1),x=e("option",{value:null},"Select user",-1),y=["value"],k={__name:"TestLogin",props:["users"],setup(r){const t=u({user:null});function i(){v.post("/testuserlogin",t)}return(D,a)=>{const c=d("Head");return o(),n(l,null,[m(c,{title:"Test User Login"}),e("div",h,[e("div",b,[w,_(e("select",{"onUpdate:modelValue":a[0]||(a[0]=s=>t.user=s),id:"usersDropDown",class:"w-full"},[x,(o(!0),n(l,null,g(r.users,s=>(o(),n("option",{key:s.id,value:s.id},[e("span",null,f(s.name),1)],8,y))),128))],512),[[p,t.user]]),e("button",{onClick:i,class:"w-full h-10 bg-gray-700 hover:bg-gray-900 text-white mt-3"}," LOGIN ")])])],64)}}};export{k as default};
