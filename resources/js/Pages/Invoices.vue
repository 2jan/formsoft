<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    list: Array,
    numPages: Number,
    currentPage: Number,
    href: String
});

const formIsVisible = ref(false);

const form = useForm({
    invoice_number: null,
    customer_tax_id: null,
    seller_tax_id: null,
    product_name: null,
    net_price: null
});

function formatDate(date) {
    const d = new Date(date);
    //console.log(new Intl.DateTimeFormat('pl-PL').format(d));
    return d.getFullYear() + '-' + (1 + d.getMonth()).toString().padStart(2, '0') + '-' + d.getDate().toString().padStart(2, '0');
}

function showForm(item) {
    formIsVisible.value = true;
}

function closeForm() {
    formIsVisible.value = false;
    form.reset();
}

function testForm() {
    console.log(form);
}

</script>

<template>
    <Head title="Invoices" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoices</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <PrimaryButton @click="showForm({})">Test</PrimaryButton>
                        <Modal :show="formIsVisible" @close="closeForm">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Wprowadź dane faktury
                                </h2>
                                <form @submit.prevent="form.post('/invoices')">
                                    <div class="mt-6">
                                        <label for="invoice_number">Nr faktury:</label>
                                        <input id="invoice_number" v-model="form.invoice_number" />
                                    </div>

                                    <div class="mt-6">
                                        <label for="customer_tax_id">NIP kupującego:</label>
                                        <input id="customer_tax_id" v-model="form.customer_tax_id" />
                                    </div>
                                    
                                    <div class="mt-6">
                                        <label for="seller_tax_id">NIP sprzedającego:</label>
                                        <input id="seller_tax_id" v-model="form.seller_tax_id" />
                                    </div>
                                    
                                    <div class="mt-6">
                                        <label for="product_name">Nazwa produktu:</label>
                                        <input id="product_name" v-model="form.product_name" />
                                    </div>
                                    
                                    <div class="mt-6">
                                        <label for="net_price">Kwota netto:</label>
                                        <input id="net_price" v-model="form.net_price" />
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <SecondaryButton @click="closeForm">Anuluj</SecondaryButton>
                                        <PrimaryButton
                                            class="ms-3"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click="testForm"
                                        >
                                            Zapisz
                                        </PrimaryButton>
                                    </div>

                                    <button type="submit"></button>
                                </form>
                            </div>

                        </Modal>

                        <table class="w-full whitespace-nowrap table-fixed">
                            <tr class="text-left font-bold ">
                                <th class="pb-4 pt-6 px-1">Nr faktury</th>
                                <th class="pb-4 pt-6 px-1">NIP kupującego</th>
                                <th class="pb-4 pt-6 px-1">NIP sprzedającego</th>
                                <th class="pb-4 pt-6 px-1">Nazwa produktu</th>
                                <th class="pb-4 pt-6 px-1">Kwota netto</th>
                                <th class="pb-4 pt-6 px-1">Data wystawienia</th>
                                <th class="pb-4 pt-6 px-1">Data edycji</th>
                                <th class="pb-4 pt-6 px-1">Funkcje</th>
                            </tr>
                            <tr v-for="item in list" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                <td class="border-t p-1">
                                    {{ item.invoice_number }}
                                </td>
                                <td class="border-t p-1">
                                    {{ item.customer_tax_id }}
                                </td>
                                <td class="border-t p-1">
                                    {{ item.seller_tax_id }}
                                </td>
                                <td class="border-t p-1">
                                    {{ item.product_name }}
                                </td>
                                <td class="border-t p-1">
                                    {{ item.net_price }}
                                </td>
                                <td class="border-t p-1">
                                    {{ formatDate(item.created_at) }}
                                </td>
                                <td class="border-t p-1">
                                    {{ formatDate(item.updated_at) }}
                                </td>
                                <td class="border-t p-1">
                                    <button @click="showForm(item)">Edytuj</button>
                                </td>
                            </tr>
                            <tr v-if="list.length === 0">
                                <td class="px-6 py-4 border-t" colspan="8">No items found.</td>
                            </tr>
                            <tfoot v-if="numPages > 0">
                                <tr>
                                    <td class="px-6 py-4 border-t" colspan="8">
                                        <Pagination :href="href" :currentPage="currentPage" :numPages="numPages">
                                        </Pagination>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>