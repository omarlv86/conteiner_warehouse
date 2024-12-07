<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                    <h4 class="text-center my-4">Inventario</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button class="btn btn-primary">Nuevo registro</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Numero de contenedor</th>
                            <th>Tamaño</th>
                            <th>Estatus</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item, index in items" :key="index">
                            <td>{{item.numero_contenedor}}</td>
                            <td>{{item.tamano}}</td>
                            <td>{{item.estatus == 1 ? 'Dentro': 'Fuera'}} del almacen</td>
                            <td>
                                <button class="btn btn-primary">Editar</button>
                                <button class="btn btn-danger">Eliminar</button>
                            </td>
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
                infoSolicitud:{
                    calle:'',
                    num_ext:'',
                    num_int:'',
                    cp:'',
                    colonia:'',
                    municipio:'',
                    ciudad:'',
                    estado:'',
                    ingreso_bruto:'',
                    num_ide:'',
                    no_sol:'',
                    poliza:'',
                    ret:'',
                    u_pago:'',
                    concepto:'',
                    matricula:'',
                    zona:'',
                    tipo_negocio:'',
                    ret_cen_tra:'',
                    unidad_pago_cen_tra:'',
                    nextpay:''
                },
                submitting: false,

            }),

            mounted() {
                this.getInventario()
            },

            methods: {
              getInventario(){
                axios.post(this.baseUrl, {'param': 'getInventario'}, this.httpConfig)
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
                submit(){
                  try {
                    this.v$.$touch();
                    
                    if (!this.v$.data.$invalid) {
                        this.loading = true;
                        this.data.getInfo = true;
                        axios.post(this.baseUrl, this.data, this.httpConfig)
                        .then(res => {
                        if (this.datatable) {
                            this.datatable.destroy();
                        }
                        if(res.data.length == 0){
                            this.loading = false;
                            setTimeout(() => {
                                alert("El folio no está Entregado o no es de Pro99")
                            }, 500);
                        }else{
                            this.items = res.data.data;
                            this.info = res.data.info
                            Vue.nextTick(() => {
                                this.datatable =  new DataTable('#table', {
                                language: {
                                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                                },
                                "bDestroy": true
                            });
                            this.loading = false;
                            });
                        }
                        
                        
                        })
                        .catch(this.handleError);
                    }
                  } catch (error) {
                    this.loading = false;
                  }

                },
                showDocuments(num_solicitud){
                    try {
                        const url = this.documentsUrl
                        this.numSolicitud = num_solicitud
                        const data = {
                            num_solicitud
                        }
                        axios.post(this.documentsUrl, data, this.httpConfig)
                        .then(res => {
                            this.documentsGen = res.data[1].archivos
                            this.documentsTit = res.data[0].archivos
                            this.path = res.data[0].path
                            this.infoSolicitud = res.data[2][0]
                            console.log(this.infoSolicitud)
                        })
                        .catch(error => {
                            console.log(error)
                        });
                    } catch (error) {
                    //this.loading = false;
                    console.log('error submit: ',error)
                  }
                },
                openfile(nombre, tipo){
                    let url = "";
                    if(tipo == "titular"){
                        url = `${this.path}${this.numSolicitud}/Documentos del Titular/${nombre}`;
                    }else{
                        url = `${this.path}${this.numSolicitud}/Documentos General/${nombre}`;
                    }
                    window.open(url)
                },
                toggleSelectAll(status){
                    this.items.forEach(obj => {
                        if(obj.sol_rechazada == 1 || obj.sol_procesada != 1){
                            obj.status = status;
                        }
                    });
                },
                loadPre(solicitud){
                    url = `/sipac/admon/folios/folios_pre_rechazos.php?numsol=${solicitud}`;
                    location.href = url
                },
                loadRechazo(solicitud){
                    url = `/sipac/admon/folios/folios_rechazos.php?numsol=${solicitud}`;
                    location.href = url
                },
                updateInfoNumSolicitud(){
                    
                    const data = {
                        'data' : this.infoSolicitud,
                        'updateInfo': true,
                    }
                    axios.post(this.baseUrl, data, this.httpConfig) 
                    .then(res => {
                        if(res.status == 200){
                            alert("Se han validado los folios correctamente.")
                            update_form = {
                                'no_sol' : this.infoSolicitud.no_sol,
                            }
                            this.formModified.push(update_form)
                        }else{
                            alert("Ha ocurrido un error.")
                        }      
                    })
                    .catch(err => {
                            console.log(err)
                            this.handleError
                    });
                },
                validateInfo(){
                    try {
                        this.loading = true;
                        const fecha = new Date().toISOString().slice(0, 19).replace('T', ' ');
                        
                        let newData = this.items.filter(el => el.status === true)
                        
                        let data = {
                            'info': this.info,
                            'rows': newData,
                            'validateInfo': true
                        }
                        axios.post(this.baseUrl, data, this.httpConfig) 
                        .then(res => {
                            if(res.data.status == 200){
                                this.loading = false;
                                alert("Se han validado los folios correctamente.")
                            }else{
                                this.loading = false;
                                alert("Ha ocurrido un error.")
                            }
                        })
                        .catch(err => {
                            console.log(err)
                            this.handleError
                        });
                    } catch (error) {
                        console.log(error)
                    }
                },
                reingresar(id_desglose, no_solicitud, msg) {
                    let data = {
                        reingreso: 1,
                        id_desglose,
                        no_solicitud,
                        msg,
                    }
                    try {
                        this.loading = true;
                        axios.post('/sipac/admon/Robots/validar_pro99_test.php', data, this.httpConfig) 
                        .then(res => {
                            console.log('res', res.data);
                            if(res.status == 200){
                                Vue.nextTick(() => {
                                    this.datatable =  new DataTable('#table', {
                                        language: {
                                            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                                        },
                                        "bDestroy": true
                                    });
                                    this.loading = false;
                                });
                                alert("Se han reingresado la solicitud correctamente.")
                            }else{
                                this.loading = false;
                                alert("Ha ocurrido un error.")
                            }
                        })
                        .catch(err => {
                            console.log(err)
                            this.handleError
                        });
                    } catch (error) {
                        console.log(error)
                    }
                }, 
                formatCurrency(number) {
                    const formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 2,
                    });
                    return formatter.format(number);
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