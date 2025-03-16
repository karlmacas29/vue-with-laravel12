<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/194/LpHvHmvUKRwWojXHVS9uThtKeNLqWv-metabGFyYXZlbC12dWUuanBn-.jpg" width="400" alt="Laravel With Vue"></a></p>

# Laravel 12 (Backend) With Vue 3 (Frontend)(CRUD) Building a Single Page Application
- has working search query
- has a working pagination
- using sqlite as example database
- vue3 Composition API 

<p align="center"><a href="#" target="_blank"><img src="https://github.com/user-attachments/assets/37671a0b-e808-4cb4-82d2-c45307416468" width="400" alt="Laravel With Vue"></a></p>
  
# Basic create and install Laravel with vue (simple template guide)
- composer create-project laravel/laravel laravel-anyname-vue

# Install a vue, vue router and loader in vite plugin
- npm install vue@latest vue-loader@latest vue-router@latest @vitejs/plugin-vue --save-dev

# Configure in vite.config.js structure like this:
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue' //add a vue plugin

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue() //add a vue plugin
    ],
});

# Go to resources/js and add a component folder and create App.vue file
<template></template>
# Go to app.js and configure like this for adding vue-router plugin:

import './bootstrap';

import {createApp} from 'vue'

import app from '@/components/App.vue'

import router from '@/router'

createApp(app).use(router).mount("#app")


# Create router folder and create index.js structure like this:

import {createRouter, createWebHistory} from 'vue-router'

import productIndex from '@/components/products/index.vue'

const routes = [
    {
        path: '/',
        name: 'products.index',
        component: productIndex
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router


# Run this command (new) this command run both php artisan serve and npm run dev at a same time and make sure open localhost from this line says  INFO  Server running on [http://127.0.0.1:8000].

- composer run dev

# Edit .env if you want to run MySQL or any in Database or you can keep local database like sqlite
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# Create a Model(table) with Controller to the database
- php artisan make:model Product -mc

# Now go to databases/migration and find a php file that name a timestamp today together with Product model name ex: '2025_03_16_044230_create_products_table.php' and find a code that structure like this:

 public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
		/** add a $table->datatypes('name of the data')->is null or not; //look for other 		*documentation for this code
		*/
            $table->timestamps();
        });
    }

# After that you will run this code for changes in database
- php artisan migrate

# Add plugin for vue inside laravel is same as you add package in vue example is toastification
npm install --save vue-toastification@next


# Create api Laravel and vue
- php artisan install:api

# Go to app/Models/User.php add HasApiTokens like this structure

 use HasFactory, Notifiable , HasApiTokens;

# Install Intervention Image (this package when you have a image uploader in your project and upload via databases in laravel)
- composer require intervention/image-Laravel
- php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"





