<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de contenedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vue/composition-api@1.7.1"></script>
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div id="app">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="">Numero de contenedores</label>
                        <select class="form-select" v-model="totalContenedores" @change="initializeContenedores">
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div v-for="(contenedor, index) in contenedores" :key="index">
                        <div class="mb-3">
                            <label for="">Numero de contenedor</label>
                            <input type="text"  class="form-control" placeholder="Ingresa numero de contenedor" v-model="contenedor.numero">
                        </div>
                        <div class="mb-3">
                            <label for="">Tamaño</label>
                            <select class="form-select"  v-model="contenedor.tamano">
                                <option value="20HC">20HC</option>
                                <option value="40HC">40HC</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">Numero economico</label>
                        <input type="text"  class="form-control" placeholder="Ingresa numero economico" v-model="data.numero_economico">
                    </div>
                    <div class="mb-3">
                        <label for="">Placas de la unidad</label>
                        <input type="text"  class="form-control" placeholder="Ingresa las placas de la unidad" v-model="data.placas_unidad">
                    </div>
                    <div class="mb-3">
                        <label for="">Nombre del conductor</label>
                        <input type="text"  class="form-control" placeholder="Ingresa el nombre del conductor" v-model="data.nombre_conductor">
                    </div>
                    <div class="mb-3">
                        <label for="">Flujo</label>
                        <select class="form-select" v-model="data.flujo">
                            <option value="1">Entrada</option>
                            <option value="2">Salida</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" @click="insertarContenedor" >Registrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-demi"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vuelidate/core"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vuelidate/validators"></script>

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
                        numero_economico: { 
                            required: this.vv.helpers.withMessage(this.errorMessages.required, this.vv.required) 
                        },
                        placas_unidad: { 
                            required: this.vv.helpers.withMessage(this.errorMessages.required, this.vv.required) 
                        },
                        nombre_conductor: { 
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
                loading: false,
                path:null,
                datatable: null,
                selectAll: false,
                data:{
                    folio:null,
                },
                totalContenedores: 0,
                data:{
                    numero_economico:'',
                    placas_unidad:'',
                    nombre_conductor:'',
                    flujo:1,
                },
                contenedores : [],
                submitting: false,
                mensaje: ''

            }),

            mounted() {
                //this.getInventario()
            },

            methods: {
                insertarContenedor(){
                    this.v$.$touch();
                    if (!this.v$.data.$invalid) {
                        let data = this.data;
                        let counter20 = 0;
                        let counter40 = 0;
                        let counterError = 0;
                        this.contenedores.forEach(element => {
                            if(element.tamano == '20HC') counter20++
                            if(element.tamano == '40HC') counter40++
                            if(counter20 > 2){
                                alert('No se pueden ingresar mas de 2 contenedores de tamaño 20HC')
                                counterError++;
                            }
                            if(counter40>1){
                                alert('No se pueden ingresar mas de 1 contenedor de tamaño 40HC') 
                                counterError++;
                            }
                            if(counter20 >=1 && counter40 >= 1){
                                alert('No se pueden ingresar 1 contenedor de tamaño 40HC y un contenedor 20HC al mismo tiempo')
                                counterError++;
                            }
                        });
                        if(counterError > 0) return
                        data['contenedores'] = this.contenedores
                        data['param'] = 'contenedores';
                        axios.post(this.baseUrl,data, this.httpConfig)
                        .then(res => {
                            res.data.status == 200 ? alert(res.data.msg) : alert(res.data.error)
                            this.initializeContenedores()
                            this.data.numero_economico = '';
                            this.data.placas_unidad = '';
                            this.data.nombre_conductor = ''
                            this.flujo = 1
                        })
                        .catch(this.handleError);
                    }else{
                        alert('Ingresa los datos correctamente')
                    }
                }, 
                initializeContenedores() {
                    this.contenedores = Array.from({ length: this.totalContenedores }, () => ({
                        numero: '',
                        tamano: ''
                    }));
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