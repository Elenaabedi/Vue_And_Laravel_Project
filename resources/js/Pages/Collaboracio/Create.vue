<script setup>
import { Form, Field } from "vee-validate";
import vue3StarRatings from "vue3-star-ratings";
import * as Yup from "yup";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { reactive } from 'vue';
import SearchSelect from "@/Components/SearchSelect.vue";

/**
 *  Data received from the controller
 */
var props = defineProps({
    empreses: {
        type: Object,
    },
    empresa: {
        type: Object,
    },
    contactes: {
        type: Object,
        required: true,
    },
    cicles: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    year: {
        type: Number,
        required: true,
    },
    errors: {
        type: Object,
        required: false,
    },
});

/**
 * Validations
 */
const schema = Yup.object().shape({
    contacte_id: Yup.string().required("S'ha d'assignar un contacte a l'estada."),
    cicle_id: Yup.string().required("S'ha d'assignar un cicle a l'estada."),
    any: Yup.number("Assignar un any és obligatori."),
});

async function scrollTop() {
    window.scroll({
        top: 100,
        behavior: "smooth",
    });
}

/**
 * Inputs from the controller
 */
const form = reactive({
    empresa_id: null,
    contacte_id: null,
    cicle_id: null,
    any: null,
    user: props.user,
    comentaris: null,
    stars: 0,
});


const state = reactive({
    empresaSelected: false,
    showEmpresaError: false,
})

function handleSelectEmpresa(selectedOption) {
    if (selectedOption) {
        form.empresa_id = selectedOption.id;
        state.empresaSelected = true;
        state.showEmpresaError = false;
    }

    contactesFiltrats.length = 0;
    for (const contacte of props.contactes) {
        if (contacte.empresa_id == selectedOption.id) {
            contactesFiltrats.push(contacte);
        }
    }
}

function handleCloseEmpresa() {
    if (!state.empresaSelected) {
        state.showEmpresaError = true;
    }
}

function checkEmpresa() {
    if (props.empresa) {
        return true;
    }
    if (state.empresaSelected) {
        return true;
    } else {
        state.showEmpresaError = true;
        return false;
    }
}

var contactesFiltrats = reactive([]);

async function ChargeContactes() {
    contactesFiltrats.length = 0;
    for (const contacte of props.contactes) {
        if (contacte.empresa_id == props.empresa.id) {
            contactesFiltrats.push(contacte);
        }
    }

    return contactesFiltrats;
}

async function valueEmpresa_id() {
    if (props.empresa) {
        form.empresa_id = props.empresa.id;
        ChargeContactes();
    }
}

valueEmpresa_id();

var anys = reactive([]);

function carregarAny() {
    anys.length = 0;

    for (let index = 1970; index < props.year; index++) {
        anys.push(index);
    }

    anys = anys.reverse();

    return anys;
}

carregarAny();

// Request form
async function onSubmit(values) {
    if (checkEmpresa()) {
        router.post("/collaboracio/store", form);

        if (Object.keys(props.errors).length > 0) {
            scrollTop();
        }
    }
}
</script>

<template>
    <Head :title="$t(`Crear Estada`)" />
    <AuthenticatedLayout>
        <h1 class="mt-5 ms-5 mb-4">{{ $t("Nova Estada") }}</h1>
        <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors }" class="ms-5 me-5">
            <div class="form-row">
                <div class="serverError" v-if="Object.keys(props.errors).length > 0">
                    <ol>
                        <li v-for="item in props.errors">
                            {{ $t(item) }}
                        </li>
                    </ol>
                </div>
                <!-- Empresa -->
                <div class="form-group col mt-3">
                    <label class="mb-2">{{ $t("Empresa") }}</label>
                    <template v-if="empreses">
                        <SearchSelect
                        :values="props.empreses"
                        @select="handleSelectEmpresa"
                        @close="handleCloseEmpresa"
                        />
                        <div v-if="state.showEmpresaError" class="text-danger">
                            {{ $t("L'assignació d'empresa és obligatòria.") }}
                        </div>
                    </template>
                    <template v-else-if="empresa">
                        <!-- Empresa -->
                        <div class="form-group col">
                            <Field name="empresa_id" type="text" class="form-control" :value="empresa.nom" disabled />
                        </div>
                    </template>
                    <div class="invalid-feedback">{{ errors.empresa_id }}</div>
                </div>
                <!-- Contacte -->
                <div class="form-group col mt-3">
                    <label class="mb-2">{{ $t("Contacte") }}</label>
                    <Field name="contacte_id" as="select" class="form-select" :class="{ 'is-invalid': errors.contacte_id }"
                        v-model="form.contacte_id">
                        <template v-if="form.empresa_id === null">
                            <option value="">{{ $t("Selecciona una empresa") }}</option>
                        </template>
                        <template v-else-if="contactesFiltrats.length > 0">
                            <option v-for="contactef of contactesFiltrats" :value="contactef.id">
                                {{ contactef.nom }} {{ contactef.cognoms }}
                            </option>
                        </template>
                        <template v-else>
                            <option value="">{{ $t("Encara no hi ha contactes assignats a aquesta empresa") }}</option>
                        </template>
                    </Field>
                    <Link v-if="contactesFiltrats.length == 0 && form.empresa_id"
                        :href="route('contacte.create', form.empresa_id)" as="button" class="btn btn-primary mt-3">{{
                            $t("Crear Contacte") }}</Link>
                    <div class="invalid-feedback">{{ errors.empresa_id }}</div>
                </div>
                <!-- Cicle -->
                <div class="form-group col mt-3">
                    <label class="mb-2">{{ $t("Cicle") }}</label>
                    <Field name="cicle_id" id="cicle_id" as="select" class="form-select"
                        :class="{ 'is-invalid': errors.cicle_id }" v-model="form.cicle_id">
                        <option v-for="cicle in cicles" :value="cicle.id">
                            {{ cicle.nom }}
                        </option>
                    </Field>
                    <div class="invalid-feedback">{{ errors.cicle_id }}</div>
                </div>
                <!-- Any -->
                <div class="form-group col mt-3">
                    <label class="mb-2">{{ $t("Any") }}</label>
                    <Field name="any" id="any" as="select" class="form-select" :class="{ 'is-invalid': errors.any }"
                        v-model="form.any">
                        <option v-for="any in anys" :value="any">
                            {{ any }}
                        </option>
                    </Field>
                    <div class="invalid-feedback">{{ errors.any }}</div>
                </div>
                <!-- User -->
                <Field name="user" type="text" class="form-control" v-model="form.user" hidden />
                <!-- Comentaris -->
                <div class="form-group col mt-3">
                    <label class="mb-2">{{ $t("Comentaris") }}</label>
                    <Field as="textarea" name="comentaris" class="form-control" :class="{ 'is-invalid': errors.comentaris }"
                        v-model="form.comentaris">
                    </Field>
                    <div class="invalid-feedback">
                        {{ errors.comentaris }}
                    </div>
                </div>
                <!-- Valoració  -->
                <div class="mx-auto p-2" style="width: 200px;">
                    <br>
                    <vue3-star-ratings :numberOfStars="5" inactiveColor="#DDDDDD" :showControl="false" v-model="form.stars"/>
                </div><br><br>
            </div>
            <!--Submit-->
            <div class="form-group mt-3 mb-3 d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary mr-1 me-lg-3" @click="handleCloseEmpresa()">
                    {{ $t("Crear Estada") }}
                </button>
                <Link :href="route('collaboracio.index')" as="button" class="btn btn-secondary">{{ $t("Cancel·lar") }}</Link>
            </div>
        </Form><br><br><br>
    </AuthenticatedLayout>
</template>

<style scoped>
.serverError {
    color: rgb(202, 8, 8);
    background-color: rgb(252, 239, 183);
    border-radius: 5px;
    padding: 20px 20px 5px 10px;
    margin-bottom: 20px;
}
</style>