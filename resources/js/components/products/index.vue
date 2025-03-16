<script setup>
import ProductsTable from '@/components/products/ProductsTable.vue'
import { reactive, ref } from 'vue'
import { useToast } from 'vue-toastification'
const toast = useToast();

const form = reactive({
    name: "",//
    description: "",//
    image: "",//
    type: "", //
    quantity: "", //
    price: ""//
})

let errors = ref([])
const isModal = ref(false)

const handeFileChange = (e) => {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onloadend = (file) => {
        form.image = reader.result; // Convert to Base64
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

const handleSave = async() => {
    await axios.post('http://127.0.0.1:8000/api/products', form, { 
        headers: {
            'Content-Type': 'application/json'
        },
        withCredentials: true // Required for Laravel Sanctum
     }).then((response)=>{
        toast.success('Product Added Successfully')
        form.name = ''
        form.description = ''
        form.image = ''
        form.price = ''
        form.quantity = ''
        form.type = ''
        isModal.value = false
     }).catch((error)=>{
        if (error.response && error.response.data.errors) {
            errors.value = error.response.data.errors; // Store errors in ref

            if (errors.value.name) {
                toast.error('Error => ' + errors.value.name[0]); // Access first error message
            }

            if (errors.value.description) {
                toast.error('Error => ' + errors.value.description[0]); // Access first error message
            }
            console.log(errors.value);
        }
        
     })
}


</script>

<template>
    <div class="mx-auto px-6 py-3 w-250">
        <div class="flex justify-between my-3">
            <h1 class="text-3xl font-bold">Products List</h1>
            <button type="button" @click="isModal = true" class="block cursor-pointer bg-blue-600 text-white px-5 py-2 rounded-md ">Add Product</button>
        </div>
        
        <ProductsTable></ProductsTable>
    </div>

    <!-- Main modal -->
    <div v-if="isModal"  class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800/50 max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Create New Product
                    </h3>
                    <button @click="isModal = false" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <!-- images -->
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                            <input accept="image/*" @change="handeFileChange" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" type="file">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG or JPG (MAX. 800x400px).</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <!-- Name -->
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" v-model="form.name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <!-- type -->
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                            <select v-model="form.type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option selected="" disabled>Select category</option>
                                <option>TV/Monitors</option>
                                <option>PC</option>
                                <option>Gaming/Console</option>
                                <option>Phones</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <!-- quantity -->
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                            <input v-model="form.quantity" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <!-- price -->
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                $
                                </span>
                                <input v-model="form.price" type="number" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="0" required="">
                            </div>
                        </div>
                        <div class="col-span-2">
                            <!-- desc -->
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Description</label>
                            <textarea v-model="form.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write product description here"></textarea>                    
                        </div>
                    </div>
                    <button type="button" @click.prevent="handleSave" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Add new product
                    </button>
                </form>
            </div>
        </div>
    </div> 
</template>