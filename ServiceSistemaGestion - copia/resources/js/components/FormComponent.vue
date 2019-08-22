<template>
  <div class="col-md-6">
    <div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Crear Configuración</h3>
            </div>
            <div class="box-body">
              <form class="" action="" v-on:submit.prevent="newConfig()" method="post">
              <!-- Date dd/mm/yyyy -->
              <input type="hidden" v-model="estado">
              <div class="form-group">
                  <label>CONFIG KEY:</label>
                  <div class="form-group">
                  <select  v-model="key" class="form-control">
                    <option value="table">Table</option>
                    <option value="column">Column</option>
                  </select>
                </div>

                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <!-- phone mask -->
              <div class="form-group">
                <label>CONFIG VALUE:</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-adversal"></i>
                  </div>
                  <input type="text" v-model="value" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->

              <!-- phone mask -->
              <div class="form-group">
                <label>IS FILTER:</label>
                <div class="form-group">
                <select  v-model="filter" class="form-control">
                  <option value="0">NO</option>
                  <option value="1">SI</option>
                </select>
              </div>
              </div>
              <!-- /.form group -->

              <!-- IP mask -->
              <div class="form-group">
                <label>ORDER FILTER:</label>
                <div class="form-group">
                <select v-model="order" class="form-control">
                  <option value="0">SELECT</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
              </div>
              </div>

              <!-- /.form group -->

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Crear</button>
              </div>
            </form>
            </div>

            <!-- /.box-body -->
          </div>
  </div>
</template>

<script>
    export default {
      data(){
        return{
          key:"table",
          order:0,
          filter:0,
          estado:1,
          value:'',
        }
      },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
          newConfig(){
            var token=document.getElementById("_token").value;
            var idEmpresa=document.getElementById("empresa").value;
            const params={
              _token:token,
              key:this.key,
              value:this.value,
              estado:this.estado,
              filter:this.filter,
              order:this.order,
              idEmpresa:idEmpresa,
            }
            axios.post('/gestion/configuracion',params).then(

              ({data})=>{
                if(data.length==69){
                  alert(data);
                }else if(data.length==52){
                alert(data);
                }else{
                    location.reload();
                }

              }


            );
          //  alert(this.value.trim());
          }
        }
    }
</script>
