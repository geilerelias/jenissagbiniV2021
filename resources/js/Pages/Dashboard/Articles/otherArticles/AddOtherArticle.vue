<template>
    <div>
        <v-toolbar dark style="border-radius:0">
            <v-btn icon dark @click="close()">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title class="headline">{{ formTitle }}</v-toolbar-title>
            <div class="flex-grow-1"></div>
            <v-toolbar-items>
                <v-btn text :disabled="!valid" class="mr-4" @click="validate">
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
                    enctype="multipart/form-data"
                >
                    <v-row>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field
                                clearable
                                v-model="editedArticle.title"
                                :rules="titleRules"
                                label="Titulo"
                                prepend-icon="mdi-format-title"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                clearable
                                v-model="editedArticle.description"
                                :rules="descriptionRules"
                                label="Descripción"
                                prepend-icon="mdi-text"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12">
                            <v-file-input
                                ref="file"
                                v-model="file"
                                :rules="fileRules"
                                accept=".pdf"
                                label="Seleccione un archivo"
                                single-line
                                v-on:change="handleFileUpload()"
                            />
                        </v-col>
                        <v-col cols="12">
                            <v-card
                                v-if="file !== null && file !== undefined"
                                class="mx-auto "
                                max-width="700"
                            >
                                <v-toolbar>
                                    <v-toolbar-title primary-title>
                                        Gestión de archivos
                                    </v-toolbar-title>
                                    <v-spacer></v-spacer>

                                    <v-btn icon @click="onUpload">
                                        <v-icon>mdi-file-eye</v-icon>
                                    </v-btn>

                                    <button icon @click="rotate -= 90">
                                        <v-icon>mdi-rotate-left</v-icon>
                                    </button>
                                    <button icon @click="rotate += 90">
                                        <v-icon>mdi-rotate-right</v-icon>
                                    </button>
                                    <v-chip
                                        v-if="
                                            $vuetify.breakpoint.smAndUp &&
                                                numPages != 0
                                        "
                                    >
                                        <v-avatar left class="grey lighten-1">
                                            {{ numPages }}
                                        </v-avatar>
                                        Páginas
                                    </v-chip>
                                </v-toolbar>

                                <v-divider></v-divider>

                                <v-progress-circular
                                    v-if="!loaded && viewDocument"
                                    :size="50"
                                    color="success"
                                    indeterminate
                                ></v-progress-circular>

                                <v-sheet
                                    v-show="loaded"
                                    class="pa-12"
                                    color="grey lighten-3"
                                    style="height: 500px;overflow: scroll"
                                >
                                    <div
                                        v-for="i in numPages"
                                        :key="i"
                                        class="d-flex  justify-center mb-6"
                                    >
                                        <pdf
                                            ref="myPdf"
                                            class="elevation-15 pa-0 ma-0 "
                                            :src="src"
                                            :page="i"
                                            @loaded="onLoad"
                                            :rotate="rotate"
                                            transition="fade-transition"
                                        ></pdf>

                                        <p></p>
                                    </div>
                                </v-sheet>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-form>
            </v-container>
        </v-card-text>

        <v-card-actions>
            <div class="flex-grow-1 "></div>
            <v-btn color="warning " class="mr-4 " @click="close">
                Cancelar
            </v-btn>
            <v-btn color="error " class="mr-4 " @click="reset">
                Reiniciar
            </v-btn>
            <v-btn
                :disabled="!valid"
                color="success "
                class="mr-4 "
                @click="save"
            >
                Guardar
            </v-btn>
        </v-card-actions>
    </div>
</template>

<script>
import pdf from "vue-pdf";
export default {
    components: {
        pdf
    },

    props: ["formTitle", "editedIndex", "editedItem", "idItem"],
    data() {
        return {
            isActive: false,
            loaded: false,
            src: "",
            file: null,
            fileUrl: null,
            show: true,
            numPages: 0,
            rotate: 0,
            valid: true,
            titleRules: [v => !!v || "Titulo es requerido"],
            descriptionRules: [v => !!v || "Descripción es requerida"],
            fileRules: [v => !!v || "El archivo es requerido"],
            lazy: false,
            indexArticle: -1,
            editedArticle: {
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
            }
        };
    },
    computed: {
        doubleScore() {
            return this.score * 2;
        }
    },
    beforeUpdate: function() {
        console.log("Empieza un nuevo renderizado de component");
        this.editedArticle = this.editedItem;
        console.log("this is edited article: ", this.editedArticle);

        console.log(
            "this is file: ",
            this.file,
            " this is index: ",
            this.editedIndex
        );
        if (this.editedIndex > 0 && this.file === null) {
            console.log("soy mayor");
            console.log("edit file: ", this.editedArticle.file);

            this.viewDocument = true;
            this.src = pdf.createLoadingTask(
                "/storage/" + this.editedArticle.file
            );
            this.src.then(pdf => {
                this.numPages = pdf.numPages;
            });
            this.viewDocument = true;
        }
    },
    methods: {
        handleFileUpload() {
            this.viewDocument = false;
            this.editedArticle.file = this.file;
            console.log(this.editedArticle.file);
        },
        onLoad() {
            this.loaded = true;
            console.log("lo hace");
        },
        Change() {
            let reader = new FileReader();
            reader.onload = () => {
                this.fileUrl = reader.result;
            };
            console.log(reader.readAsDataURL(this.editedArticle.file));
        },
        onUpload() {
            const fr = new FileReader();
            console.log(this.editedIndex);

            if (this.editedIndex > 0 && this.file === null) {
                console.log("soy mayor");
                console.log("edit file: ", this.editedArticle.file);

                this.viewDocument = true;
                this.src = pdf.createLoadingTask(
                    "/pdf/" + this.editedArticle.file
                );
                this.src.then(pdf => {
                    this.numPages = pdf.numPages;
                });
            } else if (this.file !== null) {
                this.viewDocument = true;
                fr.readAsDataURL(this.file);
                fr.addEventListener("load", () => {
                    this.src = pdf.createLoadingTask(fr.result);
                    this.src.then(pdf => {
                        this.numPages = pdf.numPages;
                    });
                });
            } else {
                Swal.fire(
                    "Archivo vacío!",
                    "No has elegido ningún archivo.",
                    "error"
                );
            }
        },
        password: function(updatePassword, reason) {
            updatePassword(prompt('password is "test"'));
        },
        error: function(err) {
            console.log(err);
        },

        save() {
            if (this.validate()) {
                if (this.editedIndex > -1) {
                    this.edit();
                } else {
                    let formData = new FormData();
                    formData.append("title", this.editedArticle.title);
                    formData.append(
                        "description",
                        this.editedArticle.description
                    );
                    formData.append("file", this.editedArticle.file);
                    console.log(formData);
                    axios
                        .post("/api/othersArticle", formData)
                        .then(res => {
                            const othersArticleServidor = res.data;
                            this.$emit(
                                "addOthersArticles",
                                othersArticleServidor
                            );

                            Swal.fire(
                                "Buen trabajo! 😃",
                                "Registro Exitoso! ",
                                "success"
                            );
                        })
                        .catch(error => {
                            console.error(error);
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
            const params = this.editedArticle;
            const index = this.editedIndex;
            let formData = new FormData();
            formData.append("title", this.editedArticle.title);
            formData.append("description", this.editedArticle.description);
            formData.append("file", this.editedArticle.file);

            axios
                .post(`/api/othersArticle/update/${this.idItem}`, formData)
                .then(res => {
                    this.$emit("updateArrayOfOthersArticles", params);

                    Swal.fire(
                        "Buen trabajo!",
                        "Editado correctamente!",
                        "success"
                    );
                    this.close();
                })
                .catch(error => {
                    Swal.fire({
                        type: "error",
                        title: "Oops...",
                        text: "Algo salió mal!"
                    });
                });
        },
        validate() {
            if (this.$refs.form.validate()) {
                return true;
            }
            console.log("fallo la validación");
            return false;
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        close() {
            this.$emit("closeDialog", false);
            // this.restoreDefault();

            setTimeout(() => {
                this.editedArticle = Object.assign({}, this.defaultItem);
                this.file = this.defaultItem.file;
                this.$emit("editedItem", this.editedArticle);
                // this.editedItem = Object.assign({}, this.defaultItem);
                this.indexArticle = -1;

                this.$emit("editedIndex", this.indexArticle);
                //this.editedIndex = -1;
                this.reset();
            }, 300);
        }
    }
};
</script>

<style lang="scss" scoped></style>
