<script setup>
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link, usePage } from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";
import BToast from "@/Components/BToast.vue";
import LanguageSelector from "@/Components/LanguageSelector.vue"
import BToastError from "@/Components/BToastError.vue";

const page = usePage();

const user = page.props.auth.user;
const profile = `https://ui-avatars.com/api/?name=${user.name}+${user.cognoms.charAt(0)}`;

</script>

<template>
    <div class="p-3 bg-light">
        <Link :href="route('empresa.index')" class="nav-link px-2 link-light"><img src="../../img/logo/logo_IES.png"
            alt="Logo IES Carles Vallbona" width="330" /></Link>
    </div>
    <header class="p-3 p-e-5 mb-3 border-bottom colorvb">
        <div class="container-flex">
            <div class="d-flex align-items-center justify-content-center justify-content-lg-start px-5">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li>
                        <Link :href="route('empresa.index')" class="nav-link px-2 link-light">{{ $t("Empresa") }}</Link>
                    </li>
                    <li>
                        <Link :href="route('collaboracio.index')" class="nav-link px-2 link-light">{{ $t("Estades")
                        }}</Link>
                    </li>
                    <li>
                        <Link :href="route('contacte.index')" class="nav-link px-2 link-light">{{ $t("Contactes") }}</Link>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <LanguageSelector></LanguageSelector>
                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img :src="profile" alt="Foto de perfil" width="32" height="32" class="rounded-circle" />
                        </a>
                        <ul class="dropdown-menu text-small">
                            <li>
                                <DropdownLink class="dropdown-item" :href="route('profile.edit')">
                                    <i class="bi bi-person"></i>
                                    {{ $t("Perfil") }}
                                </DropdownLink>
                            </li>
                            <li v-if="user.rol_id == 1">
                                <DropdownLink class="dropdown-item" :href="route('register')">
                                    <i class="bi bi-check2-circle"></i>
                                    {{ $t("Registrar usuari") }}
                                </DropdownLink>
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li>
                                <DropdownLink class="dropdown-item" :href="route('logout')" method="post" as="button">
                                    <i class="bi bi-box-arrow-right"></i>
                                    {{ $t("Surt") }}
                                </DropdownLink>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <transition name="page" mode="out-in">
        <main>
            <slot />
        </main>
    </transition>

    <BToast />
    <BToastError />
    <Footer class="footer"></Footer>
</template>

