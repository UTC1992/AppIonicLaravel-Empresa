<template>
  <div class="col-md-6">
    <table class="table">
      <thead>
        <tr>
          <th>KEY</th>
          <th>VALUE</th>
          <th>ESTADO</th>
          <th>FILTER</th>
          <th>ORDER</th>
          <th>ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="config in configs" :key="config.id">
          <td>{{config.key}}</td>
          <td>{{config.value}}</td>
          <td>{{config.estado}}</td>
          <td>{{config.filter}}</td>
          <td>{{config.order}}</td>
          <td>
            <button type="button" class="btn btn-danger btn-xs" v-on:click="eliminarConfing(config.id)" >Eliminar</button>
            <!--<button type="button" class="btn btn-warning btn-xs" v-on:click="editarConfing(config.id)" >Editar</button>-->
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data(){
    return {
      configs:{},
      renderComponent: true,
    }
  },
  mounted() {
    this.getConfigServer();
},
methods:{
  getConfigServer(){
    var idEmpresa=document.getElementById("empresa").value;
    console.log(idEmpresa);
    axios.get('/gestion/configuracion/'+idEmpresa).then(
      ({data})=>{
        if(data.data){
          console.log(data);
          this.configs=data.data;
        }else{
          alert(data);
          console.log(data);
        }
      }
    );
  },

  eliminarConfing(idConfig){
  //  alert(idConfig);
    axios.get('/gestion/configuracion/delete/'+idConfig).then(
      ({data})=>{
        //alert(data);
        if(data){
          this.getConfigServer();
        }

      }
    );
  },
  editarConfing(idConfig){
    alert(idConfig);
  }
}

}
</script>
