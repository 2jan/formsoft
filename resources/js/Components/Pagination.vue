<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    currentPage: {
        type: Number,
        required: true,
    },
    numPages: {
        type: Number,
        required: true,
    }
});

const links = computed(() => {
    const result = [];
    if (props.numPages < 2) {
        return result;
    }
    const min = props.currentPage > 1 ? props.currentPage - 1 : 1;
    const max = props.currentPage < props.numPages ? props.currentPage + 1 : props.numPages;

    if (min > 1) {
        result.push({ id: 1, link: props.href + '?page=1', first: true});
    }
    
    for (let i = min; i <= max; i++) {
        result.push({ id: i, link: props.href + '?page=' + i, current: i === props.currentPage });
    }

    if (max < props.numPages) {
        result.push({ id: props.numPages, link: props.href + '?page=' + props.numPages, last: true });
    }
    return result;
});

function getStyles(item) {
    let result = 'items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-gray-700 disabled:bg-slate-100 disabled:text-black';
    if (item.first) {
        result += ' mr-8';
    } else if (item.last) {
        result += ' ml-8';
    }
    return result;
}
</script>

<template>
    <div class="flex flex-row space-x-3 justify-end">
        <div v-for="link in links" :key="link.id" >
            <Link 
                :href="link.link" 
                :disabled="link.current"
                as="button" 
                type="button"
                :class="getStyles(link)"
            >
                {{ link.id }}
            </Link>
        </div>
    </div>
</template>