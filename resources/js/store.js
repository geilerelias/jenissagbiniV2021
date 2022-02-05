import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        drawer: false,
        search: "",
        links: [
            {
                route: "inicio",
                icon: "mdi-home",
                title: "Inicio"
            },
            {
                route: "quienSoy",
                icon: "mdi-account-question",
                title: "¿Quien soy?"
            },

            {
                route: "proyectos",
                icon: "mdi-file-tree",
                title: "Proyectos"
            },
            {
                route: "articulo",
                icon: "mdi-card-text",
                title: "Artículos"
            },
            {
                route: "servicios",
                icon: "mdi-face-agent",
                title: "Servicios"
            },
            {
                route: "contacto",
                icon: "mdi-card-account-mail",
                title: "Contacto"
            },
        ],
        linksWithAuth: [
            {
                route: "dashboard",
                icon: 'mdi-view-dashboard',
                title: "Dashboard",
                can: 'view dashboard'
            },
            {
                route: "notices.index",
                icon: 'mdi-bulletin-board',
                title: "Noticias",
                can: 'view notices'
            },
            {
                route: "about.index",
                title: "Quien soy",
                icon: "mdi-account-question",
                can: 'view about'
            },
            {
                route: "articles",
                title: "Artículos",
                icon: "mdi-file-tree",
                can: 'view articles'
            },
            {
                route: "project",
                title: "Proyectos",
                icon: "mdi-card-text",
                can: 'view project'
            },
            {
                route: "services",
                icon: 'mdi-face-agent',
                title: "Servicios",
                can: 'view services'
            },

            // {route: "posts.index", icon: 'mdi-note-text-outline', title: "Post"},
            {
                route: "user.index",
                icon: 'mdi-account-tie',
                title: "User",
                can: 'view user'
            },
            {
                route: "role.index",
                icon: 'mdi-shield-account',
                title: "Roles",
                can: 'view rol'
            },
            {
                route: "permission.index",
                icon: 'mdi-badge-account-outline',
                title: "Permisos",
                can: 'view permission'
            },

        ],
        expand: false,
    },
    getters: {
        getDrawer(state) {
            return state.drawer;
        },
        getSearch(state) {
            return state.search;
        },
        getExpand(state) {
            return state.expand;
        },
    },
    mutations: {
        setDrawer(state, v) {
            state.drawer = v;
        },
        setSearch(state, v) {
            state.search = v;
        },
        setExpand(state, v) {
            state.expand = v;
        },
    },
    actions: {}
});

export default store;
