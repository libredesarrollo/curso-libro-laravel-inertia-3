<template>
    <div>
        <div class="flex" v-if="$page.props.step != 4">
            <div class="flex mx-auto flex-col sm:flex-row">
                <div class="step" :class="{ active: $page.props.step == 1 }">
                    STEP 1
                </div>
                <div class="step" :class="{ active: parseInt($page.props.step) == 2 }">
                    STEP 2
                </div>
                <div class="step" :class="{ active: $page.props.step == 3 }">
                    STEP 3
                </div>
            </div>
        </div>

        <contact-layout>
            <ContactGeneral v-if="$page.props.step == 1" :errors="errors" :contactGeneral="contactGeneral" />
            <div v-if="contactGeneral">
                <ContactCompany :contactGeneralId="contactGeneral.id" @back-step-event="backStep"
                    v-if="$page.props.step == 2" :errors="errors" :contactCompany="contactGeneral.company" />
                <ContactPerson :contactGeneralId="contactGeneral.id" @back-step-event="backStep"
                    v-if="$page.props.step == 2.5" :errors="errors" :contactPerson="contactGeneral.person" />
                <ContactDetail :contactGeneralId="contactGeneral.id" @back-step-event="backStep"
                    v-if="$page.props.step == 3" :errors="errors" :contactDetail="contactGeneral.detail" />
                <div v-if="$page.props.step == 4">END</div>
            </div>
        </contact-layout>
    </div>
</template>
<script setup  lang="ts">
import ContactLayout from '@/layouts/contact/Layout.vue';
import ContactGeneral from "@/pages/contact/general/Form.vue"
import ContactCompany from "@/pages/contact/company/Form.vue"
import ContactPerson from "@/pages/contact/person/Form.vue"
import ContactDetail from "@/pages/contact/detail/Form.vue"

import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    errors: Object
    contactGeneral?: {              // Objeto opcional
        id: string;
        subject: string;
        type: string;
        message: string;
    };
}>();

const page = usePage();

function backStep(value: number) {
    console.log(value)
    console
    if (value == 2 && props.contactGeneral && props.contactGeneral.type) {
        // paso 2
        if (props.contactGeneral.type == 'person') {
            page.props.step = 2.5
        } else {
            page.props.step = 2
        }
    } else {
        // paso 1
        page.props.step = value
    }
}

</script>