<template>
  <div class="card">
    <div class="col-md-12">
      <div class="col-md-6">
        <button type="button" class="btn btn-warning btn-block" v-on:click="generateConfing()" >GENERAR CONFIGURACION</button>
        <button type="button" class="btn btn-danger btn-block" v-on:click="borrarConfing()" >BORRAR CONFIGURACION</button>
      </div>
      <div class="col-md-6">

          <input  class="form-control" readonly type="text" v-model="mensaje" />
      </div>
    </div>
  </div>
</template>

<script>
    export default {
      data(){
        return {
          mensaje:"",
        }
      },
        mounted() {
          this.validateConfig();
            console.log('Component mounted.');
        },

        methods:{
          generateConfing(){
            var token=document.getElementById("_token").value;
            var idEmpresa=document.getElementById("empresa").value;
            const params={
              _token:token,
              id:idEmpresa,
            }
            axios.post('/gestion/configuracion/create',params).then(
              ({data})=>{
                if(data){
                  this.mensaje=data;
                }
              }
            );
          },

          validateConfig(){
            var idEmpresa=document.getElementById("empresa").value;
            axios.get('/gestion/configuracion/validate/'+idEmpresa).then(
              ({data})=>{
                if(data){
                  this.mensaje="LA CONFIGURACIÓN YA HA SIDO GENERADA";
                }
                else if(!data){
                  this.mensaje="CONFIGURACION NO GENERADA";
                }
              }
            );
          },
          borrarConfing(){
            var idEmpresa=document.getElementById("empresa").value;
            axios.get('/gestion/configuracion/drop/'+idEmpresa).then(
              ({data})=>{
                if(data){
                  alert("Tabla borrada con éxito");
                  this.mensaje="CONFIGURACION NO GENERADA";
                }

              }
            );
          }
        }
    }
</script>
