import { ref, onMounted } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

export function usePagination<T>(url: string) {
    const items = ref<T[]>([]);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const loading = ref(false);
    const searchQuery = ref('');
    const statusFilter = ref<string | null>(null);

    //obtain token
    const page = usePage();
    const token = page.props.auth_token;

    async function fetchItems(page = 1) {
        loading.value = true;
        try {
            const { data } = await axios.get(url, {
                params: {
                    page,
                    search: searchQuery.value,
                    status: statusFilter.value
                },
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            items.value = data.data;
            currentPage.value = data.current_page;
            lastPage.value = data.last_page;

        } catch (error) {
            console.log(error)
        } finally {
            loading.value = false;
        }
    }

    function goToPage(page: number) {
        if (page > 0 && page <= lastPage.value) {
            fetchItems(page);
        }
    }

    function setSearchQuery(query: string) {
        searchQuery.value = query;
        fetchItems(1);
    }

    function setStatusFilter(status: string | null) {
        statusFilter.value = status;
        fetchItems(1);
    }

    onMounted(() => {
        fetchItems();
    });

    return {
        items,
        currentPage,
        lastPage,
        fetchItems,
        goToPage,
        setSearchQuery,
        setStatusFilter,
        loading
    };
}
