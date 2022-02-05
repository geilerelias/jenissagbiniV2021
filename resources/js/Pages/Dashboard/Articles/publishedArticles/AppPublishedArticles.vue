<template>
    <v-app>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <v-card>
            <v-toolbar dark flat>
                <v-btn icon @click="$router.push({ name: 'articles/main' })">
                    <v-icon>mdi-arrow-left</v-icon>
                </v-btn>
                <v-toolbar-title>Artículos publicados</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <div class="flex-grow-1"></div>
                <v-text-field
                    max-width="200"
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>

                <v-btn icon @click="dialog = !dialog">
                    <v-icon>mdi-plus-circle</v-icon>
                </v-btn>
            </v-toolbar>

            <v-data-table
                :search="search"
                :headers="headers"
                :items="publishedArticles"
                class="elevation-1"
            >
                <template v-slot:item.action="{ item }">
                    <v-icon small class="mr-2" @click="editItem(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon small @click="deleteItem(item)">
                        mdi-delete
                    </v-icon>
                </template>
                <template v-slot:no-data>
                    <v-btn color="primary" @click="initialize">Reset</v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog
            v-model="dialog"
            fullscreen
            hide-this.overlay
            transition="dialog-bottom-transition"
        >
            <v-card>
                <v-toolbar dark>
                    <v-btn icon dark @click="dialog = !dialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title class="headline">{{
                        formTitle
                    }}</v-toolbar-title>
                    <div class="flex-grow-1"></div>
                    <v-toolbar-items>
                        <v-btn
                            text
                            :disabled="!valid"
                            class="mr-4"
                            @click="validate"
                        >
                            Registrar
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-container fluid>
                        <v-form
                            ref="form"
                            v-model="valid"
                            :lazy-validation="lazy"
                        >
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        clearable
                                        v-model="editedItem.title"
                                        :rules="titleRules"
                                        label="Titulo"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-autocomplete
                                        clearable
                                        v-model="editedItem.country"
                                        :rules="countryRules"
                                        :items="countries"
                                        :menu-props="{
                                            top: false,
                                            offsetY: true
                                        }"
                                        label="País"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-menu
                                        ref="menu"
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        :return-value.sync="editedItem.year"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="editedItem.year"
                                                :rules="yearRules"
                                                label="Fecha"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="editedItem.year"
                                            type="month"
                                            locale="es"
                                            no-title
                                            scrollable
                                        >
                                            <div class="flex-grow-1"></div>
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="menu = false"
                                                >Cancel</v-btn
                                            >
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="
                                                    $refs.menu.save(
                                                        editedItem.year
                                                    )
                                                "
                                                >OK</v-btn
                                            >
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        clearable
                                        v-model="editedItem.isbn"
                                        label="ISBN"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-text-field
                                        clearable
                                        v-model="editedItem.ed"
                                        label="Ed"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-text-field
                                        clearable
                                        v-model="editedItem.link"
                                        label="Enlace"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-file-input
                                        accept="application/pdf"
                                        clearable
                                        v-model="editedItem.file"
                                        label="Subir archivo "
                                    ></v-file-input>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea
                                        clearable
                                        v-model="editedItem.description"
                                        :rules="descriptionRules"
                                        label="Descripción"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="warning" class="mr-4" @click="close">
                        Cancelar
                    </v-btn>
                    <v-btn color="error" class="mr-4" @click="reset">
                        Reiniciar
                    </v-btn>
                    <v-btn
                        :disabled="!valid"
                        color="success"
                        class="mr-4"
                        @click="save"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div class="text-center">
            <v-overlay :opacity="0.9" :value="overlay">
                <v-row class="mb-6" no-gutters>
                    <v-col sm="12"
                        ><v-progress-circular
                            indeterminate
                            size="64"
                        ></v-progress-circular>
                    </v-col>

                    <v-col class="mt-5">
                        <span class="white--text">
                            Un momento por favor, estamos procesando su
                            solicitud
                        </span>
                    </v-col>
                </v-row>
            </v-overlay>
        </div>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        overlay: false,
        menu: false,
        dialog: false,
        headers: [
            {
                text: "Id",
                align: "left",
                sortable: false,
                value: "id"
            },
            { text: "Titulo", value: "title" },
            { text: "País", value: "country" },
            { text: "Año", value: "year" },
            { text: "Descripción", value: "description" },
            { text: "ISBN", value: "isbn" },
            { text: "Ed", value: "ed" },
            { text: "Enlace", value: "link" },
            { text: "Accion", align: "left", sortable: false, value: "action" }
        ],
        items: [
            {
                text: "Dashboard",
                disabled: false,
                href: "/dashboard"
            },
            {
                text: "Artículos",
                disabled: false,
                href: "/dashboard/articles"
            },
            {
                text: "Artículos publicados",
                disabled: true,
                href: "#"
            }
        ],
        publishedArticles: [],
        editedIndex: -1,
        editedItem: {
            id: "",
            title: "",
            description: "",
            year: "",
            country: "",
            isbn: "",
            ed: "",
            link: "",
            file: null
        },
        defaultItem: {
            id: "",
            title: "",
            description: "",
            year: "",
            country: "",
            isbn: "",
            ed: "",
            link: "",
            file: null
        },
        search: "",
        valid: true,
        titleRules: [v => !!v || "Titulo es requerido"],
        yearRules: [v => !!v || "Año es requerido"],
        countryRules: [v => !!v || "País promotor es requerido"],
        descriptionRules: [v => !!v || "Descripción es requerida"],
        linkRules: [v => /^(http|https)/.test(v) || "Enlace no valido"],
        lazy: false,
        countries: new Array(
            "Afghanistan",
            "Albania",
            "Algeria",
            "Andorra",
            "Angola",
            "Antarctica",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bermuda",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Brazil",
            "Brunei",
            "Bulgaria",
            "Burkina Faso",
            "Burma",
            "Burundi",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cape Verde",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Colombia",
            "Comoros",
            "Congo, Democratic Republic",
            "Congo, Republic of the",
            "Costa Rica",
            "Cote d'Ivoire",
            "Croatia",
            "Cuba",
            "Cyprus",
            "Czech Republic",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "East Timor",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Ethiopia",
            "Fiji",
            "Finland",
            "France",
            "Gabon",
            "Gambia",
            "Georgia",
            "Germany",
            "Ghana",
            "Greece",
            "Greenland",
            "Grenada",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Honduras",
            "Hong Kong",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran",
            "Iraq",
            "Ireland",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea, North",
            "Korea, South",
            "Kuwait",
            "Kyrgyzstan",
            "Laos",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macedonia",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Mauritania",
            "Mauritius",
            "Mexico",
            "Micronesia",
            "Moldova",
            "Mongolia",
            "Morocco",
            "Monaco",
            "Mozambique",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Norway",
            "Oman",
            "Pakistan",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Poland",
            "Portugal",
            "Qatar",
            "Romania",
            "Russia",
            "Rwanda",
            "Samoa",
            "San Marino",
            " Sao Tome",
            "Saudi Arabia",
            "Senegal",
            "Serbia and Montenegro",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Swaziland",
            "Sweden",
            "Switzerland",
            "Syria",
            "Taiwan",
            "Tajikistan",
            "Tanzania",
            "Thailand",
            "Togo",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Venezuela",
            "Vietnam",
            "Yemen",
            "Zambia",
            "Zimbabwe"
        )
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "Nuevo Item" : "Editar Item";
        }
    },

    watch: {
        dialog(val) {
            val || this.close();
        }
    },

    mounted() {
        this.initialize();
    },

    methods: {
        initialize() {
            this.publishedArticles = [
                {
                    id: 0,
                    title: `
                                SISTEMA DE DIALOGO HUMANO ROBOT UTILIZANDO RAZONAMIENTO BASADO EN CASOS. LA INVESTIGACION APLICADA EN LA SOCIEAD DE LA INFORMACION INNOVACION Y DESARROLLO TECNOLÓGICO
                         `,
                    description: `
                            Socializado en el CONGRESO INTERNACIONAL SOBRE TECNOLOGÌA E INNOVACIÒN+CIENCIA E INVESTIGACIÓN. BUENOS AIRES, ARGENTINA. 2018.
                        `,
                    year: "2018-01",
                    country: "Colombia",
                    isbn: "9789585660847",
                    ed: "CIMTED , v. , p.38 - 45  1 ,2018",
                    link: "",
                    file: null,
                    created_at: "2019-10-11 03:09:46",
                    updated_at: "2019-10-11 03:09:46"
                }
            ];
            this.overlay = true;
            this.axios
                .get("/api/publishedArticle")
                .then(res => {
                    this.publishedArticles = this.publishedArticles.concat(
                        res.data
                    );
                    this.overlay = false;
                })
                .catch(error => {
                    console.log(error);
                    this.overlay = false;
                });
        },

        editItem(item) {
            this.editedIndex = this.publishedArticles.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        deleteItem(item) {
            const index = this.publishedArticles.indexOf(item);
            Swal.fire({
                title: "Estás seguro?",
                html: `Eliminar <b>${item.title}</b>, No podrás revertir esto!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#424242",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminar!"
            }).then(result => {
                console.log("item.id", item.id);

                if (result.value) {
                    this.overlay = true;
                    axios
                        .delete(`/api/publishedArticle/${item.id}`)
                        .then(response => {
                            Swal.fire(
                                "Eliminado!",
                                "Eliminado correctamente.",
                                "success"
                            );
                            this.publishedArticles.splice(index, 1);
                            this.overlay = false;
                        })
                        .catch(error => {
                            Swal.fire(
                                "Oops...",
                                "Algo salió mal!" + error,
                                "error"
                            );
                            this.overlay = false;
                        });
                }
            });
        },
        close() {
            this.dialog = false;
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            }, 300);
        },
        save() {
            if (this.validate()) {
                if (this.editedIndex > -1) {
                    this.edit();
                } else {
                    var formData = new FormData();
                    formData.append("id", this.editedItem.id);
                    formData.append("title", this.editedItem.title);
                    formData.append("description", this.editedItem.description);
                    formData.append("year", this.editedItem.year);
                    formData.append("country", this.editedItem.country);
                    formData.append("isbn", this.editedItem.isbn);
                    formData.append("ed", this.editedItem.ed);
                    formData.append("link", this.editedItem.link);
                    formData.append("file", this.editedItem.file);

                    this.overlay = true;
                    axios
                        .post("/api/publishedArticle", formData)
                        .then(res => {
                            const publishedArticleServidor = res.data;
                            this.publishedArticles.push(
                                publishedArticleServidor
                            );
                            Swal.fire(
                                "Buen trabajo! 😃",
                                "Registro Exitoso! ",
                                "success"
                            );
                            this.overlay = false;
                        })
                        .catch(error => {
                            console.error(error);
                            this.overlay = false;
                        });
                    this.close();
                }
            } else {
                Swal.fire({
                    type: "error",
                    title: "Oops...",
                    text: "Campos no validados!"
                });
            }
        },
        edit() {
            const params = this.editedItem;
            const index = this.editedIndex;
            const item = this.editedItem;

            this.overlay = true;
            axios
                .put(
                    `/api/publishedArticle/${this.publishedArticles[index].id}`,
                    params
                )
                .then(res => {
                    Object.assign(this.publishedArticles[index], item);
                    Swal.fire(
                        "Buen trabajo!",
                        "Editado correctamente!",
                        "success"
                    );
                    this.close();

                    this.overlay = false;
                })
                .catch(error => {
                    Swal.fire({
                        type: "error",
                        title: "Oops...",
                        text: "Algo salió mal!"
                    });

                    this.overlay = false;
                });
        },
        validate() {
            if (this.$refs.form.validate()) {
                return true;
            }
            console.log("fallo la validacion");
            return false;
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        }
    }
};
</script>
