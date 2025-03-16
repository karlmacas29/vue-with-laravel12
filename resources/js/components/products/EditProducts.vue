<script setup>
import { onMounted, reactive ,ref} from 'vue'

import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'
const toast = useToast();

const routes = useRoute()
const router = useRouter()

const form1 = reactive({
    name: "",//
    description: "",//
    image: "",//
    type: "", //
    quantity: "", //
    price: ""//
})

let errors = ref([])

const editProduct = async() => {
  await axios.get(`/api/products/${routes.params.id}/edit`).then((response)=>{
    form1.name = response.data.products.name
    form1.description = response.data.products.description
    form1.image = response.data.products.image
    form1.type = response.data.products.type
    form1.quantity = response.data.products.quantity
    form1.price = response.data.products.price
  })
}

const updateProduct = async() => {
    await axios.put(`/api/products/${routes.params.id}`, form1).then((response)=>{
    router.push('/')
    toast.success('Update Products Success')
  }).catch((error)=>{
    if (error.response && error.response.data.errors) {
            errors.value = error.response.data.errors; // Store errors in ref

            if (errors.value.name) {
                toast.error('Error => ' + errors.value.name[0]); // Access first error message
            }

            if (errors.value.description) {
                toast.error('Error => ' + errors.value.description[0]); // Access first error message
            }
        } 
     })
}
onMounted(()=>{
    editProduct()
})
</script>
<template>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-start mx-auto p-4">
        
        <button @click="router.push('/')" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg  hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </button>
        <h1 class="text-2xl">Back</h1>
    </div>
    </nav>
    <form class="max-w-sm mx-auto">
        <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <!-- images -->
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                            <input accept="image/*"  class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" type="file">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG or JPG (MAX. 800x400px).</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <!-- Name -->
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" v-model="form1.name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <!-- type -->
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                            <select v-model="form1.type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
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
                            <input v-model="form1.quantity" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <!-- price -->
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                $
                                </span>
                                <input v-model="form1.price" type="number" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="0" required="">
                            </div>
                        </div>
                        <div class="col-span-2">
                            <!-- desc -->
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Description</label>
                            <textarea v-model="form1.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write product description here"></textarea>                    
                        </div>
                    </div>
                    <button  @click="updateProduct" type="button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Update
                    </button>
    </form>
</template>