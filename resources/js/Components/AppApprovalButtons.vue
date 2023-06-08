<template>
    <div>
        <approval-modal :payload="approvalPayload" @reset="resetApprovalPayload"/>

        <div class="flex space-x-4 justify-end">
            <primary-button v-if="model.can.approve" @click.prevent="approveModel">Approve</primary-button>
            <secondary-button v-if="model.can.edit" @click.prevent="voidModel">Void</secondary-button>
            <danger-button v-if="model.can.reverse" @click.prevent="reverseModel">Reverse</danger-button>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import DangerButton from "./DangerButton.vue";
import PrimaryButton from "./PrimaryButton.vue";
import ApprovalModal from "./ApprovalModal.vue";
import SecondaryButton from "./SecondaryButton.vue";

 const props = defineProps({
     model: Object,
     modelString: String
 })

 const approvalPayload = ref({
     show: false,
     action: 'approve',
     id: props.model.id,
     model: props.modelString
 })

 const resetApprovalPayload = () => {
     approvalPayload.value = {
         ...approvalPayload.value,
         show: false,
     }
 }

 const approveModel = () => {
     approvalPayload.value = {
         ...approvalPayload.value,
         show: true,
         action: 'approve'
     }
 }

 const reverseModel = () => {
     approvalPayload.value = {
         ...approvalPayload.value,
         show: true,
         action: 'reverse'
     }
 }

 const voidModel = () => {
     approvalPayload.value = {
         ...approvalPayload.value,
         show: true,
         action: 'void'
     }
 }
</script>
