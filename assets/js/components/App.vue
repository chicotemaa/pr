<template>
  <div class="container-fluid">
    <loading :active.sync="isLoading" 
        :is-full-page="fullPage"></loading>
    <div class="row">
      <div class="col-md-6">
        <label for="">
          Filtrar por Cliente 
        </label>
        <br>
        <v-select label="name" :filterable="false" :options="clientOptions" @search="onSearchClients" v-on:input="filtrarCliente">
          <template slot="no-options">
           Buscar Cliente
          </template>
          <template slot="option" slot-scope="option">
            <div class="d-center">
              {{ option.texto }}
              </div>
          </template>
          <template slot="selected-option" slot-scope="option">
            <div class="selected d-center">
              {{ option.texto }}
            </div>
          </template>
        </v-select>
      </div>
      <div class="col-md-6">  
        <div class="row"> 
          <div class="col-md-5">
            <label for="">Fecha Desde: </label> 
            <date-picker id="fechaDesde" v-model="fechaDesde" :lang="lang" :format="dateFormat" :type="type"></date-picker>
          </div>
          
          <div class="col-md-5" style="margin-left:10px;">
            <label for="">Fecha Hasta: </label>
            <date-picker id="fechaHasta" v-model="fechaHasta" :lang="lang" :format="dateFormat" :type="type"></date-picker>
          </div>
        </div>
      </div>
      <div class="col-md-4"> 
      </div>
      <div class="col-md-4"> 
        <br> 
        <button type="button" class="btn btn-success btn-block" v-on:click='filtrar'>Filtrar</button>
      </div>
      <div class="col-md-4"> 
      </div>
    </div>
    <br>
    <div class="container">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="otgestion-tab" data-toggle="tab" href="#otgestion" role="tab" aria-controls="home" aria-selected="true">OT por Gestión</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="otestados-tab" data-toggle="tab" href="#otestados" role="tab" aria-controls="home" aria-selected="true">OT por Estados</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sol" role="tab" aria-controls="profile" aria-selected="false">Solicitudes</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <h2 class="center" style="background-color:#f05536;color:white;">Panel de control</h2>
        <div class="tab-pane fade show active" id="otgestion" role="tabpanel" aria-labelledby="otgestion-tab">
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <div class="row">
                  <div :key="d.estado" v-for="d in dashboardg" class="col-xs-12 col-md-6 mb-6">
                    <a :href="'/admin/?entity=OrdenTrabajo&action=list&estadoGestion='+d.idEstadoGestion+linkFiltro" target="_blank">
                      <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center" :style="'color:'+coloresEstGest[d.estado]">  
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-uppercase mb-1" >{{ d.estado }}</div>
                              <div class="h5 mb-0 font-weight-bold " :style="'color:'+coloresEstGest[d.estado]">{{ d.estadoTotal }}</div>
                            </div>
                            <div class="col-auto">
                              <i class="fas fa-briefcase fa-2x "></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="card">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div id="chart_cg">

                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="otestados" role="tabpanel" aria-labelledby="otestados-tab">
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <div class="row">
                <div :key="d.estado" v-for="d in dashboard" class="col-xs-12 col-md-6 mb-6">
                  <a :href="'/admin/?entity=OrdenTrabajo&action=list&estado='+d.idEstado+linkFiltro" target="_blank">
                    <div class="card border-left-primary shadow h-100 py-2">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center" :style="'color:'+coloresEst[d.estado]">
                          <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">{{ d.estado }}</div>
                            <div class="h5 mb-0 font-weight-bold" :style="'color:'+coloresEst[d.estado]">{{ d.estadoTotal }}</div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div id="chart_ce">

                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="sol" role="tabpanel" aria-labelledby="sol-tab">
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <div class="row">
                <div :key="d.estado" v-for="d in dashboardSolEst" class="col-xs-12 col-md-6 mb-6">
                  <a :href="'/admin/?entity=Solicitud&action=list&estado='+d.idEstado+linkFiltro" target="_blank">
                    <div class="card border-left-primary shadow h-100 py-2">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center" :style="'color:'+coloresEst[d.estado]">
                          <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">{{ d.estado }}</div>
                            <div class="h5 mb-0 font-weight-bold " :style="'color:'+coloresEst[d.estado]">{{ d.estadoTotal }}</div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div id="chart_cse">

                    </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <div class="row">
                <div :key="d.estado" v-for="d in dashboardSolServ" class="col-xs-12 col-md-6 mb-6">

                  <a :href="'/admin/?entity=Solicitud&action=list&servicio='+d.idServicio+linkFiltro" target="_blank">
                    <div class="card border-left-primary shadow h-100 py-2">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1">{{ d.estado }}</div>
                            <div class="h5 mb-0 font-weight-bold">{{ d.estadoTotal }}</div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="card">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div id="chart_css">

                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
  
</template>

<script>
// para los graficos
import ApexCharts from 'apexcharts';
// para el select por cliente
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

// para la fecha desde y hasta
import DatePicker from 'vue2-datepicker'
// para el spinner
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  components: {
    "v-select": vSelect,
    DatePicker ,
    Loading
  },
  data() {
    return {
      // colores para los estados de gestio de una ot
      coloresEstGest:{
        'Abierta':'#dd4b39',
        'Pendiente':'#f39c12',
        'Cerrada':'#00a65a'
      },
      // los colores de estados se aplican los dos para ot y solicitudes
       coloresEst:{
          'Pendiente': '#dd4b39',
          'Estoy en camino': '#f39c12',
          'Me recibió': '#00a65a',
          'No me atendió': '#ffb100',
          'Pendiente de revision': '#F44600',
          'Finalizado': '#5cbe4a',
          'Postergado': '#2db7f0'
       },
      // spinner de carga
      isLoading: false,
      fullPage: true,
      // variables para los graficos del dashboard
      dashboard: [],
      dashboardg: [],
      cantGestionPorEstadosArray: [],
      cg_json: null,
      ce_json: null,
      dashboardSolEst:[],
      dashboardSolServ:[],
      cantSolEstArray:null,
      cantSolServArray:null,
      chart_cse:null,
      chart_css:null,
      chart_ce:null,
      chart_cg:null,

      // clienteSeleccionado: null,
      // para el select de clientes
      clientOptions: [],
      idCliente: null,
      // para los filtros de fecha desde y hasta
      fechaDesde: null,
      fechaHasta: null,
      lang: 'es',
      dateFormat: 'DD/MM/YYYY',
      type: 'DD-MM-YYYY',
      resultadosFiltrados: null,

      // link de los filtros
      linkFiltro:''
    };
  },
  mounted() {
    // totalizadores por ot de estados
    let el = document.querySelector("div[data-dashboard]");
    this.dashboard = JSON.parse(el.dataset.dashboard);

    //totalizados por ot de estados de gestion
    let el1 = document.querySelector("div[data-dashboardg]");
    this.dashboardg = JSON.parse(el1.dataset.dashboardg);

    // totalizadores por solicitudes por estado
    let el2 = document.querySelector("div[data-dashboardSolEst]");
    this.dashboardSolEst = JSON.parse(el2.dataset.dashboardsolest);

    // totalizadores por solicitudes por servicios
    let el3 = document.querySelector("div[data-dashboardSolServ]");
    this.dashboardSolServ = JSON.parse(el3.dataset.dashboardsolserv);

    // let el1= document.querySelector("div[data-cantGestionPorEstadosArray]");
    // this.cantGestionPorEstadosArray= JSON.parse(el1.dataset.cantGestionPorEstadosArray);

    // let el2=document.querySelector("div[data-cantGestionArray]");
    // this.cantGestionArray =JSON.parse(el2.dataset.cantgestionarray);
    // console.log(this.cantGestionArray)

    // let el3=document.querySelector("div[data-cantEstadosArray]");
    // console.log(el3.dataset)
    // this.cantEstadosArray = JSON.parse(el3.dataset.cantestadosArray);

    // console.log(this.cantGestionPorEstadosArray);
    // console.log(this.cantGestionArray);
    // console.log(this.cantEstadosArray);

    // para el pie chart de ot por estados
    var ce= document.querySelector("div[data-cantEstadosArray]");

    this.ce_json=JSON.parse(ce.dataset.cantestadosarray);

    var labels_ce = this.ce_json[0]
    var cantidad_ce = this.ce_json[1]

    // para setear los colores 
    let colores_ot_est=[]
    this.ce_json[0].forEach(lab => {
      colores_ot_est.push(this.coloresEst[lab])
    });
    var options_ce = {
      chart: {
        type: 'pie'
      },
      series: cantidad_ce,
      labels: labels_ce,
      colors: colores_ot_est
    }

    this.chart_ce = new ApexCharts(document.querySelector("#chart_ce"), options_ce);

    this.chart_ce.render();

    // para el pie chart de ot por estados de gestion
    var cg= document.querySelector("div[data-cantGestionArray]");
    this.cg_json=JSON.parse(cg.dataset.cantgestionarray);

    var labels_cg = this.cg_json[0]
    var cantidad_cg = this.cg_json[1]
    // para setear los colores 
    let colores_ot_est_g=[]
    this.cg_json[0].forEach(lab => {
      colores_ot_est_g.push(this.coloresEstGest[lab])
    });
    var options_cg = {
      chart: {
        type: 'pie'
      },
      series: cantidad_cg,
      labels: labels_cg,
      colors: colores_ot_est_g

    }

    this.chart_cg = new ApexCharts(document.querySelector("#chart_cg"), options_cg);

    this.chart_cg.render();

    // para las solicitudes por estado
    var cse= document.querySelector("div[data-cantSolEstArray]");

    this.cantSolEstArray=JSON.parse(cse.dataset.cantsolestarray);

    var labels_cse = this.cantSolEstArray[0]
    var cantidad_cse = this.cantSolEstArray[1]

    let colores_ser_est=[]
    this.cantSolEstArray[0].forEach(lab => {
      colores_ser_est.push(this.coloresEst[lab])
    });
    // console.log(this.cantSolEstArray)
    // console.log(this.cantSolEstArray[0])
    // console.log(this.cantSolEstArray[1])
    var options_cse = {
      chart: {
        type: 'pie'
      },
      series: cantidad_cse,
      labels: labels_cse,
      colors: colores_ser_est
    }

    this.chart_cse = new ApexCharts(document.querySelector("#chart_cse"), options_cse);

    this.chart_cse.render();

    // para las solicitudes por servicio
    var css= document.querySelector("div[data-cantSolServArray]");
    // console.log(css.dataset)
    this.cantSolServArray=JSON.parse(css.dataset.cantsolservarray);
    
    var labels_css = this.cantSolServArray[0]
    var cantidad_css = this.cantSolServArray[1]
    var options_css = {
      chart: {
        type: 'pie'
      },
      series: cantidad_css,
      labels: labels_css
    }

    this.chart_css = new ApexCharts(document.querySelector("#chart_css"), options_css);

    this.chart_css.render();
    
    
    // this.$emit("input", val);
    },

  methods: {
    // metodo del select2 para obtener los clientes cuando tipeo por ajax
    onSearchClients(search, loading) {
      // activo el spinner
      loading(true);
      // ahora ejecuto el metodo para buscar por la url
      if(search){
         this.search(loading, search, this);
      }else{
        loading(false);
      }
     
    },
    search: _.debounce((loading, search, vm) => {
      fetch(
        `/admin/buscarClientes/${escape(search)}`
      ).then(res => {
        res.json().then(json => (vm.clientOptions = json));
        loading(false);
      });
    }, 350),

    // aca filtro por el cliente
     filtrarCliente: function (val) {
       
        // use event here as well as id
        // console.log('In addToCart')
        if (val) {
          this.idCliente=val.idCliente
        }else{
          this.idCliente = null
        }
      },


      filtrar: function(){
        this.isLoading = true;
        // if(this.fechaDesde || this.fechaHasta  || this.idCliente){

          var fechaDesde = null
          if (this.fechaDesde) {
            var d = this.fechaDesde.getDate();
            var m = this.fechaDesde.getMonth() + 1; //Month from 0 to 11
            var y = this.fechaDesde.getFullYear();
            fechaDesde=d+'-'+m+'-'+y
          }

          var fechaHasta = null
          if (this.fechaHasta) {
            var d2 = this.fechaHasta.getDate();
            var m2 = this.fechaHasta.getMonth() + 1; //Month from 0 to 11
            var y2 = this.fechaHasta.getFullYear();
            fechaHasta=d2+'-'+m2+'-'+y2
          }

          //concateno en el link del filtro los parametros para que en cada boton pueda llevar al list filtrado
          this.linkFiltro =`&idCliente=${escape(this.idCliente)}&fechaDesde=${escape(fechaDesde)}&fechaHasta=${escape(fechaHasta)}`

          fetch(
            `/admin/dashboard/${escape(this.idCliente)}/${escape(fechaDesde)}/${escape(fechaHasta)}`
          ) .then(res => res.json())
            .then( data => {
           
              // this.resultadosFiltrados= JSON.parse(res);
              this.dashboard = data.dashboard
              this.dashboardg = data.dashboardg
              this.dashboardSolEst = data.dashboardSolEst
              this.dashboardSolServ = data.dashboardSolServ
              this.ce_json=data.cantEstadosArray
              this.cg_json=data.cantGestionArray
              this.cantSolEstArray=data.cantSolEstArray
              this.cantSolServArray=data.cantSolServArray



              // var ce= document.querySelector("div[data-cantEstadosArray]");

              // this.ce_json=JSON.parse(ce.dataset.cantestadosarray);

              var labels_ce = this.ce_json[0]
              var cantidad_ce = this.ce_json[1]
              // para setear los colores 
              let colores_ot_est=[]
              this.ce_json[0].forEach(lab => {
                colores_ot_est.push(this.coloresEst[lab])
              });
              var options_ce = {
                chart: {
                  type: 'pie'
                },
                series: cantidad_ce,
                labels: labels_ce,
                colors: colores_ot_est
              }

              this.chart_ce.updateOptions(options_ce);

              // chart_ce.render();

              // // // para el pie chart de ot por estados de gestion
              // // var cg= document.querySelector("div[data-cantGestionArray]");
              // // this.cg_json=JSON.parse(cg.dataset.cantgestionarray);

              var labels_cg = this.cg_json[0]
              var cantidad_cg = this.cg_json[1]
              // para setear los colores 
              let colores_ot_est_g=[]
              this.cg_json[0].forEach(lab => {
                colores_ot_est_g.push(this.coloresEstGest[lab])
              });
              var options_cg = {
                chart: {
                  type: 'pie'
                },
                series: cantidad_cg,
                labels: labels_cg,
                colors: colores_ot_est_g
              }

              this.chart_cg.updateOptions(options_cg);

              // chart_cg.render();

              // // para las solicitudes por estado
              // // var cse= document.querySelector("div[data-cantSolEstArray]");

              // // this.cantSolEstArray=JSON.parse(cse.dataset.cantsolestarray);

              var labels_cse = this.cantSolEstArray[0]
              var cantidad_cse = this.cantSolEstArray[1]
              // para setear los colores 
              let colores_ser_est=[]
              this.cantSolEstArray[0].forEach(lab => {
                colores_ser_est.push(this.coloresEst[lab])
              });
              // console.log(this.cantSolEstArray)
              // console.log(this.cantSolEstArray[0])
              // console.log(this.cantSolEstArray[1])
              var options_cse = {
                chart: {
                  type: 'pie'
                },
                series: cantidad_cse,
                labels: labels_cse,
                colors: colores_ser_est
              }

              this.chart_cse.updateOptions(options_cse);

              // chart_cse.render();

              // // para las solicitudes por servicio
              // var css= document.querySelector("div[data-cantSolServArray]");
              // // console.log(css.dataset)
              // this.cantSolServArray=JSON.parse(css.dataset.cantsolservarray);
              
              var labels_css = this.cantSolServArray[0]
              var cantidad_css = this.cantSolServArray[1]
              var options_css = {
                chart: {
                  type: 'pie'
                },
                series: cantidad_css,
                labels: labels_css
              }

              this.chart_css.updateOptions(options_css);

              this.isLoading = false;
            }
            );

        // }
      }
  }


};
</script>

<style>
.center {
  text-align: center;
}
</style>