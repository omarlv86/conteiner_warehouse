<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center my-4">Historial de contenedores</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre conductor</th>
                            <th>Numero economico</th>
                            <th>Numero de camion</th>
                            <th>Tamaño</th>
                            <th>Movimiento</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item, index in items" :key="index">
                            <td>{{item.conductor}}</td>
                            <td>{{item.numero_economico}}</td>
                            <td>{{item.numero}}</td>
                            <td>{{item.tamano}}</td>
                            <td>{{item.movimiento}}</td>
                            <td>{{item.fecha_movimiento}}</td>
                        </tr>
                        
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script id="script">

        // const modal = new bootstrap.Modal(document.getElementById('categoryModal'))
        new Vue({
            el: '#app',
            setup() {
                return {
                    v$: Vuelidate.useVuelidate(),
                    vv: VuelidateValidators,
                }

            },

            validations(){

                return {

                    data: {
                        folio: { 
                            required: this.vv.helpers.withMessage(this.errorMessages.required, this.vv.required) 
                        },
                    }

                };

            },

            data: () => ({
                errorMessages: {
                    required: 'Este campo es requerido'
                },

                httpConfig: {

                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        "Content-Type": "application/x-www-form-urlencoded"
                    }

                },

                baseUrl: '/api.php',
                documentsUrl: '/sipac/admon/Robots/validar_pro99_documents.php',
                items:[],
                info:[],
                formModified:[],
                documentsGen:[],
                documentsTit:[],
                loading: false,
                numSolicitud: null,
                path:null,
                datatable: null,
                selectAll: false,
                data:{
                    folio:null,
                },
                submitting: false,

            }),

            mounted() {
                this.getHistorial()
            },

            methods: {
                getHistorial(){
                    axios.post(this.baseUrl, {'param': 'getHistorial'}, this.httpConfig)
                    .then(res => {
                            this.items = res.data.data;
                            this.start()
                    })
                    .catch(this.handleError);
                },
                start() {                    
                if (!this.datatable) {
                  this.loading = true;
                }
                Vue.nextTick(() => {
                    this.datatable =  new DataTable('#example', {
                      language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                      },
                      "bDestroy": true
                      });

                    this.loading = false;
                });


                }, 
                handleError(e) {
                    if (axios.isAxiosError(e)) {
                        alert(e.response.data.error);
                    } else {
                        alert('Ha ocurrido un error');
                    }
                    this.submitting = false;
                    this.loading = false;
                }

            },
        });

    </script>
</body>
</html>