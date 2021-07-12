<template>
    <v-navigation-drawer nav height="105vh" v-model="drawer" class="text-start py-12" app temporary>
        <v-list-item>
            <v-list-item-avatar>
                <!--                <v-img :src="require('@/../img/15.jpg')"></v-img>-->
            </v-list-item-avatar>

            <v-list-item-content>
                <v-list-item-title>Jenis Sagbini</v-list-item-title>
            </v-list-item-content>
        </v-list-item>

        <v-list-item>
            <v-list-item-content>
                <v-list-item-subtitle>¡Haciendo a la Gente Exitosa en un mundo cambiante!.</v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>

        <v-list dense>
            <v-list-item-group v-model="item" active-class="primary text--accent-4">
                <inertia-link v-for="(item, index) in items" :key="index" link :href="item.link">
                    <v-list-item :class="route().current(item.link)?'active primary  white--text':''">
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </inertia-link>
            </v-list-item-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
export default {
    data() {
        return {
            item: parseInt(this.pagina),
            items: [
                {link: "/", icon: "mdi-home", title: "Inicio"},
                {
                    link: "/quienSoy",
                    icon: "mdi-account-question",
                    title: "¿Quien soy?"
                },

                {link: "/proyectos", icon: "mdi-file-tree", title: "Proyectos"},
                {link: "/articulo", icon: "mdi-card-text", title: "Artículos"},
                {link: "/servicios", icon: "mdi-face-agent", title: "Servicios"},
                {
                    link: "/contacto",
                    icon: "mdi-card-account-mail",
                    title: "Contacto"
                }
            ]
        };
    },
    computed: {
        drawer: {
            get() {
                return this.$store.state.drawer;
            },
            set(val) {
                this.$store.commit("setDrawer", val);
            }
        }
    }
};
</script>

<style>
</style>
