(window.webpackJsonp=window.webpackJsonp||[]).push([["app"],{"0lO1":function(t,a,s){},gYqe:function(t,a,s){"use strict";var e=s("0lO1");s.n(e).a},ng4s:function(t,a,s){"use strict";s.r(a);var e=s("oCYn"),r=function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{staticClass:"container-fluid"},[s("loading",{attrs:{active:t.isLoading,"is-full-page":t.fullPage},on:{"update:active":function(a){t.isLoading=a}}}),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-6"},[s("label",{attrs:{for:""}},[t._v("\n        Filtrar por Cliente \n      ")]),t._v(" "),s("br"),t._v(" "),s("v-select",{attrs:{label:"name",filterable:!1,options:t.clientOptions},on:{search:t.onSearchClients,input:t.filtrarCliente},scopedSlots:t._u([{key:"option",fn:function(a){return[s("div",{staticClass:"d-center"},[t._v("\n            "+t._s(a.texto)+"\n            ")])]}},{key:"selected-option",fn:function(a){return[s("div",{staticClass:"selected d-center"},[t._v("\n            "+t._s(a.texto)+"\n          ")])]}}])},[s("template",{slot:"no-options"},[t._v("\n         Buscar Cliente\n        ")])],2)],1),t._v(" "),s("div",{staticClass:"col-md-6"},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-5"},[s("label",{attrs:{for:""}},[t._v("Fecha Desde: ")]),t._v(" "),s("date-picker",{attrs:{id:"fechaDesde",lang:t.lang,format:t.dateFormat,type:t.type},model:{value:t.fechaDesde,callback:function(a){t.fechaDesde=a},expression:"fechaDesde"}})],1),t._v(" "),s("div",{staticClass:"col-md-5",staticStyle:{"margin-left":"10px"}},[s("label",{attrs:{for:""}},[t._v("Fecha Hasta: ")]),t._v(" "),s("date-picker",{attrs:{id:"fechaHasta",lang:t.lang,format:t.dateFormat,type:t.type},model:{value:t.fechaHasta,callback:function(a){t.fechaHasta=a},expression:"fechaHasta"}})],1)])]),t._v(" "),s("div",{staticClass:"col-md-4"}),t._v(" "),s("div",{staticClass:"col-md-4"},[s("br"),t._v(" "),s("button",{staticClass:"btn btn-success btn-block",attrs:{type:"button"},on:{click:t.filtrar}},[t._v("Filtrar")])]),t._v(" "),s("div",{staticClass:"col-md-4"})]),t._v(" "),s("br"),t._v(" "),s("div",{staticClass:"container"},[t._m(0),t._v(" "),s("div",{staticClass:"tab-content",attrs:{id:"myTabContent"}},[s("h2",{staticClass:"center",staticStyle:{"background-color":"#f05536",color:"white"}},[t._v("Panel de control")]),t._v(" "),s("div",{staticClass:"tab-pane fade show active",attrs:{id:"otgestion",role:"tabpanel","aria-labelledby":"otgestion-tab"}},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-6 col-xs-12"},[s("div",{staticClass:"row"},t._l(t.dashboardg,(function(a){return s("div",{key:a.estado,staticClass:"col-xs-12 col-md-6 mb-6"},[s("a",{attrs:{href:"/admin/?entity=OrdenTrabajo&action=list&estadoGestion="+a.idEstadoGestion+t.linkFiltro,target:"_blank"}},[s("div",{staticClass:"card border-left-primary shadow h-100 py-2"},[s("div",{staticClass:"card-body"},[s("div",{staticClass:"row no-gutters align-items-center",style:"color:"+t.coloresEstGest[a.estado]},[s("div",{staticClass:"col mr-2"},[s("div",{staticClass:"text-xs font-weight-bold text-uppercase mb-1"},[t._v(t._s(a.estado))]),t._v(" "),s("div",{staticClass:"h5 mb-0 font-weight-bold ",style:"color:"+t.coloresEstGest[a.estado]},[t._v(t._s(a.estadoTotal))])]),t._v(" "),t._m(1,!0)])])])])])})),0)]),t._v(" "),t._m(2)])]),t._v(" "),s("div",{staticClass:"tab-pane fade",attrs:{id:"otestados",role:"tabpanel","aria-labelledby":"otestados-tab"}},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-6 col-xs-12"},[s("div",{staticClass:"row"},t._l(t.dashboard,(function(a){return s("div",{key:a.estado,staticClass:"col-xs-12 col-md-6 mb-6"},[s("a",{attrs:{href:"/admin/?entity=OrdenTrabajo&action=list&estado="+a.idEstado+t.linkFiltro,target:"_blank"}},[s("div",{staticClass:"card border-left-primary shadow h-100 py-2"},[s("div",{staticClass:"card-body"},[s("div",{staticClass:"row no-gutters align-items-center",style:"color:"+t.coloresEst[a.estado]},[s("div",{staticClass:"col mr-2"},[s("div",{staticClass:"text-xs font-weight-bold text-uppercase mb-1"},[t._v(t._s(a.estado))]),t._v(" "),s("div",{staticClass:"h5 mb-0 font-weight-bold",style:"color:"+t.coloresEst[a.estado]},[t._v(t._s(a.estadoTotal))])]),t._v(" "),t._m(3,!0)])])])])])})),0)]),t._v(" "),t._m(4)])]),t._v(" "),s("div",{staticClass:"tab-pane fade",attrs:{id:"sol",role:"tabpanel","aria-labelledby":"sol-tab"}},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-6 col-xs-12"},[s("div",{staticClass:"row"},t._l(t.dashboardSolEst,(function(a){return s("div",{key:a.estado,staticClass:"col-xs-12 col-md-6 mb-6"},[s("a",{attrs:{href:"/admin/?entity=Solicitud&action=list&estado="+a.idEstado+t.linkFiltro,target:"_blank"}},[s("div",{staticClass:"card border-left-primary shadow h-100 py-2"},[s("div",{staticClass:"card-body"},[s("div",{staticClass:"row no-gutters align-items-center",style:"color:"+t.coloresEst[a.estado]},[s("div",{staticClass:"col mr-2"},[s("div",{staticClass:"text-xs font-weight-bold text-uppercase mb-1"},[t._v(t._s(a.estado))]),t._v(" "),s("div",{staticClass:"h5 mb-0 font-weight-bold ",style:"color:"+t.coloresEst[a.estado]},[t._v(t._s(a.estadoTotal))])]),t._v(" "),t._m(5,!0)])])])])])})),0)]),t._v(" "),t._m(6)]),t._v(" "),s("br"),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-6 col-xs-12"},[s("div",{staticClass:"row"},t._l(t.dashboardSolServ,(function(a){return s("div",{key:a.estado,staticClass:"col-xs-12 col-md-6 mb-6"},[s("a",{attrs:{href:"/admin/?entity=Solicitud&action=list&servicio="+a.idServicio+t.linkFiltro,target:"_blank"}},[s("div",{staticClass:"card border-left-primary shadow h-100 py-2"},[s("div",{staticClass:"card-body"},[s("div",{staticClass:"row no-gutters align-items-center"},[s("div",{staticClass:"col mr-2"},[s("div",{staticClass:"text-xs font-weight-bold  text-uppercase mb-1"},[t._v(t._s(a.estado))]),t._v(" "),s("div",{staticClass:"h5 mb-0 font-weight-bold"},[t._v(t._s(a.estadoTotal))])]),t._v(" "),t._m(7,!0)])])])])])})),0)]),t._v(" "),t._m(8)])])])])],1)};r._withStripped=!0;s("ma9I"),s("QWBl"),s("07d7"),s("5s+n"),s("rB9j"),s("hByQ"),s("FZtP");var i=s("4SJn"),o=s("Snq/"),c=s.n(o),l=(s("bfyA"),s("6nZA")),n=s.n(l),d=s("kGIl"),h=s.n(d),v=(s("5A0h"),{components:{"v-select":c.a,DatePicker:n.a,Loading:h.a},data:function(){return{coloresEstGest:{Abierta:"#dd4b39",Pendiente:"#f39c12",Cerrada:"#00a65a"},coloresEst:{Pendiente:"#dd4b39","Estoy en camino":"#f39c12","Me recibió":"#00a65a","No me atendió":"#ffb100",Finalizado:"#5cbe4a",Postergado:"#2db7f0"},isLoading:!1,fullPage:!0,dashboard:[],dashboardg:[],cantGestionPorEstadosArray:[],cg_json:null,ce_json:null,dashboardSolEst:[],dashboardSolServ:[],cantSolEstArray:null,cantSolServArray:null,chart_cse:null,chart_css:null,chart_ce:null,chart_cg:null,clientOptions:[],idCliente:null,fechaDesde:null,fechaHasta:null,lang:"es",dateFormat:"DD/MM/YYYY",type:"DD-MM-YYYY",resultadosFiltrados:null,linkFiltro:""}},mounted:function(){var t=this,a=document.querySelector("div[data-dashboard]");this.dashboard=JSON.parse(a.dataset.dashboard);var s=document.querySelector("div[data-dashboardg]");this.dashboardg=JSON.parse(s.dataset.dashboardg);var e=document.querySelector("div[data-dashboardSolEst]");this.dashboardSolEst=JSON.parse(e.dataset.dashboardsolest);var r=document.querySelector("div[data-dashboardSolServ]");this.dashboardSolServ=JSON.parse(r.dataset.dashboardsolserv);var o=document.querySelector("div[data-cantEstadosArray]");this.ce_json=JSON.parse(o.dataset.cantestadosarray);var c=this.ce_json[0],l=this.ce_json[1],n=[];this.ce_json[0].forEach((function(a){n.push(t.coloresEst[a])}));var d={chart:{type:"pie"},series:l,labels:c,colors:n};this.chart_ce=new i.a(document.querySelector("#chart_ce"),d),this.chart_ce.render();var h=document.querySelector("div[data-cantGestionArray]");this.cg_json=JSON.parse(h.dataset.cantgestionarray);var v=this.cg_json[0],u=this.cg_json[1],_=[];this.cg_json[0].forEach((function(a){_.push(t.coloresEstGest[a])}));var f={chart:{type:"pie"},series:u,labels:v,colors:_};this.chart_cg=new i.a(document.querySelector("#chart_cg"),f),this.chart_cg.render();var b=document.querySelector("div[data-cantSolEstArray]");this.cantSolEstArray=JSON.parse(b.dataset.cantsolestarray);var p=this.cantSolEstArray[0],m=this.cantSolEstArray[1],y=[];this.cantSolEstArray[0].forEach((function(a){y.push(t.coloresEst[a])}));var C={chart:{type:"pie"},series:m,labels:p,colors:y};this.chart_cse=new i.a(document.querySelector("#chart_cse"),C),this.chart_cse.render();var g=document.querySelector("div[data-cantSolServArray]");this.cantSolServArray=JSON.parse(g.dataset.cantsolservarray);var S=this.cantSolServArray[0],E={chart:{type:"pie"},series:this.cantSolServArray[1],labels:S};this.chart_css=new i.a(document.querySelector("#chart_css"),E),this.chart_css.render()},methods:{onSearchClients:function(t,a){a(!0),t?this.search(a,t,this):a(!1)},search:_.debounce((function(t,a,s){fetch("/admin/buscarClientes/".concat(escape(a))).then((function(a){a.json().then((function(t){return s.clientOptions=t})),t(!1)}))}),350),filtrarCliente:function(t){this.idCliente=t?t.idCliente:null},filtrar:function(){var t=this;this.isLoading=!0;var a=null;this.fechaDesde&&(a=this.fechaDesde.getDate()+"-"+(this.fechaDesde.getMonth()+1)+"-"+this.fechaDesde.getFullYear());var s=null;this.fechaHasta&&(s=this.fechaHasta.getDate()+"-"+(this.fechaHasta.getMonth()+1)+"-"+this.fechaHasta.getFullYear());this.linkFiltro="&idCliente=".concat(escape(this.idCliente),"&fechaDesde=").concat(escape(a),"&fechaHasta=").concat(escape(s)),fetch("/admin/dashboard/".concat(escape(this.idCliente),"/").concat(escape(a),"/").concat(escape(s))).then((function(t){return t.json()})).then((function(a){t.dashboard=a.dashboard,t.dashboardg=a.dashboardg,t.dashboardSolEst=a.dashboardSolEst,t.dashboardSolServ=a.dashboardSolServ,t.ce_json=a.cantEstadosArray,t.cg_json=a.cantGestionArray,t.cantSolEstArray=a.cantSolEstArray,t.cantSolServArray=a.cantSolServArray;var s=t.ce_json[0],e=t.ce_json[1],r=[];t.ce_json[0].forEach((function(a){r.push(t.coloresEst[a])}));var i={chart:{type:"pie"},series:e,labels:s,colors:r};t.chart_ce.updateOptions(i);var o=t.cg_json[0],c=t.cg_json[1],l=[];t.cg_json[0].forEach((function(a){l.push(t.coloresEstGest[a])}));var n={chart:{type:"pie"},series:c,labels:o,colors:l};t.chart_cg.updateOptions(n);var d=t.cantSolEstArray[0],h=t.cantSolEstArray[1],v=[];t.cantSolEstArray[0].forEach((function(a){v.push(t.coloresEst[a])}));var u={chart:{type:"pie"},series:h,labels:d,colors:v};t.chart_cse.updateOptions(u);var _=t.cantSolServArray[0],f={chart:{type:"pie"},series:t.cantSolServArray[1],labels:_};t.chart_css.updateOptions(f),t.isLoading=!1}))}}}),u=(s("gYqe"),s("KHd+")),f=Object(u.a)(v,r,[function(){var t=this.$createElement,a=this._self._c||t;return a("ul",{staticClass:"nav nav-tabs",attrs:{id:"myTab",role:"tablist"}},[a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link active",attrs:{id:"otgestion-tab","data-toggle":"tab",href:"#otgestion",role:"tab","aria-controls":"home","aria-selected":"true"}},[this._v("OT por Gestión")])]),this._v(" "),a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link",attrs:{id:"otestados-tab","data-toggle":"tab",href:"#otestados",role:"tab","aria-controls":"home","aria-selected":"true"}},[this._v("OT por Estados")])]),this._v(" "),a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link",attrs:{id:"profile-tab","data-toggle":"tab",href:"#sol",role:"tab","aria-controls":"profile","aria-selected":"false"}},[this._v("Solicitudes")])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-auto"},[a("i",{staticClass:"fas fa-briefcase fa-2x "})])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-md-6 col-xs-12"},[a("div",{staticClass:"card"},[a("div",{staticClass:"card border-left-warning shadow h-100 py-2"},[a("div",{attrs:{id:"chart_cg"}})])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-auto"},[a("i",{staticClass:"fas fa-briefcase fa-2x text-gray-300"})])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-md-6 col-xs-12"},[a("div",{staticClass:"card border-left-warning shadow h-100 py-2"},[a("div",{staticClass:"card-body"},[a("div",{attrs:{id:"chart_ce"}})])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-auto"},[a("i",{staticClass:"fas fa-list fa-2x text-gray-300"})])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-md-6 col-xs-12"},[a("div",{staticClass:"card border-left-warning shadow h-100 py-2"},[a("div",{staticClass:"card-body"},[a("div",{attrs:{id:"chart_cse"}})])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-auto"},[a("i",{staticClass:"fas fa-list fa-2x text-gray-300"})])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"col-md-6 col-xs-12"},[a("div",{staticClass:"card"},[a("div",{staticClass:"card border-left-warning shadow h-100 py-2"},[a("div",{attrs:{id:"chart_css"}})])])])}],!1,null,null,null);f.options.__file="assets/js/components/App.vue";var b=f.exports;s("PENG");new e.a({el:"#app",render:function(t){return t(b)}})}},[["ng4s","runtime",0]]]);