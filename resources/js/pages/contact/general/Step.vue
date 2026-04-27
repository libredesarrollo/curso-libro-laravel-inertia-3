<template>
    <div>
        <div class="flex" v-if="$page.props.step != 4">
            <div class="mx-auto flex flex-col sm:flex-row">
                <div class="step" :class="{ active: $page.props.step == 1 }">
                    STEP 1
                </div>
                <div
                    class="step"
                    :class="{ active: parseInt($page.props.step) == 2 }"
                >
                    STEP 2
                </div>
                <div class="step" :class="{ active: $page.props.step == 3 }">
                    STEP 3
                </div>
            </div>
        </div>

        <layout>
            <ContactGeneral
                v-if="!$page.props.step || $page.props.step == 1"
                :contactGeneral="contactGeneral"
            />
            <!-- <ContactGeneral
                v-if="!$page.props.step || $page.props.step == 1"
                :errors="errors"
                :contactGeneral="contactGeneral"
            /> -->
            <div v-if="contactGeneral">
                <ContactCompany
                    :contactGeneralId="contactGeneral.id"
                    @back-step-event="backStep"
                    v-if="$page.props.step == 2"
                    :contactCompany="contactGeneral.company"
                />
                <ContactPerson
                    :contactGeneralId="contactGeneral.id"
                    @back-step-event="backStep"
                    v-if="$page.props.step == 2.5"
                    :contactPerson="contactGeneral.person"
                />
                <ContactDetail
                    :contactGeneralId="contactGeneral.id"
                    @back-step-event="backStep"
                    v-if="$page.props.step == 3"
                    :contactDetail="contactGeneral.detail"
                />
                <div v-if="$page.props.step == 4" class="step-end">
                    <span class="step-end-text">END</span>
                    <span class="step-end-subtitle"
                        >Thank you for your submission!</span
                    >
                </div>
            </div>
        </layout>
    </div>
</template>
<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import Layout from '@/layouts/contact/Layout.vue';
import ContactCompany from '@/pages/contact/company/Form.vue';
import ContactDetail from '@/pages/contact/detail/Form.vue';
import ContactGeneral from '@/pages/contact/general/Form.vue';
import ContactPerson from '@/pages/contact/person/Form.vue';

const props = defineProps<{
    // errors: object;
    contactGeneral?: {
        // Objeto opcional
        id: string;
        subject: string;
        type: string;
        message: string;
    };
}>();

const page = usePage();

function backStep(value: number) {

    if (value == 2 && props.contactGeneral && props.contactGeneral.type) {
        // paso 2
        if (props.contactGeneral.type == 'person') {
            page.props.step = 2.5;
        } else {
            page.props.step = 2;
        }
    } else {
        // paso 1
        page.props.step = value;
    }
}
</script>
