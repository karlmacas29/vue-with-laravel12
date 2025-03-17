<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';
import axios from 'axios';

const toast = useToast();
const route = useRoute();
const router = useRouter();

const form1 = reactive({
    name: "",
    description: "",
    image: null, // Store the actual File object for upload
    type: "",
    quantity: "",
    price: "",
});

const previewImage = ref(null); // Store image preview URL
let errors = ref([]);

const editProduct = async () => {
    try {
        const response = await axios.get(`/api/products/${route.params.id}/edit`);
        const product = response.data.products;

        form1.name = product.name;
        form1.description = product.description;
        form1.type = product.type;
        form1.quantity = product.quantity;
        form1.price = product.price;
        previewImage.value = product.image ? `http://127.0.0.1:8000/${product.image}` : null;
    } catch (error) {
        console.error("Error fetching product:", error);
        toast.error("Failed to load product details.");
    }
};

// Handle file input change
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form1.image = file; // Store file object
        previewImage.value = URL.createObjectURL(file); // Preview new image
    }else{
        console.log('File is null')
    }
};

const updateProduct = async () => {
        const formData = new FormData();
        formData.append("name", form1.name);
        formData.append("description", form1.description);
        formData.append("type", form1.type);
        formData.append("quantity", form1.quantity);
        formData.append("price", form1.price);

        if (form1.image instanceof File) {
            formData.append("image", form1.image);
            formData.append("has_new_image", "1"); // Flag for backend
        } else {
            formData.append("has_new_image", "0"); // No new image
        }

        console.log("Form data being sent:");
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        formData.append('_method', 'PUT'); // Add this line
        await axios.post(`/api/products/${route.params.id}`, formData,
            {
                headers: { "Content-Type": "multipart/form-data" },
                withCredentials: true, // If needed for authentication
            }
        ).then((response)=>{
        toast.success("Product Updated Successfully");
        router.push("/");
     }).catch((error)=>{
        if (error.response && error.response.data.errors) {
            errors.value = error.response.data.errors; // Store errors in ref

            if (errors.value.name) {
                toast.error('Error => ' + errors.value.name[0]); // Access first error message
            }

            if (errors.value.description) {
                toast.error('Error => ' + errors.value.description[0]); // Access first error message
            }

            if (errors.value.image) {
                toast.error('Error => ' + errors.value.image[0]); // Access first error message
            }
            console.log(errors.value);
        }
        
     })
};

onMounted(() => {
    editProduct();
});
</script>

<template>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-start mx-auto p-4">
            <button @click="router.push('/')" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </button>
            <h1 class="text-2xl">Back</h1>
        </div>
    </nav>

    <form @submit.prevent="updateProduct" class="max-w-sm mx-auto">
        <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="col-span-2">
                <!-- Image Upload -->
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                <input type="file" accept="image/png, image/jpeg" @change="handleFileUpload" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">PNG or JPG (MAX. 800x400px).</p>

                <!-- Image Preview -->
                <div v-if="previewImage" class="mt-2">
                    <p class="text-sm text-gray-700 dark:text-gray-300">Current Image:</p>
                    <img :src="previewImage" alt="Preview" class="w-40 h-40 object-cover rounded-lg shadow-md">
                </div>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input type="text" v-model="form1.name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Type product name" required>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                <select v-model="form1.type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    <option disabled>Select category</option>
                    <option>TV/Monitors</option>
                    <option>PC</option>
                    <option>Gaming/Console</option>
                    <option>Phones</option>
                    <option>Others</option>
                </select>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                <input v-model="form1.quantity" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500" placeholder="0" required>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-md dark:bg-gray-600 dark:text-gray-400">$</span>
                    <input v-model="form1.price" type="number" class="rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600" placeholder="0" required>
                </div>
            </div>

            <div class="col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Description</label>
                <textarea v-model="form1.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
            </div>
        </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">
            Update
        </button>
    </form>
</template>
