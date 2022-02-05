<template>
    <v-app>
        <v-breadcrumbs :items="items"></v-breadcrumbs>
        <v-card>
            <v-toolbar dark flat>
                <v-btn icon @click="$router.push({ name: 'articles/main' })">
                    <v-icon>mdi-arrow-left</v-icon>
                </v-btn>
                <v-toolbar-title>Otros Artículos</v-toolbar-title>
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
                :items="othersArticles"
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

        <v-row justify="center">
            <v-dialog
                v-model="dialog"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
            >
                <v-card>
                    <add-other-article
                        :formTitle="formTitle"
                        :editedItem="editedItem"
                        :editedIndex="editedIndex"
                        :idItem="othersArticles[editedIndex]"
                        @updateArrayOfOthersArticles="
                            Object.assign(othersArticles[index], $event)
                        "
                        @addOthersArticles="othersArticles.push($event)"
                        @closeDialog="dialog = $event"
                        @editedItem="editedItem = $event"
                        @editedIndex="editedIndex = $event"
                    ></add-other-article>
                </v-card>
            </v-dialog>
        </v-row>
    </v-app>
</template>

<script>
import AddOtherArticle from "./AddOtherArticle.vue";
// var loadingTask = pdf.createLoadingTask('/pdfs/Resolucion 29452 de 2017.pdf');
export default {
    components: {
        "add-other-article": AddOtherArticle
    },
    data: () => ({
        dialog2: false,
        notifications: false,
        sound: true,
        widgets: false,
        viewDocument: false,
        dialog: false,
        headers: [
            {
                text: "Id",
                align: "left",
                sortable: false,
                value: "id"
            },
            { text: "Titulo", value: "title" },
            { text: "Descripción", value: "description" },
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
                text: "Otros Artículos",
                disabled: true,
                href: "#"
            }
        ],
        othersArticles: [],
        editedIndex: -1,
        editedItem: {
            id: "",
            title: "",
            description: "",
            file: null
        },
        defaultItem: {
            id: "",
            title: "",
            description: "",
            file: null
        },
        search: ""
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "Nuevo Item" : "Editar Item";
        }
    },
    mounted() {
        this.initialize();
    },

    methods: {
        initialize() {
            this.othersArticles = [
                {
                    id: "0",
                    title: "Ejemplo de otro articulo",
                    description: "Este es el ejemplo de otro articulo",
                    file: "",
                    created_at: "2019-10-11 03:09:46",
                    updated_at: "2019-10-11 03:09:46"
                }
            ];
            this.axios
                .get("/api/othersArticle")
                .then(res => {
                    this.othersArticles = this.othersArticles.concat(res.data);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        editItem(item) {
            console.log("this is item: ", item);
            this.editedIndex = this.othersArticles.indexOf(item);
            console.log(this.editedIndex);
            this.editedItem = Object.assign({}, item);
            console.log(this.editedItem);
            this.dialog = true;
        },

        deleteItem(item) {
            const index = this.othersArticles.indexOf(item);
            Swal.fire({
                title: "Estás seguro?",
                html: `Eliminar <b>${item.title}</b>, No podrás revertir esto!`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#424242",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Eliminar!"
            }).then(result => {
                console.log("item.id", item.id);

                if (result.value) {
                    axios
                        .delete(`/api/othersArticle/${item.id}`)
                        .then(response => {
                            Swal.fire(
                                "Eliminado!",
                                "Eliminado correctamente.",
                                "success"
                            );
                            this.othersArticles.splice(index, 1);
                        })
                        .catch(error => {
                            Swal.fire(
                                "Oops...",
                                "Algo salió mal! " + error,
                                "error"
                            );
                        });
                }
            });
        }
    }
};
</script>
