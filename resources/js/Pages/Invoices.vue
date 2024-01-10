<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
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
    id: null,
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
    if (typeof item.id === 'number') {
        form.id = item.id;
    }
    if (typeof item.invoice_number === 'string') {
        form.invoice_number = item.invoice_number;
    }
    if (typeof item.customer_tax_id === 'string') {
        form.customer_tax_id = item.customer_tax_id;
    }
    if (typeof item.seller_tax_id === 'string') {
        form.seller_tax_id = item.seller_tax_id;
    }
    if (typeof item.product_name === 'string') {
        form.product_name = item.product_name;
    }
    if (typeof item.net_price === 'string') {
        form.net_price = item.net_price;
    }
    formIsVisible.value = true;
}

function closeForm() {
    formIsVisible.value = false;
    form.reset();
}

function submitForm() {
    form.post(route('invoices.save'), {
        preserveScroll: true,
        onSuccess: () => closeForm()
    });
}

function deleteInvoice(item) {
    router.delete(route('invoices.delete', {id: item.id}), {
        onError: errors => console.log(errors)
    });
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
                        <div class="flex flex-row space-x-3 justify-end">
                            <PrimaryButton @click="showForm({})">
                                Dodaj fakturę
                            </PrimaryButton>
                        </div>
                        <Modal :show="formIsVisible" @close="closeForm">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Wprowadź dane faktury
                                </h2>
                                <form @submit.prevent="form.post('/invoices')">
                                    <input type="hidden" id="id" v-model="form.id" />
                                    <div class="mt-6">
                                        <InputLabel for="invoice_number" value="Nr faktury" class="sr-only" />
                                        <TextInput 
                                            id="invoice_number"
                                            v-model="form.invoice_number" 
                                            class="mt-1 block w-3/4" 
                                            placeholder="Numer faktury"
                                        />

                                        <InputError :message="form.errors.invoice_number" class="mt-2" />
                                    </div>

                                    <div class="mt-6">
                                        <InputLabel for="customer_tax_id" value="NIP kupującego" class="sr-only" />
                                        <TextInput 
                                            id="customer_tax_id"
                                            v-model="form.customer_tax_id" 
                                            class="mt-1 block w-3/4" 
                                            placeholder="NIP kupującego"
                                        />

                                        <InputError :message="form.errors.customer_tax_id" class="mt-2" />
                                    </div>

                                    <div class="mt-6">
                                        <InputLabel for="seller_tax_id" value="NIP sprzedającego" class="sr-only" />
                                        <TextInput 
                                            id="seller_tax_id"
                                            v-model="form.seller_tax_id" 
                                            class="mt-1 block w-3/4" 
                                            placeholder="NIP sprzedającego"
                                        />

                                        <InputError :message="form.errors.seller_tax_id" class="mt-2" />
                                    </div>

                                    <div class="mt-6">
                                        <InputLabel for="product_name" value="Nazwa produktu" class="sr-only" />
                                        <TextInput 
                                            id="product_name"
                                            v-model="form.product_name" 
                                            class="mt-1 block w-3/4" 
                                            placeholder="Nazwa produktu"
                                        />

                                        <InputError :message="form.errors.product_name" class="mt-2" />
                                    </div>

                                    <div class="mt-6">
                                        <InputLabel for="net_price" value="Kwota netto" class="sr-only" />
                                        <TextInput 
                                            id="net_price"
                                            v-model="form.net_price" 
                                            class="mt-1 block w-3/4" 
                                            placeholder="Kwota netto"
                                        />

                                        <InputError :message="form.errors.net_price" class="mt-2" />
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <SecondaryButton @click="closeForm">Anuluj</SecondaryButton>
                                        <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing" @click="submitForm">
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
                                <th class="pb-4 pt-6 px-1 w-28 text-center">Funkcje</th>
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
                                <td class="border-t p-1 flex space-x-1 justify-end">
                                    <button @click="showForm(item)" class="text-white text-xs bg-slate-500 rounded p-1 hover:bg-slate-400">Edytuj</button>
                                    <button @click="deleteInvoice(item)" class="text-white text-xs bg-slate-500 rounded p-1 hover:bg-slate-400">Usuń</button>
                                </td>
                            </tr>
                            <tr v-if="list.length === 0">
                                <td class="px-6 py-4 border-t" colspan="8">No items found.</td>
                            </tr>
                            <tfoot v-if="numPages > 0">
                                <tr>
                                    <td class="px-0 py-4 border-t" colspan="8">
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